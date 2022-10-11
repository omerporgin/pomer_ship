@extends( vendorTheme().'.layout.admin' , [
    'header' => 'New Order',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')

    <button type="button" data-url="{{ route('vendor_order_download') }}"
            data-on_action="<span class='pr-3'><span class='spinner-grow spinner-grow-sm' role='status' aria-hidden='true'></span></span>Downloading"
            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm download-orders">
        <i class="fas fa-download fa-sm text-white-50"></i> Siparişleri indir
    </button>

    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm open-modal" data-onload="vendorOrderModal"
          data-url="{{ route('vendor_orders.create') }}">
         <i class="fas fa-plus"></i>
    </button>

@endsection

@section('content')

    <div class="card shadow mb-4" id="vendor_order">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
        </div>
        <div class="card-body">

            <div class="selections_container">
                <div class="row">
                    <div class="col-4">
                        <select class="order_status_select2" name="status" multiple
                                data-placeholder="{{ _('Select order status') }}">
                            <option value="">{{ _('All') }}</option>
                            @foreach($order_status as $status)
                                <option value="{{ $status->id }}" style="color:#fff;background:#{{ $status->color }}"
                                        @if($selectedOrderStatus==$status->id) selected @endif>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="table_container">

                <table id="order_table" class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ route('vendor.orders.ajax') }}"
                       data-upload_invoice_url="{{ route('api_upload_invoice') }}"
                       data-orderable="1"
                       data-paging="false"
                       data-success="orders"
                       data-placeholder="{{ _('OrderID, Email, Name') }}"
                       data-child_row_format="orderPage"
                       data-data="{{ base64data([
                            'template' => vendorTheme(),
                            'order_statuses' => [
                                $selectedOrderStatus
                            ],
                            'update_url' => route('vendor_orders.show', ['vendor_order'=>'###'])
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Order Id') }}</th>
                        <th>{{ _('Status') }}</th>
                        <th>{{ _('Ent.') }}</th>
                        <th>{{ _('Email') }}</th>
                        <th>{{ _('Alıcı') }}</th>
                        <th>{{ _('Takip NO') }}</th>
                        <th>{{ _('Taşıyıcı') }}</th>
                        <th>{{ _('Ülke') }}</th>
                        <th>{{ _('Paket Adedi') }}</th>
                        <th>{{ _('Update') }}</th>
                        <th>{{ _('Delete') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <span class="select_all"><i class="far fa-check-square"></i> Select All</span>
            </div>
        </div>
    </div>

    @include(vendorTheme().'.templates.orderPackageTemplate',[
         'shippings' => $shippings,
    ])

    @include(vendorTheme().'.templates.addLocation')

    @include(vendorTheme().'.templates.orderTableChild')

@endsection
