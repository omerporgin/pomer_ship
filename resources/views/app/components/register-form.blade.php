<input type="hidden" value="@if(isset( $data->user_type )){{ $data->user_type }}@else{{ '0' }}@endif" name="user_type">

@csrf

@if($is_register)
    <ul class="nav nav-tabs" id="register_tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button data-id="0" class="nav-link" id="home-tab" data-bs-toggle="tab"
                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                    aria-selected="true">
                {{ _('Personal') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button data-id="1" class="nav-link" id="profile-tab" data-bs-toggle="tab"
                    data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                    aria-selected="false">
                {{ _('Company') }}
            </button>
        </li>
    </ul>
@endif

<div class="tab-content mb-2 p-2" id="register_tab_content">
    <div
        class="tab-pane fade"
        id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="col-12 mb-2">
            <x-label for="identity" :value="_('Identity')"/>
            <input type="text" id="identity" name="identity"
                   value="@if(isset( $data->identity )){{ $data->identity }}@endif"
                   class="form-control">
        </div>
    </div>
    <div class="tab-pane fade @if(!$is_register AND  $data->user_type==1) show active @endif" id="profile"
         role="tabpanel" aria-labelledby="profile-tab">
        <div class="col-12 mb-2">
            <label for="company_name">{{ _('Company Name') }}</label>
            <input type="text" id="company_name" name="company_name"
                   value="@if(isset( $data->company_name )){{ $data->company_name }}@endif"
                   class="form-control">
        </div>
        <div class="col-12 mb-2">
            <label for="company_owner">{{ _('Company Owner') }}</label>
            <input type="text" id="company_owner" name="company_owner"
                   value="@if(isset( $data->company_owner )){{ $data->company_owner }}@endif"
                   class="form-control">
        </div>
        <div class="row">
            <div class="col-6 mb-2">
                <label for="company_tax">{{ _('Company Tax') }}</label>
                <input type="text" id="company_tax" name="company_tax"
                       value="@if(isset( $data->company_tax )){{ $data->company_tax }}@endif"
                       class="form-control">
            </div>
            <div class="col-6 mb-2">
                <label for="company_taxid"></label>
                <input type="text" id="company_taxid" name="company_taxid"
                       value="@if(isset( $data->company_taxid )){{ $data->company_taxid }}@endif" class="form-control">
            </div>
        </div>
    </div>
</div>

<div class="col-6 mb-2">
    <x-label for="name" :value="_('Name')"/>
    <input type="text" id="name" name="name" value="@if(isset( $data->name )){{ $data->name }}@endif"
           class="form-control" autofocus="autofocus">
</div>

<div class="col-6 mb-2">
    <x-label for="surname" :value="_('Surname')"/>
    <input type="text" id="surname" name="surname" value="@if(isset( $data->surname )){{ $data->surname }}@endif"
           class="form-control">
</div>

<div class="col-12 mb-2">
    <label for="bank">{{ _('Bank') }}</label>
    <input type="text" id="bank" name="bank" value="@if(isset( $data->bank )){{ $data->bank }}@endif"
           class="form-control">
</div>

<div class="col-12  mb-2">
    <x-label for="email" :value="_('Email')"/>

    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fas fa-at"></i>
            </span>
        </div>
        <input type="text" id="email" name="email" value="@if(isset( $data->email )){{ $data->email }}@endif"
               class="form-control">
    </div>

</div>

<div class="col-12 mb-2">
    <x-label for="password" :value="_('Password')"/>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fas fa-key"></i>
            </span>
        </div>
        <input type="password" id="password" name="password" value="" class="form-control"
               autocomplete="new-password"/>
    </div>

</div>

<div class="col-12 mb-2">
    <x-label for="password_confirmation" :value="_('Confirm Password')"/>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fas fa-key"></i>
            </span>
        </div>
        <input type="password" id="password_confirmation" name="password_confirmation" value=""
               class="form-control" autocomplete="new-password"/>
    </div>

</div>

<div class="container">
    <div class="row border p-3">
        <div class="col-12 mb-2">
            <label for="warehouse_address"><h4>{{ _('Warehouse Address') }}</h4></label>
            <div class="input-group ">
                <span class="input-group-text">
                    <i class="fas fa-map-marked-alt"></i>
                </span>
                <textarea id="warehouse_address" name="warehouse_address"
                          class="form-control">@if(isset( $data->warehouse_address )){{ $data->warehouse_address }}@endif</textarea>
            </div>
        </div>
        <div class="col-12 mb-2">
            <label for="warehouse_postal_code">{{ _('Warehouse Postal Code') }}</label>
            <div class="input-group  w-25">
                <span class="input-group-text">
            <i class="fas fa-mail-bulk"></i>
                </span>
                <input type="text" id="warehouse_postal_code" name="warehouse_postal_code"
                       class="form-control"
                       value="@if(isset( $data->warehouse_postal_code )){{ $data->warehouse_postal_code }}@endif">
            </div>
        </div>
        <div class="col-6 mb-2">
            <label for="warehouse_state_id">{{ _('Warehouse State') }}</label>
            <select id="warehouse_state_id" name="warehouse_state_id" class="form-control select_city"
                    data-target=".warehouse_city" data-url="{{ ifExistRoute('selectSingleCity') }}">
                <option value="">{{ _('Select') }}</option>
                @foreach($state_list as $state)
                    <option value="{{ $state->state_id }}"
                            @if( isset($data->warehouse_state_id) AND $data->warehouse_state_id == $state->state_id) selected @endif>
                        {{ $state->state_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 mb-2">
            <label for="warehouse_city_id">{{ _('Warehouse City') }}</label>
            <select id="warehouse_city_id" name="warehouse_city_id" class="form-control warehouse_city"
                    data-old_value="@if(isset( $data->warehouse_city_id )){{ $data->warehouse_city_id }}@endif"></select>
        </div>
        <div class="col-12 mb-2">
            <label for="password">{{ _('Warehouse Phone Number') }}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-phone"></i>
                    </span>
                </div>
                <input type="text" id="warehouse_phone" name="warehouse_phone"
                       value="@if(isset( $data->warehouse_phone )){{ $data->warehouse_phone }}@endif"
                       class="form-control masked-phone" autocomplete="new-phone"/>
            </div>
        </div>

        <div class="col-6 mb-2">
            <label for="password">{{ _('Warehouse closing time') }}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="far fa-clock"></i>
                    </span>
                </div>
                <input type="text" id="warehouse_closed_at" name="warehouse_closed_at"
                       value="@if(isset( $data->warehouse_closed_at )){{ $data->warehouse_closed_at }}@endif"
                       class="form-control"/>
            </div>
        </div>
        <div class="col-6 mb-2">
            <label for="password">{{ _('Warehouse Location') }}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon1">
                        <i class="fas fa-map-pin"></i>
                    </span>
                </div>
                <input type="text" id="warehouse_location" name="warehouse_location"
                       value="@if(isset( $data->warehouse_location )){{ $data->warehouse_location }}@endif"
                       class="form-control"/>
            </div>
            <small>{{ _('Where the package should be picked up by courier') }}</small>
        </div>

    </div>
</div>
<div class="col-12 mb-2">
    <input type="hidden" name="is_same_address" value="0">
    <input type="checkbox" id="is_same_address" name="is_same_address" class="form-check-input" value="1"
           @if(isset( $data->is_same_address ))
           @if($data->is_same_address=='1') checked @endif
           @else
           checked
        @endif>
    <label class="form-check-label" for="is_same_address">
        Fatura adresi depo adresi aynı.
    </label>
</div>

<div class="invoice_address container  mb-2">
    <div class="row border p-3">
        <div class="col-12">
            <label for="warehouse_address"><h4>{{ _('Invoice Address') }}</h4></label>
            <div class="input-group ">
                <span class="input-group-text">
                    <i class="fas fa-map-marked-alt"></i>
                </span>
                <textarea id="invoice_address" name="invoice_address"
                          class="form-control">@if(isset( $data->invoice_address )){{ $data->invoice_address }}@endif</textarea>
            </div>
        </div>
        <div class="col-12 mb-2">
            <label for="invoice_postal_code">{{ _('Invoice Postal Code') }}</label>
            <div class="input-group  w-25">
                <span class="input-group-text">
            <i class="fas fa-mail-bulk"></i>
                </span>
                <input type="text" id="invoice_postal_code" name="invoice_postal_code"
                       class="form-control"
                       value="@if(isset( $data->invoice_postal_code )){{ $data->invoice_postal_code }}@endif">
            </div>
        </div>
        <div class="col-6 mb-2">
            <label for="invoice_state_id">{{ _('Invoice state') }}</label>
            <select id="invoice_state_id" name="invoice_state_id" class="form-control select_city"
                    data-target=".invoice_city" data-url="{{ ifExistRoute('selectSingleCity') }}">
                <option value="">{{ _('Select') }}</option>
                @foreach($state_list as $state)
                    <option value="{{ $state->state_id }}"
                            @if(isset($data->invoice_state_id) AND $data->invoice_state_id == $state->state_id) selected @endif>
                        {{ $state->state_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 mb-2">
            <label for="invoice_city_id">{{ _('Invoice City') }}</label>
            <select id="invoice_city_id" name="invoice_city_id" class="form-control invoice_city"
                    data-old_value="@if(isset( $data->invoice_city_id )){{ $data->invoice_city_id }}@endif"></select>
        </div>
        <div class="col-12 mb-2">
            <label for="password">{{ _('Invoice Phone Number') }}</label>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                    <i class="fas fa-phone"></i>
                </span>
                </div>
                <input type="text" id="invoice_phone" name="invoice_phone"
                       value="@if(isset( $data->invoice_phone )){{ $data->invoice_phone }}@endif"
                       class="form-control masked-phone"
                       autocomplete="new-phone"/>
            </div>
        </div>
    </div>
</div>

