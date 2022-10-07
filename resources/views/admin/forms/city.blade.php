@php
    if ($isNew){
        $header = _('Add City');
        $link = route('admin_location_city.store');
    }else{
        $header = _('Update City');
        $link = route('admin_location_city.update', $item->id);
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
                        {{ _('state id') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="state_id" class="form-control" value="{{ $item->state_id }}">
                    </div>
                    <div class="col-md-2 text-right">
                        {{ _('state_code') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="state_code" class="form-control" value="{{ $item->state_code }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('county id') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="country_id" class="form-control" value="{{ $item->country_id }}">
                    </div>
                    <div class="col-md-2 text-right">
                        {{ _('country_code') }}:
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="country_code" class="form-control" value="{{ $item->country_code }}">
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
