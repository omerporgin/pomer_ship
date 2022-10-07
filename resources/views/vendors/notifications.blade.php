@extends( vendorTheme().'.layout.admin' , [
    'header' => '',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="card shadow mb-4" id="vendor_notifications">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Packages') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ route('api_vendor_alert.index') }}"
                       data-orderable=""
                       data-success="vendorNotifications"
                       data-placeholder="IDe">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('notification') }}</th>
                        <th>{{ _('is_read') }}</th>
                        <th>{{ _('data') }}</th>
                        <th>{{ _('date') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>


@endsection
