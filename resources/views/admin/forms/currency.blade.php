@php
    if ($isNew){
        $header = _('Add Currency');
        $link = route('admin_currencies.store');
    }else{
        $header = _('Update Currency');
        $link = route('admin_currencies.update', $item->id);
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
                    <div class="col-md-2 text-right">{{ _('is active') }} :</div>
                    <div class="col-md-10">
                        <input type="hidden" name="active" value="0"/>
                        <input type="checkbox" name="active" value="1" data-on_txt="Yes" data-off_txt="No"
                               class="switch" @if ($item->active == 1) checked="checked" @endif>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('value') }}:
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="value" class="form-control" value="{{ $item->value }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('symbol') }} / {{ _('currency') }} :
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="symbol" class="form-control" value="{{ $item->symbol }}">
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="currency" class="form-control" value="{{ $item->currency }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('country') }}:
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="country" class="form-control" value="{{ $item->country }}">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">
                        {{ _('code') }}:
                    </div>
                    <div class="col-md-10">
                        <input type="text" name="code" class="form-control" value="{{ $item->code }}">
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
