@php
    $permissions->ifAllowed('user','see');
    $header = _('Permissions');
    $route = [
    1 => _('Content'),
    '' => $header
    ];
@endphp

@extends('admin.layout.admin')

@section('top')

    <button class="btn btn-sm btn-primary open-modal" data-url="{{ route('admin_permissions.create') }}" type="button">
        <i class="fas fa-plus"></i>
    </button>

@endsection

@section('content')

    <div class="card shadow mb-4"  id="admin_permissions">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Permissions') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.permissions.ajax') }}"
                       data-orderable="2,3"
                       data-success="adminPermissions"
                       data-placeholder="ID, Name"
                       data-data="{{ base64data([
                            'update_url' => route('admin_permissions.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Name') }}</th>
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
