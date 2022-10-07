@extends( vendorTheme().'.layout.admin' , [
    'header' => 'New Order',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')
    @foreach($payments as $payment)

            <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm btn-icon-split open-modal @if(!$payment->active) disabled @endif"
               data-url="{{ ifExistRoute('vendor_payment_account.create') }}"
               data-data="{{ json_encode(['payment_id' => $payment->id ]) }}">
                <span class="icon text-white-50">
                     <i class="far fa-credit-card"></i>
                </span>
                <span class="text">{{ $payment->name }}</span>
            </a>

    @endforeach
@endsection

@section('content')

    <div class="card shadow mb-4" id="vendor_payment">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-6">
                    <h6 class="m-0 font-weight-bold text-primary">{{ _('Payment') }}</h6>
                </div>
                <div class="col-6 text-right">
                    Bakiyeniz : <h6 class="text-danger"><b>{{ $total }}</b>TL</h6>
                </div>
            </div>
        </div>

        <div class="card-body">

            <div class="table_container">

                <table class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('vendor.payment_account.ajax') }}"
                       data-orderable=""
                       data-success="paymentAccount"
                       data-placeholder="ID">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Payment name') }}</th>
                        <th>{{ _('installment') }}</th>
                        <th>{{ _('status') }}</th>
                        <th>{{ _('total') }}</th>
                        <th>{{ _('date') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
