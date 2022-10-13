<!-- // Showed as menu item -->
<li class="nav-item dropdown no-arrow mx-1">
    <span class="nav-link dropdown-toggle" href="#" id="open-shipping-price" role="button"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-tag fa-fw"></i>
    </span>
</li>

<!-- // SHIPPING PRICE MODAL / This is not displayed -->
<div class="modal fade" id="modal_shipping_price" tabindex="-1" role="dialog"
     aria-labelledby="modal_shipping_price"
     aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div id="popup_form">
                <div class="modal-header">
                    <h6 class="modal-title">Fiyat Hesaplama</h6>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="card-body modal_shipping_price">

                    <div id="get-price-results" class="p-3 mb-3 get-price-results"></div>

                    <div id="get-price-errors" class="bg-danger text-white p-3 mb-3"></div>

                    @include('components.select-location-country')

                    <div class="form-group row">
                        <div class="col-12 col-lg-2 text-right">
                            {{ _('Post Code') }}
                        </div>
                        <div class="col-5 col-lg-5">
                            <input type="text" class="form-control" name="post_code">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12 col-lg-2 text-right">
                            {{ _('Dimensions') }}
                        </div>
                        <div class="col-12 col-lg-10">

                            <div class="form-group row">
                                <div class="col-12 col-md-3">

                                    <div class="input-group">
                                        <input type="text" name="width" class="form-control js-change-calculate_desi" placeholder="En">
                                        <div class="input-group-append">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-md-3">

                                    <div class="input-group">
                                        <input type="text" name="height" class="form-control js-change-calculate_desi" placeholder="Boy">
                                        <div class="input-group-append">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-md-3">

                                    <div class="input-group">
                                        <input type="text" name="length" class="form-control js-change-calculate_desi" placeholder="Yükseklik">
                                        <div class="input-group-append">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-md-3">

                                    <div class="input-group">
                                        <input type="text" name="weight" class="form-control js-change-calculate_desi" placeholder="Ağırlık">
                                        <div class="input-group-append">
                                            <span class="input-group-text">kg</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group row">

                                <div class="col-12  ">

                                    <div class="input-group">
                                        <input type="text" name="desi" class="form-control" placeholder="Desi">
                                        <div class="input-group-append">
                                            <span class="input-group-text">desi</span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-2 text-right ">
                        </div>
                        <div class="col-12 col-lg-10 pt-2">
                            <button type="button" class="btn btn-primary btn-lg btn-block" id="get-price"
                                    data-url="{{ route('api_get_price') }}">
                                <i class="fas fa-calculator pr-2"></i>
                                HESAPLA
                            </button>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<div id="shipping-price-header-template" class="d-none">
    <div class="row bg-secondary text-white">
        <div class="col-4">{{ _('Service') }}</div>
        <div class="col-4">{{ _('Price') }}</div>
        <div class="col-4">{{ _('Delivery Time') }}</div>
    </div>
</div>
