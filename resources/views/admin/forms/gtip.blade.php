@php
    if ($isNew){
        $header = _('New Gtip');
        $link = route('admin_gtip.store');
    }else{
        $header = _('Update Gtip');
        $link = route('admin_gtip.update', $item->id);
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
                    <div class="col-md-2 text-right">{{ _('Selectable') }} :</div>
                    <div class="col-md-10">
                        <input type="hidden" name="is_selectable" value="0"/>
                        <input type="checkbox" name="is_selectable" value="1" data-on_txt="Yes" data-off_txt="No"
                               class="switch"
                               @if($item->is_selectable==1) checked="checked" @endif>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Gtip') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="gtip" value="{{ $item->gtip }}" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Description') }} :</div>
                    <div class="col-md-10">
                        <textarea name="description" class="form-control">{{ $item->description }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Unit') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="unit" value="{{ $item->unit }}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Tax') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="tax" value="{{ $item->tax }}" class="form-control">
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
                {{ _('update') }}
            </button>
        </div>
    </form>

@endsection
