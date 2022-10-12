<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('OrderID') }} :</div>
    <div class="col-md-4">
        <input type="hidden" name="order_id" value="{{ $item->order_id }}"
               class="form-control"/>
        @if( is_null($item->order_id))
            <small>ShipExporgin'de oluşturulan siparişlerde order_id bulunmaz.</small>
        @endif
        {{ $item->order_id }}
    </div>
    <div class="col-md-2 text-right">{{ _('Currency') }} :</div>
    <div class="col-md-4">

        <select class="form-control" name="currency">
            <option value="" data-sign="?">
                {{ _('Select') }}
            </option>
            @foreach($currencies as $currency)
                <option value="{{ $currency->id }}" data-sign="{{ $currency->symbol }}" @if( $item->currency==$currency->id) selected @endif>
                    {{ $currency->currency }}  {{ $currency->symbol }}
                </option>
            @endforeach
        </select>

    </div>
    {{--
    <div class="col-md-2 text-right">{{ _('Status') }} :</div>
    <div class="col-md-4">

        <select class="form-control" name="status">
            @foreach($order_status as $status)
                <option value="{{ $status->id }}" style="color:#fff;background: #{{ $status->color }}">
                    {{ $status->name }}
                </option>
            @endforeach
        </select>

    </div>
    --}}
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Full Name') }} :</div>
    <div class="col-md-4">
        <input type="text" name="full_name" value="{{ $item->full_name }}" class="form-control"/>
    </div>
    <div class="col-md-2 text-right">{{ _('Company Name') }} :</div>
    <div class="col-md-4">
        <input type="text" name="company_name" value="{{ $item->company_name }}" class="form-control"/>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Phone') }} :</div>
    <div class="col-md-4">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
            </div>
            <input type="text" name="phone" value="{{ $item->phone }}" class="form-control"/>
        </div>

    </div>

    <div class="col-md-2 text-right">{{ _('Email') }} :</div>
    <div class="col-md-4">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-at"></i></span>
            </div>
            <input type="text" name="email" value="{{ $item->email }}" class="form-control"/>
        </div>

    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Address') }} :</div>
    <div class="col-md-10">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marked"></i></span>
            </div>
            <textarea name="address" class="form-control">{{ $item->address }}</textarea>
        </div>

    </div>
</div>

@include('components.select-location', [
    'location' => $location,
    'item' => $item
])

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Post Code') }} :</div>
    <div class="col-md-4">
        <input type="text" name="post_code" value="{{ $item->post_code }}" class="form-control"/>
    </div>

    <div class="col-md-2 text-right">{{ _('Shipping') }} :</div>
    <div class="col-md-4">
        <select class="form-control form-control" name="shipment_id">
            <option value="">{{ _('Select') }}</option>
            @foreach($serviceNames as $name)
                <option value="{{ $name}}"
                        @if($orderSelectedShipmentId ==$name ) selected @endif>
                    {{ $name  }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Invoice Date') }} :</div>
    <div class="col-md-4" data-toggle="tooltip" title="Faturanın siparişin verildiği gün kesildiği kabul edilir.">

        <div class="input-group date" id="datetimepicker_container" data-target-input="nearest">
            <input type="text" name="order_date" id="order_date_picker"
                   value="{{ $item->order_date }}" class="form-control hasDatePicker"/>
            <div class="input-group-append" data-target="#order_date_picker" data-toggle="datetimepicker">
                <div class="input-group-text">
                    <i class="far fa-calendar-alt"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-2 text-right">{{ _('Invoice No') }} :</div>
    <div class="col-md-4" data-toggle="tooltip">

        <div class="input-group date" id="datetimepicker_container" data-target-input="nearest">
            <input type="text" name="invoice_no" value="{{ $item->invoice_no }}" class="form-control"/>
        </div>

    </div>
</div>



