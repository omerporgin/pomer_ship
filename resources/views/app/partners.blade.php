@extends( theme('layout.app') , [])

@section('header')
    <h2 class="h2 text-center" style="margin-top: 20px;">FIYATLARIMIZ</h2>
@endsection

@section('content')
<div id="preloader">
    <div id="status"></div>
</div>
<section class="hero position-relative" style="margin-top: 50px;" >
    <div class="hero-body section">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-7">
                    <div class="title-wrapper">
                        <h2 class="title">Entegrasyon Tek Tık Uzağınızda!</h2>
                        <p class="lead text">ShipExporgin'in pazar yeri entegrasyonu ile mağazalarınızı tek bir panelde yönetirken lojistik anlaşmalarıyla kargolarınızı kolaylıkla alıcılarına ulaştırabilirsiniz.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="integration section" style="margin-top: 50px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <h3>Satış Kanalları ve Pazar Yerleri</h3>
            </div>
            <div class="col-12 col-md-8 partners">
                <div class="row align-items-center justify-content-center text-center py-5">
                    <div class="col-md-3 col-sm-3 col-6">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/amazon.svg') }}" alt="amazon">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/ebay.svg') }}" alt="ebay">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3 mt-sm-0">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/etsy.svg') }}" alt="etsy">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3 mt-sm-0">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/shopify.svg') }}" alt="shopify">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/opencart.svg') }}" alt="opencart">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/wish.svg') }}" alt="wish">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/woocommerce.svg') }}" alt="woocommerce">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/magento.svg') }}" alt="magento">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/exporgin-logo-.png') }}" alt="exporgin">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/aliexpress.svg') }}" alt="aliexpress">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/wix.svg') }}" alt="wix">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/walmart.svg') }}" alt="walmart">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center my-3 partners">
            <div class="col-md-12 text-center">
                <h3>Lojistik Firmaları</h3>
            </div>
            <div class="col-12 col-md-8">
                <div class="row align-items-center justify-content-center text-center py-5">
                    <div class="col-md-3 col-sm-3 col-6">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/fedex.svg')}}" alt="Fedex">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/ups.svg') }}" alt="UPS">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3 mt-sm-0">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/dhl.svg') }}" alt="Dhl">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3 mt-sm-0">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/tnt.svg') }}" alt="TNT">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/usps.svg') }}" alt="Usps">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/exporgin-logo-.png') }}" alt="Exporgin">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/hermes.svg') }}" alt="Hermes">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-6 mt-3">
                        <div class="integration-logo card border border-5 border-light rounded hover-translate">
                            <div class="card-body">
                                <img class="img-fluid" src="{{ asset('img/entegration_logos/dpd.svg') }}" alt="DPD">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 text-center">
                <h3>Aradığınızı Bulamadınız mı ?</h3>
                <p class="text"> Hiç Sorun Değil! Bizimle iletişime geçip entegrasyon sağlamak istediğiniz platformu hemen paylaşın.</p>
                <div class="d-grid gap-2 col-3 mx-auto">
                    <a href="iletisim" class="btn btn-outline" style="background-color: #ffc107">İletişim</a>
                </div>
            </div>
        </div>
    </div>
</section>



@endsection
