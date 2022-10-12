@extends( adminTheme('layout.admin'))

@section('top')

    <button class="btn btn-sm btn-primary open-modal" data-url="{{ route('admin_user_groups.create') }}" type="button">
        <i class="fas fa-plus"></i>
    </button>

@endsection

@section('content')

    <div class="card shadow mb-4" id="admin_users">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('User Groups') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('api_admin_user_groups_ajax') }}"
                       data-orderable="2,3"
                       data-success="adminUserGroups"
                       data-placeholder="ID, Name, Email"
                       data-data="{{ base64data([
                            'update_url' => route('admin_user_groups.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th class="w-50">{{ _('Name') }}</th>
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
