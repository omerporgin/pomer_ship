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

        <input type="hidden" name="entegration_id" value="{{ $item->entegration_id }}"/>

        <div class="modal-body">
            @if (!$isNew)
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                           aria-controls="home" aria-selected="true">
                            {{ _('Order') }}
                        </a>
                    </li>
                    @if(isset($labels))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pdf-tab" data-toggle="tab" href="#pdf" role="tab"
                               aria-controls="pdf" aria-selected="false">
                                <i class="fas fa-qrcode"></i> {{ _('Labels') }}

                                <span class="badge badge-danger">
                                {{ count($labels) }}
                            </span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="upload_invoices-tab" data-toggle="tab" href="#upload_invoices"
                           role="tab" aria-controls="upload_invoices" aria-selected="false">
                            {{ _('Upload Invoices') }}
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="documents-tab" data-toggle="tab" href="#documents"
                           role="tab" aria-controls="documents" aria-selected="false">
                            {{ _('Documents') }}
                        </a>
                    </li>

                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pickup-tab" data-toggle="tab" href="#pickup"
                           role="tab" aria-controls="pickup" aria-selected="false">
                            <i class="fas fa-dolly-flatbed"></i> {{ _('Pickup') }}
                        </a>
                    </li>

                    <!-- ALIGN RIGHT -->
                    <li class="nav-item ml-auto" role="presentation" data-toggle="tooltip" title="{{ _('Data') }}">
                        <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data"
                           aria-selected="false">
                            <i class="fas fa-database"></i>
                            @if ($countData > 0)
                                <span class="badge badge-danger">
                                {{ $countData }}
                            </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item" role="presentation" data-toggle="tooltip" title="{{ _('Errors/Logs') }}">
                        <a class="nav-link" id="errors-tab" data-toggle="tab" href="#errors" role="tab"
                           aria-controls="errors" aria-selected="false">
                            <i class="fas fa-bug"></i>
                            @if ($countLog > 0)
                                <span class="badge badge-danger">
                                {{ $countLog }}
                            </span>
                            @endif
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="pickup" role="tabpanel" aria-labelledby="pickup-tab">
                        @include(vendorTheme('forms.order.order_pickup_tab'))
                    </div>
                    <div class="tab-pane fade" id="upload_invoices" role="tabpanel"
                         aria-labelledby="upload_invoices-tab">
                        @include(vendorTheme('forms.order.order_upload_invoices_tab'))
                    </div>

                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <button type="button" class="btn btn-primary create_documents" data-order_id="{{ $item->id }}"
                                data-url="{{ ifExistRoute('vendor.create_document') }}">
                            create_documents
                        </button>
                        <embed src="{{$item->document_etgb }}" class="order_document"/>

                        <embed src="{{ $item->document_custom }}" class="order_document"/>

                        <embed src="{{ $item->document_invoice }}" class="order_document"/>
                    </div>

                    @if(isset($labels))
                        <div class="tab-pane fade" id="pdf" role="tabpanel" aria-labelledby="pdf-tab">
                            @foreach($labels as $file )
                                {{-- Cache ediyor. CTRL+F5 ile temizlenmiyor. Bu yüzden date parametresini ekledik. Canlıda kaldırılabilir. --}}
                                <embed src="{{ asset($file) }}?date={{ date("Y-m-d H:i:s") }}" class="order_document"/>
                            @endforeach

                            @foreach($barcodes as $barcode)
                                <div class="col-3">
                                    <img src="{{ $barcode }}" class="w-100">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        @endif
                        <div class="container-fluid">
                            @include(vendorTheme('forms.order.order_home_tab'))
                            {{-- $package değişkeni alttaki includelarda çalışıyor! --}}
                            <div id="packages_container">
                                <h5>{{ _('PACKAGES') }}</h5>

                                @foreach($productList as $packageID => $products)
                                    <table class="table package form_order_table" id="form_order_table_{{ $packageID }}">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>{{ _('Move') }}</th>
                                            <th>{{ _('Type') }}</th>
                                            <th class="w-25">{{ _('Content') }}<sup>*</sup></th>
                                            <th data-toggle="tooltip" title="Quantity">{{ _('Qnt') }}<sup>*</sup></th>
                                            <th style="width:10%">{{ _('Unit Price') }}<sup>*</sup></th>
                                            <th>{{ _('SKU') }}</th>
                                            <th>{{ _('Gtip') }}</th>
                                            <th data-toggle="tooltip" title="Width in cm">{{ _('W-cm') }}</th>
                                            <th data-toggle="tooltip" title="Height in cm">{{ _('H-cm') }}</th>
                                            <th data-toggle="tooltip" title="Length in cm">{{ _('L-cm') }}</th>
                                            <th data-toggle="tooltip" title="Weight in kg">{{ _('W-kg') }}</th>
                                            <th data-toggle="tooltip" title="Delete">{{ _('Del') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="sortable_tbody">
                                        @foreach($products as $key=>$product)
                                            <tr>
                                                <td class="text-center">
                                                    <i class="fas fa-arrows-alt fa-xs"></i>
                                                </td>
                                                <td class="text-center">
                                                    <select name="type[]" class="form-control form-control-sm">
                                                        <option value="0" @if($product['type']==0) selected @endif>
                                                            Product
                                                        </option>
                                                        <option value="1" @if($product['type']==1) selected @endif>
                                                            Cargo
                                                        </option>
                                                        <option value="2" @if($product['type']==2) selected @endif>
                                                            Surcharge
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="hidden" value="{{ $product['id'] }}"
                                                           name="product_id[]">

                                                    <input type="text" value="{{ $product['name'] }}"
                                                           class="form-control form-control-sm" name="name[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['quantity'] }}"
                                                           class="form-control form-control-sm show_by_type @if($product['type']!=0) d-none @endif"
                                                           name="quantity[]">
                                                </td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" value="{{ $product['unit_price'] }}"
                                                               class="form-control form-control-sm" name="unit_price[]">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text js_currency"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['sku'] }}"
                                                           class="form-control form-control-sm show_by_type @if($product['type']!=0) d-none @endif"
                                                           name="sku[]">
                                                </td>
                                                <td>
                                                    <div class="position-relative">
                                                        <div
                                                            class="input-group input-group-sm show_by_type @if($product['type']!=0) d-none @endif">
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
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['width'] }}"
                                                           class="form-control form-control-sm" name="product_width[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['height'] }}"
                                                           class="form-control form-control-sm" name="product_height[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['length'] }}"
                                                           class="form-control form-control-sm" name="product_length[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="{{ $product['weight'] }}"
                                                           class="form-control form-control-sm" name="product_weight[]">
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
                                            'serviceNames' => $serviceNames,
                                        ])

                                    </table>
                                @endforeach
                            </div>
                        </div>
                        @if (!$isNew)

                    </div>

                    <div class="tab-pane fade" id="errors" role="tabpanel" aria-labelledby="errors-tab">
                        @include(vendorTheme('forms.order.order_errors_tab'))
                    </div>

                    <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
                        @include(vendorTheme('forms.order.order_data_tab'))
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
                'shippings' => $serviceNames,
            ])
        </table>
    </div>

@endsection
