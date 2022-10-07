@php
    if ($isNew){
        $header = _('Add Item');
        $link = route('admin_localization.store');
    }else{
        $header = _('Update Item');
        $link = route('admin_localization.update', 999 );
    }
@endphp

@extends( adminTheme().'.forms.layout.layout_form')

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form">
        <div class="modal-body">
            <div class="container-fluid">

                @if ($isNew)
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ _('Variable') }} :</div>
                        <div class="col-md-10">
                            <input type="text" name="variable" value="" class="form-control">
                        </div>
                    </div>
                @else
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ _('Variable') }} :</div>
                        <div class="col-md-10">
                            <div class="alert alert-info " role="alert">
                                {{ $item->variable }}
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="_method" value="put"/>
                    <input type="hidden" name="variable" value="{{ $item->variable }}" class="form-control">
                @endif

                @foreach($langData as $currentItem)
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ langCode($currentItem->langId) }} {{ _('value') }} :</div>
                        <div class="col-md-10">
                            <input type="text"
                                   name="value[{{ $currentItem->localizationID }}][{{ $currentItem->langId }}]"
                                   value="{{ $currentItem->value }}" class="form-control">
                        </div>
                    </div>
                @endforeach
            </div>

            @csrf

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary " data-bs-dismiss="modal">{{ _('Close') }}</button>
            <button type="button"
                    class="@if(!$updatable) disabled @else main_button @endif  btn btn-secondary text-uppercase">
                <i class="fa fa-plus" aria-hidden="true"></i>
                {{ _('update') }}
            </button>
        </div>
    </form>

@endsection
