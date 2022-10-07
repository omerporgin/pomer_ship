@php
    $link = ifExistRoute('admin_update_shipping_prices');
@endphp

@extends( adminTheme('forms.layout.layout_form'))

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form">
        <div class="modal-body">
            <div class="container-fluid">
                <ol>
                    <li>Fiyatlar UPS servisi ile güncellenir.</li>
                    <li>Aynı isteği karşılayan eski fiyatlar varsa silinir.</li>
                    <li>İşlem 5 dk civarı sürer.</li>
                </ol>
                <input type="hidden" name="shippingId" value="{{ $id }}">

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Max Desi') }} :</div>
                    <div class="col-md-10">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-dolly-flatbed"></i>
                                </span>
                            </div>
                            <input name="maxDesi" class="form-control" value="70"/>
                        </div>

                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2 text-right">{{ _('Region') }} :</div>
                    <div class="col-md-10">

                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-globe-europe"></i>
                                </span>
                            </div>
                            <select name="region" class="form-control"/>
                            @php($i = 1)
                            @while($i < 20)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @php($i++)
                                @endwhile
                                </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="main_button btn btn-primary">
                        {{ _('Refresh Prices') }}
                    </button>
                </div>
            </div>
        </div>

        @csrf

    </form>

@endsection
