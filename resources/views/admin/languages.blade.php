@extends( 'admin.layout.admin')

@section('content')

    <div class="card shadow mb-4"  id="admin_languages">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Languages') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">


                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.languages.ajax') }}"
                       data-orderable="5,6"
                       data-success="adminLanguages"
                       data-placeholder="ID, Question"
                       data-data="{{ base64data([
                            'update_url' => route('admin_languages.show', '###')
                        ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Name') }}</th>
                        <th>{{ _('Active') }}</th>
                        <th>{{ _('Currency') }}</th>
                        <th>{{ _('Code') }}</th>
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
