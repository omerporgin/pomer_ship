@extends( adminTheme().'.layout.admin')


@section('top')
    <button class="btn btn-sm btn-primary open-modal" data-url="{{ route('admin_users.create') }}" type="button">
        <i class="fas fa-plus"></i>
    </button>
@endsection

@section('content')

    <div class="card shadow mb-4" id="admin_users">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Users') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">
                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.users.ajax') }}"
                       data-orderable="1,7,8"
                       data-success="adminUsers"
                       data-placeholder="ID, Name, Email"
                       data-data="{{ base64data([
                            'type' => $type,
                            'update_url' => route('admin_users.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Image') }}</th>
                        <th>{{ _('Full Name') }}</th>
                        <th>{{ _('Company Name') }}</th>
                        <th>{{ _('Email') }}</th>
                        <th>{{ _('Permissions') }}</th>
                        <th>{{ _('User Type') }}</th>
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
