@php
    $header = _('Download Order');
@endphp

@extends( vendorTheme().'.forms.layout.layout_form' )

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form" id="form_order">
        <div class="modal-body">
            <div class="container-fluid">

                @if (!$isNew)
                    <input type="hidden" name="_method" value="put"/>
                @endif

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Start Data') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="" value="" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('End Data') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="" value="" class="form-control"/>
                    </div>
                </div>


            </div>
        </div>

        @csrf

        <div class="modal-footer">
            <button type="button" class="btn btn-primary " data-bs-dismiss="modal">{{ _('Close') }}</button>
            <button type="button"
                    class="@if(!$updatable) disabled @else main_button @endif  btn btn-secondary text-uppercase">
                <i class="fa fa-plus" aria-hidden="true"></i>
                {{ _('Download') }}
            </button>
        </div>
    </form>

@endsection
