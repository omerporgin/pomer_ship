@php
    if ($isNew){
        $header = _('Add Country');
        $link = route('admin_location_country.store');
    }else{
        $header = _('Update Country');
        $link = route('admin_location_country.update', $item->id);
    }
@endphp

@extends( adminTheme().'.forms.layout.layout_form')

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form">
        <div class="modal-body">
            <div class="container-fluid">

                @if (!$isNew)
                    <input type="hidden" name="_method" value="put"/>
                @endif

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('is approved') }} :</div>
                    <div class="col-md-10">
                        <input type="hidden" name="is_accepted" value="0"/>
                        <input type="checkbox" name="is_accepted" value="1" data-on_txt="Yes" data-off_txt="No"
                               class="switch" @if ($item->is_accepted == 1) checked="checked" @endif>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('name') }}:
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('iso2') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="iso2" class="form-control" value="{{ $item->iso2 }}">
                    </div>
                    <div class="col-md-2 text-right">
                        {{ _('iso3') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="iso3" class="form-control" value="{{ $item->iso3 }}">
                    </div>
                </div>

                    <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('Numeric Code') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="numeric_code" class="form-control" value="{{ $item->numeric_code }}">
                    </div>
                    <div class="col-md-2 text-right">
                        {{ _('Phone Code') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="phonecode" class="form-control" value="{{ $item->phonecode }}">
                    </div>
                </div>


                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('Currency') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="currency" class="form-control" value="{{ $item->currency }}">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('Currency Name') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="currency_name" class="form-control" value="{{ $item->currency_name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('Currency Symbol') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="currency_symbol" class="form-control" value="{{ $item->currency_symbol }}">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('tld') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="tld" class="form-control" value="{{ $item->tld }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('Capital') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="capital" class="form-control" value="{{ $item->capital }}">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('Native') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="native" class="form-control" value="{{ $item->native }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('region') }}:
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="region">
                                @foreach($regionList as $region)
                                    <option id="{{ $region->region }}" @if($region->region==$item->region) selected="selected" @endif>
                                        {{ $region->region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('Sub Region') }}:
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" name="subregion">
                                @foreach($subRegionList as $subRegion)

                                    <option id="{{ $subRegion->subregion }}" @if($subRegion->subregion==$item->subregion) selected="selected" @endif>
                                        {{ $subRegion->subregion }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('latitude') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="latitude" class="form-control" value="{{ $item->latitude }}">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('longitude') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="longitude" class="form-control" value="{{ $item->longitude }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('emoji') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="emoji" class="form-control" value="{{ $item->emoji }}">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('emojiU') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="emojiU" class="form-control" value="{{ $item->emojiU }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('flag') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="flag" class="form-control" value="{{ $item->flag }}">
                        </div>
                        <div class="col-md-2 text-right">
                            {{ _('wikiDataId') }}:
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="wikiDataId" class="form-control" value="{{ $item->wikiDataId }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('timezones') }}:
                        </div>
                        <div class="col-md-10">
                            <textarea type="text" name="timezones" class="form-control">{{ $item->timezones }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-2 text-right">
                            {{ _('translations') }}:
                        </div>
                        <div class="col-md-10">
                            <textarea type="text" name="translations" class="form-control">{{ $item->translations }}</textarea>
                        </div>
                    </div>
            </div>

            @csrf

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary " data-bs-dismiss="modal">{{ _('Close') }}</button>
            <button type="button"
                    class="@if(!$updatable) disabled @else main_button @endif  btn btn-secondary text-uppercase">
                <i class="fa fa-plus" aria-hidden="true"></i>
                @if ($isNew)
                    {{ _('add') }}
                @else
                    {{ _('update') }}
                @endif
            </button>
        </div>
    </form>

@endsection
