@php
    if ($isNew){
        $header = _('New Order');
        $link = route('vendor_orders.store');
    }else{
        $header = _('Update Order');
        $link = route('vendor_orders.update', $item->id);
    }
@endphp

@extends( vendorTheme().'.forms.layout.layout_form' )

@section('content')

    <form action="{{ $link }}" method="post" class="ajax_form" id="form_order">

        <input type="hidden" value="" name="package_list">

        @if (!$isNew)
            <input type="hidden" name="_method" value="put"/>
        @endif

        <input type="hidden" name="currency" value="120"/>

        <input type="hidden" name="entegration_id" value="{{ $item->entegration_id }}"/>

        <div class="modal-body">

            <div class="alert alert-danger">Düzeltilmeli.</div>

            @if (!$isNew)
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                           aria-controls="home"
                           aria-selected="true">
                            {{ _('Order') }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link " id="expdf-tab" data-toggle="tab" href="#expdf" role="tab"
                           aria-controls="expdf"
                           aria-selected="true">
                            {{ _('expdf') }}
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="errors-tab" data-toggle="tab" href="#errors" role="tab"
                           aria-controls="errors"
                           aria-selected="false">
                            {{ _('Errors/Logs') }}

                            @if ($countLog > 0)
                                <span class="badge badge-danger">
                                {{ $countLog }}
                            </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data"
                           aria-selected="false">
                            {{ _('Data') }}

                            @if ($countData > 0)
                                <span class="badge badge-danger">
                                {{ $countData }}
                            </span>
                            @endif
                        </a>
                    </li>

                    @if(isset($labels))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pdf-tab" data-toggle="tab" href="#pdf" role="tab"
                               aria-controls="pdf"
                               aria-selected="false">
                                {{ _('Pdf') }}

                                <span class="badge badge-danger">
                                {{ count($labels) }}
                            </span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="upload_invoices-tab" data-toggle="tab" href="#upload_invoices"
                           role="tab"
                           aria-controls="upload_invoices"
                           aria-selected="false">
                            {{ _('Upload Invoices') }}
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents"
                           role="tab"
                           aria-controls="upload_invoices"
                           aria-selected="false">
                            {{ _('Documents') }}
                        </a>
                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <button type="button" class="btn btn-primary create_documents" data-order_id="{{  $item->id }}"
                                data-url="{{ ifExistRoute('vendor.create_document') }}">create_documents
                        </button>
                        <embed src="{{$item->document_etgb }}" style="width:100%;height:400px"/>

                        <embed src="{{ $item->document_custom }}" style="width:100%;height:400px"/>

                        <embed src="{{ $item->document_invoice }}" style="width:100%;height:400px"/>
                    </div>

                    @if(isset($labels))
                        <div class="tab-pane fade" id="pdf" role="tabpanel" aria-labelledby="pdf-tab">
                            @foreach($labels as $file )
                                {{-- Cache ediyor. CTRL+F5 ile temizlenmiyor. Bu yüzden date parametresini ekledik. Canlıda kaldırılabilir. --}}
                                <embed src="{{ asset($file) }}?date={{ date("Y-m-d H:i:s") }}"
                                       style="width:100%;height:400px"/>
                            @endforeach
                        </div>
                    @endif

                    <div class="tab-pane fade" id="upload_invoices" role="tabpanel"
                         aria-labelledby="upload_invoices-tab">
                        <div class="container-fluid">

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Türkçe') }} :</div>
                                <div class="col-md-8">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="invoice_tr_desc"><i
                                                    class="far fa-file-alt"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="invoice_tr"
                                                   aria-describedby="invoice_tr_desc" data-order_id="{{ $item->id }}" data-type_code="INV">
                                            <label class="custom-file-label"
                                                   for="invoice_tr">{{ _('Choose file') }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('İngilizce') }} :</div>
                                <div class="col-md-8">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="invoice_en_desc"><i
                                                    class="far fa-file-alt"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="invoice_en"
                                                   aria-describedby="invoice_en_desc" data-order_id="{{ $item->id }}">
                                            <label class="custom-file-label"
                                                   for="inputGroupFile01">{{ _('Choose file') }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Proforma') }} :</div>
                                <div class="col-md-8">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="invoice_en_desc"><i
                                                    class="far fa-file-alt"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="invoice_en"
                                                   aria-describedby="invoice_en_desc" data-order_id="{{ $item->id }}" data-type_code="PNV">
                                            <label class="custom-file-label"
                                                   for="inputGroupFile01">{{ _('Choose file') }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Custom Declaration') }} :</div>
                                <div class="col-md-8">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="invoice_en_desc"><i
                                                    class="far fa-file-alt"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="invoice_en"
                                                   aria-describedby="invoice_en_desc" data-order_id="{{ $item->id }}" data-type_code="DCL">
                                            <label class="custom-file-label"
                                                   for="inputGroupFile01">{{ _('Choose file') }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right"></div>
                                <div class="col-md-8">

                                    <button id="upload_invoice_button" type="button" class="btn btn-primary btn-block">
                                        {{ _('UPLOAD INVOICES') }}
                                    </button>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="expdf" role="tabpanel" aria-labelledby="expdf-tab">
                        <div class="row">
                            @foreach($barcodes as $barcode)
                                <div class="col-3">
                                    <img src="{{ $barcode }}" class="w-100">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        @endif

                        <div class="container-fluid">

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('OrderID') }} :</div>
                                <div class="col-md-4">
                                    <input type="hidden" name="order_id" value="{{ $item->order_id }}"
                                           class="form-control"/>
                                    @if( is_null($item->order_id))
                                        <small>ShipExporgin'de oluşturulan siparişlerde order_id bulunmaz.</small>
                                    @endif
                                    {{ $item->order_id }}
                                </div>
                                <div class="col-md-2 text-right">{{ _('Status') }} :</div>
                                <div class="col-md-4">

                                    <select class="form-control" name="status">
                                        @foreach($order_status as $status)
                                            <option value="{{ $status->id }}"
                                                    style="color:#fff;background: #{{ $status->color }}">
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Name') }} :</div>
                                <div class="col-md-4">
                                    <input type="text" name="firstname" value="{{ $item->firstname }}"
                                           class="form-control"/>
                                </div>
                                <div class="col-md-2 text-right">{{ _('Surname') }} :</div>
                                <div class="col-md-4">
                                    <input type="text" name="lastname" value="{{ $item->lastname }}"
                                           class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Phone') }} :</div>
                                <div class="col-md-4">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" name="phone" value="{{ $item->phone }}"
                                               class="form-control"/>
                                    </div>

                                </div>

                                <div class="col-md-2 text-right">{{ _('Email') }} :</div>
                                <div class="col-md-4">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                        </div>
                                        <input type="text" name="email" value="{{ $item->email }}"
                                               class="form-control"/>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Address') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marked"></i></span>
                                        </div>
                                        <textarea name="address" class="form-control">{{ $item->address }}</textarea>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Description') }} :</div>
                                <div class="col-md-10">

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-mortar-pestle"></i></span>
                                        </div>
                                        <textarea name="description"
                                                  class="form-control">{{ $item->description }}</textarea>
                                    </div>

                                </div>
                            </div>

                            @if(isset($location))
                                <div class="form-group row">
                                    <div class="col-md-2 text-right">{{ _('Location') }} :</div>
                                    <div class="col-md-5" id="selected_location" data-country="{{ $item->country_id }}">
                                        {!! $location !!}
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <small role="button" id="change_location"
                                               class="text-danger">{{ _('change_location') }}</small>
                                    </div>
                                </div>
                            @endif

                            @include('components.select-location', [
                                'location' => $location,
                                'item' => $item
                            ])

                            <div class="form-group row">
                                <div class="col-md-2 text-right">{{ _('Post Code') }} :</div>
                                <div class="col-md-4">
                                    <input type="text" name="post_code" value="{{ $item->post_code }}"
                                           class="form-control" />
                                </div>

                                <div class="col-md-2 text-right">{{ _('Shipping') }} :</div>
                                <div class="col-md-4">
                                    <select class="form-control form-control-sm" name="shipment_id">
                                        <option value="">{{ _('Select') }}</option>
                                        @foreach($shippings as $shippingItem)
                                            <option value="{{ $shippingItem->id }}"
                                                    @if($orderSelectedShipmentId ==$shippingItem->id ) selected @endif>
                                                {{ $shippingItem->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="packages_container">
                                <h5>{{ _('PACKAGES') }}</h5>

                                @foreach($productList as $packageID=>$products)
                                    <table class="table package" id="form_order_table">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>{{ _('Move') }}</th>
                                            <th>{{ _('Content') }}<sup>*</sup></th>
                                            <th>{{ _('Quantity') }}<sup>*</sup></th>
                                            <th>{{ _('Unit Price') }}<sup>*</sup></th>
                                            <th>{{ _('SKU') }}</th>
                                            <th>{{ _('Gtip') }}</th>
                                            <th>{{ _('Delete') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="sortable_tbody">
                                        @foreach($products as $key=>$product)
                                            <tr>
                                                <td class="text-center">
                                                    <i class="fas fa-arrows-alt fa-xs"></i>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="{{ $product['id'] }}"
                                                           name="product_id[]">

                                                    <input type="text" value="{{ $product['name'] }}"
                                                           class="form-control form-control-sm"
                                                           name="name[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['quantity'] }}"
                                                           class="form-control form-control-sm" name="quantity[]">
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" value="{{ $product['unit_price'] }}"
                                                               class="form-control form-control-sm" name="unit_price[]">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">TL</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['sku'] }}"
                                                           class="form-control form-control-sm"
                                                           name="sku[]">
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" value="{{ $product['gtip_code'] }}"
                                                               class="form-control form-control-sm gtip_target_product_{{ $product['id'] }}"
                                                               name="gtip_code[]">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text select_gtip_button"
                                                                  data-target=".gtip_target_product_{{ $product['id'] }}">
                                                                {{ _('Gtip') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger btn-circle btn-sm delete_row"
                                                            type="button">
                                                        <i class="fas fa-trash fa-xs"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        @php
                                            if($packageID!=0){
                                                $package = $packageList[$packageID];
                                            }else{
                                                $package = (object)[
                                                    'width' => '',
                                                    'height' => '',
                                                    'length' => '',
                                                    'width' => '',
                                                    'weight' => '',
                                                    'description' => '',
                                                ];
                                            }
                                        @endphp
                                        @include(vendorTheme().'.templates.formOrderPackageFooterTemplate', [
                                            'package' => $package,
                                            'packageID' => $packageID,
                                            'shippings' => $shippings,
                                        ])

                                    </table>
                                @endforeach
                            </div>

                        </div>

                        @if (!$isNew)

                    </div>



                    <div class="tab-pane fade" id="errors" role="tabpanel" aria-labelledby="errors-tab">

                        <!--// BOOTSTRAP ACORDEON -->
                        <small>65535 karakter ile sınırlandırılmıştır. Bu sınırı geçen durumlarda kayıt edilmez.</small>
                        <div id="accordion">
                            @if(!is_null($item->log))
                                @php ($i = 1)
                                @foreach(json_decode($item->log) as $topKey=>$list)
                                    <div class="card">
                                        <div class="card-header" id="heading_log{{ $i }}">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link btn-block"
                                                        data-toggle="collapse"
                                                        data-target="#collapse_log{{ $i }}" aria-expanded="true"
                                                        aria-controls="collapse_log{{ $i }}">
                                                    Log {{ $topKey }}
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapse_log{{ $i }}" class="collapse"
                                             aria-labelledby="heading_log{{ $i }}" data-parent="#accordion">
                                            <div class="card-body pretty_json">{{ json_encode($list) }}</div>
                                        </div>
                                    </div>
                                    @php ($i++)
                                @endforeach
                            @else
                                @include(vendorTheme().'.components.empty-div')
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">

                        <!--// BOOTSTRAP ACORDEON -->
                        <small>65535 karakter ile sınırlandırılmıştır. Bu sınırı geçen durumlarda kayıt edilmez.</small>
                        <div id="accordion">

                            @if(!is_null($item->data))
                                @php ($i = 1)
                                @foreach(json_decode($item->data) as $topKey=>$list)
                                    <div class="card">
                                        <div class="card-header" id="heading_{{ $i }}">
                                            <h5 class="mb-0">
                                                <button type="button" class="btn btn-link btn-block"
                                                        data-toggle="collapse"
                                                        data-target="#collapse_{{ $i }}" aria-expanded="true"
                                                        aria-controls="collapse_{{ $i }}">
                                                    Data : <small><b>{{ $topKey }}</b></small>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapse_{{ $i }}" class="collapse"
                                             aria-labelledby="heading_{{ $i }}" data-parent="#accordion">
                                            <div class="card-body pretty_json">{{ json_encode($list) }}</div>
                                        </div>
                                    </div>
                                    @php ($i++)
                                @endforeach
                            @else
                                @include(vendorTheme().'.components.empty-div')
                            @endif
                        </div>
                    </div>
                </div>

            @endif

        </div>

        @csrf

        <div class="modal-footer">

            <button class="btn btn-warning  btn-icon-split add_package" type="button">
                <span class="icon text-white-50">
                   <i class="fas fa-box"></i>
                </span>
                <span class="text">{{ _('New Package') }}</span>
            </button>

            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ _('Close') }}</button>
            {{--
                /*
                    Burada iki buton var.
                    Ana butonu gizliyoruz.
                    .number_packages tıklayınca ürünlere paket numarası veriyor ve trigger main_button çalışıyor.
                */
            --}}
            <button type="button"
                    class="@if(!$updatable) disabled @endif btn btn-secondary text-uppercase btn-icon-split number_packages">
                <span class="icon text-white-50">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
                <span class="text">
                    @if ($isNew)
                        {{ _('add') }}
                    @else
                        {{ _('update') }}
                    @endif
                </span>
            </button>

            <button type="button"
                    class=" @if(!$updatable) disabled @else main_button @endif d-none">
            </button>
        </div>
    </form>

    <div class="d-none" id="template_div">
        @include(vendorTheme().'.templates.formOrderProductTemplate')

        <table>
            @include(vendorTheme().'.templates.formOrderPackageFooterTemplate', [
                'packageID' => null,
                'shippings' => $shippings,
            ])
        </table>
    </div>

@endsection
