@php
    if ($isNew){
        $link = route('admin_user_groups.store');
        $header = _('Add User Group');
    }else{
        $header = _('Update User Group');
        $link = route('admin_user_groups.update', $item->id);
    }
@endphp

@extends( adminTheme().'.forms.layout.layout_form' )

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form" id="user_group_form">

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

                @if (!$isNew)
                    <div class="border p-3 mb-3">
                        <input type="hidden" name="user_group" value="{{ $item->id }}">

                        <h6 class="modal-title">{{ _('New Price Rule') }}</h6>
                        <div class="form-group row">
                            <div class="col-md-2 text-right">{{ _('Shipping Id/Service') }} :</div>
                            <div class="col-md-3">
                                <select name="shipping_id" class="form-control" data-url="{{ route('api.get_shipping_services') }}">
                                    <option value="">{{ _('Select') }}</option>
                                    @foreach($shippingList as $shipping)
                                        <option value="{{ $shipping->id }}">{{ $shipping->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="shipping_service_id" class="form-control"></select>
                            </div>
                            <div class="col-md-2">
                                {{ _('Is Default') }} :
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" name="is_default" value="0"/>
                                <input type="checkbox" name="is_default" value="1" data-on_txt="Yes" data-off_txt="No"
                                       class="switch" @if ($item->is_default == 1) checked="checked" @endif>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-2 text-right">{{ _('Desi') }} :</div>
                            <div class="col-6 col-md-5">
                                <input type="text" name="min" class="form-control" value=""
                                       placeholder="{{ _('Min') }}">
                            </div>
                            <div class="col-6 col-md-5">
                                <input type="text" name="max" class="form-control" value=""
                                       placeholder="{{ _('Max') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 col-md-2 text-right">{{ _('Price / Discount') }} :</div>
                            <div class="col-6 col-md-5">

                                <div class="input-group mb-3">
                                    <input type="text" name="price" class="form-control" value=""
                                           placeholder="{{ _('Price') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>

                            </div>
                            <div class="col-6 col-md-5">

                                <div class="input-group mb-3">
                                    <input type="text" name="discount" class="form-control" value=""
                                           placeholder="{{ _('Discount') }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2 text-right"></div>
                            <div class="col-md-10">
                                <button class="btn btn-secondary btn-block" type="button" id="add_user_group_price"
                                        data-url="{{ ifExistRoute('admin_user_group_prices.store')  }}">
                                    {{ _('Add') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="border p-3">
                        <h6 class="modal-title">{{ _('Price Rules List') }}</h6>

                        <div id="price_rules_list" class="overflow-auto">

                            <table class="table datatable-generated table-hover no-footer price-data-table"
                                   data-url="{{ ifExistRoute('api_admin_user_group_prices_ajax') }}"
                                   data-orderable="1"
                                   data-placeholder="ID"
                                   data-success="adminShippingPrice"
                                   data-data="{{ base64data([
                                        'id' => $item->id,
                                   ]) }}">
                                <thead>
                                <tr>
                                    <th>{{ _('ID') }}</th>
                                    <th>{{ _('Shipping_id') }}</th>
                                    <th>{{ _('is_default') }}</th>
                                    <th>{{ _('Min') }}</th>
                                    <th>{{ _('Max') }}</th>
                                    <th>{{ _('Price') }}</th>
                                    <th>{{ _('Discount') }}</th>
                                    <th>{{ _('Delete') }}</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                        </div>
                    </div>
            </div>

        </div>

        @endif

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
