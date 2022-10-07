<input type="hidden" name="city_id" value="{{ $item->city_id }}">
<input type="hidden" name="state_id" value="{{ $item->state_id }}">
<input type="hidden" name="country_id" value="{{ $item->country_id }}">

<div class="form-group row">
    <div class="col-md-2 text-right full_search @if(isset($location)) d-none @endif">
        {{ _('Select District') }} :
    </div>
    <div class="col-md-10 full_search @if(isset($location)) d-none @endif">
        <select name="fake_city_id" class="select2"
                data-placeholder="{{ _('Select city/district') }}"
                data-url="{{ route('selectCity') }}"
        ></select>
    </div>

    <div class="col-md-2 text-right partial_search">{{ _('Country > District') }} :</div>
    <div class="col-md-3 partial_search">
        <select name="fake_country_id" class="select2"
                data-placeholder="{{ _('Select country') }}"
                data-url="{{ route('selectSingleCountry') }}"
        ></select>
    </div>

    <div class="col-md-3 partial_search">
        <select name="fake_state_id" class="select2"
                data-placeholder="{{ _('Select city') }}"
                data-url="{{ route('selectSingleState') }}"
        ></select>
    </div>
    <div class="col-md-4 partial_search">
        <select name="fake_city_id" class="select2"
                data-placeholder="{{ _('Select district') }}"
                data-url="{{ route('selectSingleCity') }}"
        ></select>
    </div>

    <div class="col-12 text-right">
        <small class="text-danger @if(isset($location)) d-none @endif toggle_full_search"
               data-on_toogled_text="{{ _('Full search') }}" role="button">
            {{ _('Ülke > Şehir > Semt') }}
        </small>
    </div>
</div>
