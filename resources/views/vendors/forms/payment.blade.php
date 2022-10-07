@php
    $header = _('New Payment');
@endphp

@extends( vendorTheme().'.forms.layout.layout_form' )

@section('content')

    <form action="{{ route('vendor_paytr_form') }}" method="post" id="form_payment">

        <input type="hidden" name="payment_id" value="{{ $payment_id }}" class="form-control"/>
        <input type="hidden" name="card_type" value="" class="form-control"/>

        <div class="modal-body">
            <div class="container-fluid" style="overflow: auto">

                <div class="form-group row">
                    <div class="col-md-4 text-right">{{ _('Name On Card') }} :</div>
                    <div class="col-md-8">
                        <input type="text" name="card_name" value="" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4 text-right">{{ _('Card Number') }} :</div>
                    <div class="col-md-8">
                        <input type="text" name="card_number" value="" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-4 text-right">{{ _('Total') }} :</div>
                    <div class="col-md-8">

                        <div class="input-group  mb-3">
                            <input type="text" name="total_payment" value="" class="form-control"/>
                            <div class="input-group-append">
                                <span class="input-group-text">TL</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-4 text-right">{{ _('Expiry date') }} :</div>
                    <div class="col-4">
                        <select name="expiry_month" class="form-control d-inline" style="width:auto">
                            <option value="01">1</option>
                            <option value="02">2</option>
                            <option value="03">3</option>
                            <option value="04">4</option>
                            <option value="05">5</option>
                            <option value="06">6</option>
                            <option value="07">7</option>
                            <option value="08">8</option>
                            <option value="09">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select> /
                        <select name="expiry_year" class="form-control d-inline" style="width:auto">
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                        </select>
                    </div>
                    <div class="col-2 text-right">{{ _('CVV') }} :</div>
                    <div class="col-2">
                        <input type="text" name="card_cvv" value="" class="form-control"/>
                    </div>
                </div>

                <div id="installment_container"></div>
                <div id="payment_form_container" class="d-none"></div>

            </div>
        </div>
        @csrf

        <div class="modal-footer">

            <button type="button" class="btn btn-primary " data-bs-dismiss="modal">{{ _('Close') }}</button>

            <button type="button"
                    class="btn-icon-split btn btn-success text-uppercase disable_on_start" disabled="disabled">
                <span class="icon text-white-50">
                    <i class="far fa-credit-card"></i>
                </span>
                <span class="text">
                    {{ _('Pay') }}
                </span>
            </button>

        </div>
    </form>

@endsection
