@extends( vendorTheme().'.layout.admin' , [
    'header' => '',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="card shadow mb-4" id="vendor_messages">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Packages') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">
                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ route('') }}"
                       data-orderable="1"
                       data-success="pageOrderPackages"
                       data-placeholder="ID, Headline">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Taşıyıcı Firma') }}</th>
                        <th>{{ _('Takip No') }}</th>
                        <th>{{ _('Durum') }}</th>
                        <th>{{ _('Sipariş') }}</th>
                        <th>{{ _('Etiket') }}</th>
                        <th>{{ _('Not') }}</th>
                        <th>{{ _('Etiket yazdır') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>


@endsection
