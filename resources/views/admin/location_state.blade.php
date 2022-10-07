@extends( adminTheme().'.layout.admin' , [
    'header' => 'States',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="card shadow mb-4" id="admin_currencies">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('States') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.location_state.ajax') }}"
                       data-orderable="6,5"
                       data-success="adminState"
                       data-placeholder="ID, Country name, City name"
                       data-data="{{ base64data([
                            'update_url' => route('admin_location_state.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Country code') }}</th>
                        <th>{{ _('Country') }}</th>
                        <th>{{ _('Name') }}</th>
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
