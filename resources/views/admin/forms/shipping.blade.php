@php
    if ($isNew){
        $header = _('Add Shipping');
        $link = route('admin_shippings.store');
    }else{
        $header = _('Update Shipping');
        $link = route('admin_shippings.update', $item->id);
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
                        <input type="hidden" name="is_active" value="0"/>
                        <input type="checkbox" name="is_active" value="1" data-on_txt="Yes" data-off_txt="No"
                               class="switch" @if ($item->is_active == 1) checked="checked" @endif>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('is test') }} :</div>
                    <div class="col-md-10">
                        <input type="hidden" name="is_test" value="0"/>
                        <input type="checkbox" name="is_test" value="1" data-on_txt="Yes" data-off_txt="No"
                               class="switch" @if ($item->is_test == 1) checked="checked" @endif>
                    </div>
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">
                            {{ _('Live') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#profile" type="button"
                                role="tab" aria-controls="profile" aria-selected="false">
                            {{ _('Test') }}
                        </button>
                    </li>
                </ul>
                <div class="tab-content mb-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="form-group row">
                            <div class="col-12 col-sm-4 col-md-2 text-right">{{ _('User') }} :</div>
                            <div class="col-12 col-sm-8  col-md-4">
                                <input type="text" name="user" class="form-control" value="{{  $item->getOriginal('user') }}">
                            </div>
                            <div class="col-12 col-sm-4  col-md-2 text-right">{{ _('Account Number') }} :</div>
                            <div class="col-12 col-sm-8 col-md-4">
                                <input type="text" name="account_number" class="form-control"
                                       value="{{ $item->getOriginal('account_number') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-sm-4 col-md-2 text-right">{{ _('Api Key') }} :</div>
                            <div class="col-12 col-sm-8  col-md-4">
                                <input type="text" name="api_key" class="form-control" value="{{ $item->getOriginal('api_key')  }}">
                            </div>
                            <div class="col-12 col-sm-4  col-md-2 text-right">{{ _('Api Secret') }} :</div>
                            <div class="col-12 col-sm-8 col-md-4">
                                <input type="text" name="api_secret" class="form-control"
                                       value="{{ $item->getOriginal('api_secret')  }}">
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="form-group row">
                            <div class="col-12 col-sm-4 col-md-2 text-right">{{ _('User') }} :</div>
                            <div class="col-12 col-sm-8  col-md-4">
                                <input type="text" name="test_user" class="form-control" value="{{ $item->test_user }}">
                            </div>
                            <div class="col-12 col-sm-4  col-md-2 text-right">{{ _('Account Number') }} :</div>
                            <div class="col-12 col-sm-8 col-md-4">
                                <input type="text" name="test_account_number" class="form-control"
                                       value="{{ $item->test_account_number }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12 col-sm-4 col-md-2 text-right">{{ _('Api Key') }} :</div>
                            <div class="col-12 col-sm-8  col-md-4">
                                <input type="text" name="test_api_key" class="form-control"
                                       value="{{ $item->test_api_key }}">
                            </div>
                            <div class="col-12 col-sm-4  col-md-2 text-right">{{ _('Api Secret') }} :</div>
                            <div class="col-12 col-sm-8 col-md-4">
                                <input type="text" name="test_api_secret" class="form-control"
                                       value="{{ $item->test_api_secret }}">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Image') }} :</div>
                    <div class="col-md-10">

                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="validatedCustomFile">
                            <label class="custom-file-label" for="validatedCustomFile">{{ _('Choose file...') }}</label>
                            <small>JPEG PNG GIF TIF BMP ICO PSD WebP</small>
                        </div>
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
