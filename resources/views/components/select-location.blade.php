<div class="form-group row">

    <div class="col-md-2 text-right partial_search">{{ _('Location') }} :</div>
    <div class="col-md-3 partial_search">
        <select name="country_id" class="select2"
                data-placeholder="{{ _('Select country') }}"
                data-url="{{ route('selectSingleCountry') }}">
            <option value="{{ $item->country_id }}" selected>{{ $item->country_name }}</option>
        </select>
    </div>

    <div class="col-md-3 partial_search">
        <select name="state_id" class="select2"
                data-placeholder="{{ _('Select city') }}"
                data-url="{{ route('selectSingleState') }}">
            <option value="{{ $item->state_id }}" selected>{{ $item->state_name }}</option>
        </select>
    </div>
    <div class="col-md-4 partial_search">
        <select name="city_id" class="select2"
                data-placeholder="{{ _('Select district') }}"
                data-url="{{ route('selectSingleCity') }}">
            <option value="{{ $item->city_id }}" selected>{{ $item->city_name }}</option>
        </select>
    </div>

    <div class="col-12 text-right">
        <small class="text-danger @if(isset($location)) d-none @endif toggle_full_search"
               data-on_toogled_text="{{ _('Full search') }}" role="button">
            {{ _('Ülke > Şehir > Semt') }}
        </small>
    </div>
</div>
