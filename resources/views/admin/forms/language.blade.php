@php
    if ($isNew){
        $header = _('Add Language');
        $link = route('admin_languages.store');
    }else{
        $header = _('Update Language');
        $link = route('admin_languages.update', $item->id);
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
                    <div class="col-md-2 text-right">{{ _('currency_id') }} :</div>
                    <div class="col-md-10">
                        <select name="currency_id" class="form-control select2_minimal" style="width:100%">
                            @foreach($currencies['list'] as $currency)
                                <option value="{{ $currency->id }}"
                                        @if($currency->id==$item->currency_id) selected @endif>
                                    {{ $currency->currency }}
                                </option>
                            @endforeach
                        </select>
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
