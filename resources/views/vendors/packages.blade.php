@extends( vendorTheme().'.layout.admin' , [
    'header' => 'New Order',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')

@endsection

@section('content')

    <div class="card shadow mb-4" id="vendor_packages">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                @if($type=='confirmed')
                    {{ _('Confirmed Packages') }}
                @else
                    {{ _('Waiting Packages') }}
                @endif
            </h6>
        </div>
        <div class="card-body">

            <div class="table_container">
                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('vendor.packages.ajax') }}"
                       data-orderable="1"
                       data-success="packages"
                       data-data="{{ base64data([
                            'type'=>$type
                       ]) }}"
                       data-placeholder="ID, Headline">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Shipping Service') }}</th>
                        <th>{{ _('Tractking Id') }}</th>
                        <th>{{ _('Status') }}</th>
                        <th>{{ _('Order') }}</th>
                        <th>{{ _('Label') }}</th>
                        <th>{{ _('Price') }}</th>
                        <th>{{ _('Notes') }}</th>
                        <th>{{ _('Print Label') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
