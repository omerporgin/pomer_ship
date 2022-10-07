<div class="form-group row">

    <div class="col-12 col-lg-2 text-right partial_search">{{ _('Country') }} :</div>
    <div class="col-12 col-lg-10 partial_search">
        <select name="country_id" class="select2 w-100"
                data-placeholder="{{ _('Select country') }}"
                data-url="{{ route('selectSingleCountry') }}"
        ></select>
    </div>

</div>
