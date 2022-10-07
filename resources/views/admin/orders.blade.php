@extends( adminTheme().'.layout.admin' , [
    'header' => 'New Order',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')

@endsection

@section('content')

    <div class="card shadow mb-4" id="admin_order">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
        </div>
        <div class="card-body">

            <div class="selections_container">
                <div class="row">
                    <div class="col-4">
                        <select class="order_status_select2" name="status" multiple data-placeholder="{{ _('Select order status') }}">
                            <option value="">{{ _('All') }}</option>
                            @foreach($order_status as $status)
                                <option value="{{ $status->id }}"  style="color:#fff;background:#{{ $status->color }}" @if($selectedOrderStatus==$status->id) selected @endif>{{ $status->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <select class="select2_vendor" name="vendors[]" multiple="multiple" style="width:100%"
                                data-url="{{ route("vendor_select2") }}"
                                data-placeholder="{{ _('Select vendors') }}"></select>
                    </div>
                    <div class="col-4">
                        <button class="btn btn-primary btn-sm text-uppercase" id="filter_orders">{{ _('search') }}</button>
                    </div>
                </div>
            </div>

            <div class="table_container">
                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.orders_ajax') }}"
                       data-orderable="1"
                       data-paging="true"
                       data-success="orders"
                       data-placeholder="ID, Headline"
                       data-data="{{
                            base64data([
                                'template' => adminTheme(),
                                'order_statuses' => [
                                    $selectedOrderStatus
                                ],
                                'update_url' => route('admin_orders.show', '###')
                            ])
                        }}"
                       data-child_row_format="">
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
                        <th>{{ _('Upload Invoice') }}</th>
                        <th>{{ _('Update') }}</th>
                        <th>{{ _('Delete') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
