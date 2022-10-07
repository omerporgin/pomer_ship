@php
    if ($isNew){
        $header = _('Add');
         $link = route('vendor_entegration_data.store');
    }else{
        $header = _('Update');
         $link = route('vendor_entegration_data.update', [$item?->id ]);
    }
@endphp

@extends( vendorTheme().'.forms.layout.layout_form' )

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form">
        <div class="modal-body">
            <div class="container-fluid">

                @if (!$isNew)
                    <input type="hidden" name="_method" value="put"/>
                @endif

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Entegration Name') }} :</div>
                    <div class="col-md-10">
                        {{ $entegration->name }}
                        <input type="hidden" name="entegration_id" value="{{ $entegration->id}}"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Url') }} :</div>
                    <div class="col-md-10">

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="url"><i class="fas fa-paperclip"></i></span>
                            </div>
                            <input type="text" name="url" value="{{ $item?->url}}" class="form-control"
                                   aria-label="url" aria-describedby="url"/>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('User / Pass') }} :</div>
                    <div class="col-md-5">
                        <input type="text" name="user" value="{{ $item?->user }}" class="form-control"/>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="pass" value="{{ $item?->pass }}" class="form-control"/>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Start / Days') }} :</div>
                    <div class="col-md-5">

                        <div class="input-group date" id="datetimepicker3" data-target-input="nearest">
                            <input type="text" name="last_date" id="start"
                                   value="@if(is_null($item?->last_date)){{ date('Y-m-d H:i:s')  }}@else{{ $item?->last_date }}@endif"
                                   class="form-control hasDateTimePicker"/>
                            <div class="input-group-append" data-target="#start" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-5">
                        <input type="text" name="days" value="{{ $item?->days }}" class="form-control"/>
                    </div>
                </div>
                <div class="row justify-content-end mb-3">
                    <div class="col-md-10">
                        <small>{{ _('Start date will be updated automatically with last update date on last update.') }}</small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Statuses') }} :</div>
                    <div class="col-md-4">
                        <input type="text" name="statuses" value="{{ $item?->statuses }}" class="form-control"/>
                        <small>{{ _('Saparate values wth comma.') }}</small>
                    </div>
                    <div class="col-md-2 text-right">{{ _('Max Orders') }} :</div>
                    <div class="col-md-4">
                        <input type="text" name="max" value="{{ $item?->max }}" class="form-control"/>
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
                @if ($isNew)
                    {{ _('add') }}
                @else
                    {{ _('update') }}
                @endif
            </button>
        </div>
    </form>

@endsection
