<div class="form-group row">
    <div class="col-2 text-right mt-2">{{ _('Has Pickup') }} :</div>
    <div class="col-4 mt-2">
        <input type="hidden" name="has_pickup" value="0"/>
        <input type="checkbox" name="has_pickup" value="1" data-on_txt="Yes" data-off_txt="No"
               class="switch" @if($item->has_pickup==1) checked="checked" @endif>
    </div>

    <div class="col-2 text-right mt-2">{{ _('Diffrent Pickup Address') }} :</div>
    <div class="col-4 mt-2">
        <input type="hidden" name="has_diffrent_pickup_address" value="0"/>
        <input type="checkbox" name="has_diffrent_pickup_address" value="1" data-on_txt="Yes" data-off_txt="No"
               class="switch" @if($item->has_diffrent_pickup_address==1) checked="checked" @endif>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Planned Shipping Date') }} :</div>
    <div class="col-md-10">

        <input type="hidden" name="shipped_at" value="{{ $item->shipped_at }}"/>

        <div class="row text-center p-3">
        @foreach($dateList as $date)
            @if($date->isWeekend())
                <div
                    class="col p-1 bg-dark rounded border text-light "
                    data-toggle="tooltip"
                    title="Haftasonu pickup servisi çalışmaz.">
                          {{ $date->format('d') }}
                    <small  class="d-none d-lg-inline">{{ $date->translatedFormat('D') }}</small>
                </div>
            @else
                <div
                    class="js_choose_date col p-1 rounded border @if( $item->shipped_at ==$date->translatedFormat('Y-m-d')) bg-danger text-light @else bg-light @endif"
                    data-toggle="tooltip"
                    data-date="{{ $date->translatedFormat('Y-m-d') }}"
                    title="{{ $date->translatedFormat('d F Y') }}"
                    role="button">
                        {{ $date->format('d') }}
                    <small class="d-none d-lg-inline">{{ $date->translatedFormat('D') }}</small>
                </div>
            @endif
        @endforeach
        </div>
    </div>

</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Close Time') }} :</div>
    <div class="col-md-4">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="pickup_closed_at_desc">
                    <i class="far fa-clock"></i>
                </span>
            </div>
            <div class="custom-file">
                <input type="text" class="form-control" value="{{ $item->pickup_closed_at }}" name="pickup_closed_at"
                       placeholder="18:00">
            </div>
        </div>

    </div>
    <div class="col-md-2 text-right">{{ _('Location') }} :</div>
    <div class="col-md-4">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="invoice_en_desc">
                    <i class="fas fa-map-pin"></i>
                </span>
            </div>
            <div class="custom-file">
                <input type="text" class="form-control" value="{{ $item->pickup_location }}" name="pickup_location">
            </div>
        </div>

    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Şehir/Semt') }} :</div>
    <div class="col-6 col-md-5">
        <select name="pickup_state_id" class="form-control select_city"
                data-target=".warehouse_city"
                data-url="{{ ifExistRoute('selectSingleCity') }}">
            <option value="">{{ _('Select') }}</option>
            @foreach($stateList as $state)
                <option value="{{ $state->state_id }}" @if($item->pickup_state_id == $state->state_id) selected @endif>
                    {{ $state->state_name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-5">
        <select name="pickup_city_id" class="form-control warehouse_city" data-old_value="{{ $item->pickup_city_id }}">
        </select>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Pickup Address') }} :</div>
    <div class="col-md-10">

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-map-marked"></i></span>
            </div>
            <textarea name="pickup_address" class="form-control">{{ $item->pickup_address }}</textarea>
        </div>

    </div>
</div>

<div class="form-group row">
    <div class="col-md-2 text-right">{{ _('Post Code') }} :</div>
    <div class="col-md-4">
        <input type="text" name="pickup_post_code" value="{{ $item->pickup_post_code }}"
               class="form-control"/>

    </div>
</div>
