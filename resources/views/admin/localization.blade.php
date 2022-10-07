@extends( 'admin.layout.admin')

@section('top')
    <button type="button" id="scanLanguageVariablesBtn"
            data-on_scan="<span class='pr-3'><span class='spinner-grow spinner-grow-sm' role='status' aria-hidden='true'></span></span>{{ _('Scanning') }}"
            data-after_scan="{{ _('Scanned') }}" class="btn btn-primary btn-sm">
        {{ _('Scan new variables') }}
    </button>

    <button class="btn btn-sm btn-primary open-modal"  data-id="" data-url="{{ route('admin_localization.create') }}" type="button">
        <i class="fas fa-plus"></i>
    </button>
@endsection

@section('content')

    <div class="card shadow mb-4"  id="admin_localization">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Localization') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated dataTable no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.localization.ajax') }}"
                       data-orderable="3,4"
                       data-success="adminLocalization"
                       data-placeholder="ID, Variable, value"
                       data-data="{{ base64data([
                            'update_url' => route('admin_localization.show', '###')
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Variable') }}</th>
                        <th>{{ _('Value') }}</th>
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
