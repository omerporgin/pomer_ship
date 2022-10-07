@php
    if ($isNew){
        $header = _('Add Blog');
        $link = ifExistRoute('admin_blogs.store');
    }else{
        $header = _('Update Blog');
        $link = ifExistRoute('admin_blogs.update',  $item->id );
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
                    <div class="col-md-2 text-right">{{ _('Is active') }} :</div>
                    <div class="col-md-10">
                        <input type="hidden" name="active" value="0"/>
                        <input type="checkbox" name="active" value="1" data-on_txt="Yes" data-off_txt="No"
                               class="switch" @if ($item->active == 1) checked="checked" @endif>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Language') }} :</div>
                    <div class="col-md-10">

                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fas fa-globe-europe"></i>
                            </span>
                            </div>
                            <select name="lang" class="form-control">
                                @foreach ($langsAll as $key => $lang)
                                    <option value="{{ $lang->id }}"
                                            @if($lang->id == $item->lang) selected @endif>{{ $lang->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Headline') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="headline" value="{{ $item->headline }}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">Url :</div>
                    <div class="col-md-10">

                        <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fas fa-link"></i>
                            </span>
                            </div>
                            <input name="url" value="{{ $item->url }}" placeholder="url" class="form-control"/>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Lead') }} :</div>
                    <div class="col-md-10">
                        <textarea type="text" name="lead" class="form-control">{{ $item->lead }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Body') }} :</div>
                    <div class="col-md-10">
                        <textarea type="text" name="body" class="form-control rich_text">{{ $item->body }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Title') }} :</div>
                    <div class="col-md-10">
                        <input type="text" name="title" value="{{ $item->title }}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Description') }} :</div>
                    <div class="col-md-10">
                        <textarea type="text" name="description"
                                  class="form-control">{{ $item->description }}</textarea>
                    </div>
                </div>

                @if(!$isNew)
                    <div class="form-group row">
                        <div class="col-md-2 text-right">{{ _('Images') }} :</div>
                        <div class="col-md-10">
                            <div class="row">
                                @foreach($item->imgs() as $img)
                                    <div class="col-3 mb-2">
                                        <img src="{{ asset($img) }}" class="img-fluid">
                                        <button type="button" class="btn btn-danger btn-sm btn-block delete_blog_image" data-file="{{ basename($img) }}" data-url="{{ route('admin.blogs.delete_image') }}">
                                            {{ _('Delete') }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Image') }} :</div>
                    <div class="col-md-10">

                        <div class="custom-file">
                            <input type="file" name="image" class="custom-file-input" id="validatedCustomFile">
                            <label class="custom-file-label" for="validatedCustomFile">{{ _('Choose file...') }}</label>
                            <small>JPEG	PNG	GIF	TIF	BMP	ICO	PSD	WebP</small>
                        </div>
                    </div>
                </div>

            </div>
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

        @csrf

    </form>

@endsection
