@extends( adminTheme().'.layout.admin' , [
    'header' => 'Cities',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="card shadow mb-4" id="admin_currencies">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Cities') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.location_city.ajax') }}"
                       data-orderable="5,6"
                       data-success="adminCity"
                       data-placeholder="ID, Country name, City name, District name"
                       data-data="{{ base64data([
                            'update_url' => route('admin_location_city.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Country') }}</th>
                        <th>{{ _('State') }}</th>
                        <th>{{ _('Name') }}</th>
                        <th>{{ _('Approved') }}</th>
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
