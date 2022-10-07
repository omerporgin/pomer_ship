@php
    if ($isNew){
        $link = route('admin_users.store');
        $header = _('Add User');
    }else{
        $header = _('Update User');
        $link = route('admin_users.update', $item->id);
    }
@endphp

@extends( adminTheme().'.forms.layout.layout_form' )

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form">

        @if (!$isNew)
            <input type="hidden" name="_method" value="put"/>
        @endif

        <div class="modal-body">
            <div class="container-fluid">

                @if (!$isNew)

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home"
                               aria-selected="true">
                                {{ _('User') }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="address-tab" data-toggle="tab" href="#address" role="tab"
                               aria-controls="address"
                               aria-selected="false">
                                {{ _('Location') }}
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="permissions-tab" data-toggle="tab" href="#permissions" role="tab"
                               aria-controls="permissions"
                               aria-selected="false">
                                {{ _('Permissons') }}
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            @endif

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Name') }} :</div>
                                <div class="col-md-5">
                                    <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="surname" class="form-control" value="{{ $item->surname }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('User Type') }} :</div>
                                <div class="col-md-10">
                                    <select name="user_type" class="form-control admin_user_type">
                                        <option value="0" @if($item->user_type==0) selected @endif>Bireysel</option>
                                        <option value="1" @if($item->user_type==1) selected @endif>Kurumsal</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row hide_on_1">
                                <div class="col-md-2 text-right">{{ _('Identity') }} :</div>
                                <div class="col-md-10">
                                    <input type="text" name="identity" class="form-control"
                                           value="{{ $item->identity }}">
                                </div>
                            </div>

                            <div class="form-group row hide_on_0">
                                <div class="col-md-2 text-right">{{ _('Company Owner') }} :</div>
                                <div class="col-md-10">
                                    <input type="text" name="company_owner" class="form-control"
                                           value="{{ $item->company_owner }}">
                                </div>
                            </div>

                            <div class="form-group row hide_on_0">
                                <div class="col-md-2 text-right">{{ _('Company Name') }} :</div>
                                <div class="col-md-10">
                                    <input type="text" name="company_name" class="form-control"
                                           value="{{ $item->company_name }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Bank') }} :</div>
                                <div class="col-md-10">
                                    <input type="text" name="bank" class="form-control" value="{{ $item->bank }}">
                                </div>
                            </div>

                            <div class="form-group row hide_on_0">
                                <div class="col-md-2 text-right">{{ _('Company Tax') }} :</div>
                                <div class="col-md-4">
                                    <input type="text" name="company_tax" class="form-control"
                                           value="{{ $item->company_tax }}">
                                </div>

                                <div class="col-md-2 text-right">{{ _('Company Taxid') }} :</div>
                                <div class="col-md-4">
                                    <input type="text" name="company_taxid" class="form-control"
                                           value="{{ $item->company_taxid }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Email') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-at"></i>
                                    </span>
                                        </div>
                                        <input type="text" name="email" class="form-control" value="{{ $item->email }}">
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Permissions') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                        </div>

                                        <select class="form-control" name="permission_id"
                                                @if(\Auth::user()->permission_id != 13) disabled @endif>
                                            @foreach($permissionList['list'] as $permission)
                                                <option value="{{ $permission->id }}"
                                                        @if($permission->id==$item->permission_id) selected @endif>
                                                    {{ $permission->name }}
                                                    </options>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(\Auth::user()->permission_id != 13)
                                        <span class="text-danger">Only Super Admins can change user permissions</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Price Group') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                        </div>

                                        <select class="form-control" name="user_group_id"
                                                @if(\Auth::user()->permission_id != 13) disabled @endif>
                                            <option value="">Seçiniz</option>
                                            @foreach($userGroupList['list'] as $userGroup)
                                                <option value="{{ $userGroup->id }}"
                                                        @if($userGroup->id==$item->user_group_id) selected @endif>
                                                    {{ $userGroup->name }}
                                                    </options>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if(\Auth::user()->permission_id != 13)
                                        <span class="text-danger">Only Super Admins can change user permissions</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 text-right">{{ _('Image') }} : </label>
                                <div class="col-sm-10">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="photo"
                                               lang="tr">
                                        <label class="custom-file-label" for="customFile">
                                            <i class="far fa-image"></i>
                                            {{ _('select_file') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 text-right">{{ _('Language') }} : </label>
                                <div class="col-sm-10">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">
                                    <i class="fas fa-globe-europe"></i>
                                </span>
                                        </div>
                                        <select name="lang" class="form-control">
                                            <option>{{ _('Select Language') }}</option>
                                            @foreach ($langsAll as $key => $lang)
                                                <option value="{{ $lang->id }}"
                                                        @if($lang->id == $item->lang) selected @endif>{{ $lang->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            @if (!$isNew)

                        </div>

                        <div class="tab-pane fade" id="address" role="tabpanel" aria-labelledby="address-tab">
                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Warehouse Address') }} :</div>
                                <div class="col-md-10">
                                <textarea name="warehouse_address"
                                          class="form-control">{{ $item->warehouse_address }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Warehouse Postal Code') }} :</div>
                                <div class="col-md-10">
                                    <input type="text" name="warehouse_postal_code" class="form-control"
                                           value="{{ $item->warehouse_postal_code }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Şehir/Semt') }} :</div>
                                <div class="col-5">
                                    <select name="warehouse_state_id" class="form-control select_city"
                                            data-target=".warehouse_city"
                                            data-url="{{ ifExistRoute('selectSingleCity') }}">
                                        <option value="">{{ _('Select') }}</option>
                                        @foreach($stateList as $state)
                                            <option value="{{ $state->state_id }}"
                                                    @if($item->warehouse_state_id == $state->state_id) selected @endif>
                                                {{ $state->state_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <select name="warehouse_city_id" class="form-control warehouse_city"
                                            data-old_value="{{ $item->warehouse_city_id }}"></select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Warehouse Phone') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        </div>
                                        <input type="text" name="warehouse_phone" class="form-control"
                                               value="{{ $item->warehouse_phone }}">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Invoice Address') }} :</div>
                                <div class="col-md-10">
                                <textarea name="invoice_address"
                                          class="form-control">{{ $item->invoice_address }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Invoice Postal Code') }} :</div>
                                <div class="col-md-10">
                                    <input type="text" name="invoice_postal_code" class="form-control"
                                           value="{{ $item->invoice_postal_code }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Invoice Phone') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        </div>
                                        <input type="text" name="invoice_phone" class="form-control"
                                               value="{{ $item->invoice_phone }}">
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Şehir/Semt') }} :</div>
                                <div class="col-5">
                                    <select name="invoice_state_id" class="form-control select_city"
                                            data-target=".invoice_city"
                                            data-url="{{ ifExistRoute('selectSingleCity') }}">
                                        <option value="">{{ _('Select') }}</option>
                                        @foreach($stateList as $state)
                                            <option value="{{ $state->state_id }}"
                                                    @if($item->invoice_state_id == $state->state_id) selected @endif>
                                                {{ $state->state_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-5">
                                    <select name="invoice_city_id" class="form-control invoice_city"
                                            data-old_value="{{ $item->invoice_city_id }}"></select>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="permissions-tab">
                            <div class="table_container">
                                <table class="table datatable-generated table-hover no-footer dataTable"
                                <thead>
                                <tr>
                                    <th>{{ _('ID') }}</th>
                                    <th>{{ _('Can') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php( $thisUser = $item->get() )
                                @foreach($gateList as $gateName=>$gate)
                                    <tr>
                                        <td>{{ $gateName }}</td>
                                        <td>
                                            @if(Gate::forUser($thisUser)->allows($gateName))
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!$isNew)
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
