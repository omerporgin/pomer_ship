@extends('admin.layout.admin')

@section('content')

    <div class="card shadow mb-4"  id="admin_order_status">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Order Statusses') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('order_status.ajax') }}"
                       data-orderable="2,3,4"
                       data-success="orderStatus"
                       data-placeholder="ID, Name"
                       data-paging="false">
                    <thead>
                    <tr>
                        <th>{{ _('Id.') }}</th>
                        <th>{{ _('Name.') }}</th>
                        <th>{{ _('From.') }}</th>
                        <th>{{ _('To.') }}</th>
                        <th>{{ _('Color.') }}</th>
                        <th>{{ _('Who.') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

@endsection
