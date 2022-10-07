@extends( adminTheme().'.layout.admin')

@section('top')

    <button class="btn btn-sm btn-primary open-modal" data-url="{{ route('admin_gtip.create') }}" type="button">
        <i class="fas fa-plus"></i>
    </button>

@endsection

@section('content')

    <div class="card shadow mb-4"  id="admin_gtip">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Gtip') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">
                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.gtip.ajax') }}"
                       data-orderable="4,5"
                       data-success="adminGtip"
                       data-placeholder="ID, Name, Gtip code"
                       data-data="{{ base64data([
                            'update_url' => route('admin_gtip.show', '###')
                        ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Gtip') }}</th>
                        <th>{{ _('Description') }}</th>
                        <th>{{ _('Unit') }}</th>
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
