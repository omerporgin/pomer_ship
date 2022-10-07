@extends( adminTheme().'.layout.admin' , [
    'header' => _('Countries'),
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="card shadow mb-4" id="admin_currencies">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Countries') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.location_country.ajax') }}"
                       data-orderable="9,10"
                       data-success="adminCountry"
                       data-placeholder="ID, Name, Iso2, ıso3"
                       data-data="{{ base64data([
                            'update_url' => route('admin_location_country.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Country') }}</th>
                        <th>{{ _('Iso3') }}</th>
                        <th>{{ _('Iso2') }}</th>
                        <th>{{ _('Capital') }}</th>
                        <th>{{ _('Currency') }}</th>
                        <th>{{ _('Region') }}</th>
                        <th>{{ _('Sub Region') }}</th>
                        <th>{{ _('Accepted') }}</th>
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
