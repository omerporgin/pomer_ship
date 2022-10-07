@php
    if ($isNew){
        $link = route('admin_permissions.store');
        $header = _('Add Permission');
    }else{
        $header = _('Update Permission');
        $link = route('admin_permissions.update', $item->id);
    }
@endphp

@extends( adminTheme().'.forms.layout.layout_form' )

@section('content')
    <form action="{{ $link }}" method="post" class="ajax_form">
        <div class="modal-body">
            <div class="container-fluid">

                @if (!$isNew)
                    <input type="hidden" name="_method" value="put"/>
                @endif

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Name') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                    </div>
                </div>

                <div class="form-group row">
                    @foreach($permissionList as $key=>$permissionItem)

                        <div class="col-2 text-right mt-2">{{ _($key) }} :</div>
                        <div class="col-4 mt-2">
                            <input type="hidden" name="{{ $key }}" value="0"/>
                            <input type="checkbox" name="{{ $key }}" value="1" data-on_txt="Yes" data-off_txt="No"
                                   class="switch"
                                   @if(isset($permission->{$key}) and $permission->{$key}==1) checked="checked" @endif>

                        </div>

                    @endforeach

                </div>

            </div>

            @csrf

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary " data-bs-dismiss="modal">{{ _('Close') }}</button>
            <button type="button"
                    class="@if(!$updatable) disabled @else main_button @endif btn btn-secondary text-uppercase">
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
