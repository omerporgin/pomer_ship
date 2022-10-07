@extends( adminTheme().'.layout.admin' , [
    'header' => 'Currencies',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="card shadow mb-4" id="admin_currencies">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Currencies') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.currencies.ajax') }}"
                       data-orderable="6,7"
                       data-success="adminCurrencies"
                       data-placeholder="ID, Country, Code, Symbol"
                       data-data="{{ base64data([
                            'update_url' => route('admin_currencies.show', '###')
                        ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Country') }}</th>
                        <th>{{ _('Active') }}</th>
                        <th>{{ _('Currency') }}</th>
                        <th>{{ _('Code') }}</th>
                        <th>{{ _('Symbol') }}</th>
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
