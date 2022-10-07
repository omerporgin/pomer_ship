@extends( theme('layout.app') , [])

@section('header')
    <h2 class="h2 text-center" style="margin-top: 20px;">FIYATLARIMIZ</h2>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="container">
                    <div class="row pb-5">
                        <div class="col-lg-12 col-md-12 mt-5">
                            <div class="d-flex justify-content-sm-center p-3 rounded text" >
                                <div>
                                    <h3 class="mb-1 text-lg-center text" ><span class="pe-2"><i class="bi bi-clock"></i></span>Hizmetlerimize göz atın!</h3>
                                    <div class="d-flex align-items-center" style="margin-top: 20px;">
                                        <span class="pe-3"><span class="is-weight-700"><i class="fas fa-box-open"></i></span> Fullfillment Hizmetleri </span> |
                                        <span class="px-3"><span class="is-weight-700"><i class="fas fa-store"></i></span> Pazaryeri Entegrasyonları</span> |
                                        <span class="ps-3"><span class="is-weight-700"><i class="fas fa-people-carry"></i></span> Operasyon Yönetimi </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="container" style="margin-top: 20px;">
                    <!--Section:Cards-->
                    <div class="row">
                        <!---Bronz Paket-->
                        <div class="col-md-6 col-lg-3">
                            <div class="pricing pricing-warning">
                                <div class="title" style="text-align: center"><a href="#">Bronz Paket</a></div>
                                <div class="price-box" style="text-align: center">
                                    <div class="price">$100<span>/Aylık</span></div>
                                </div>
                                <ul class="options">
                                    <li class="active"><span><i class="fas fa-check"></i></span>Pazaryeri Entegrasyonları
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Hasar Kontrolü</li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Yurtiçi Ücretsiz Depo
                                        Teslimi
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Gönderilerde % 5 İndirim
                                    </li>
                                    <li class="active"  style="height: 3em;"><span></span></li>
                                    <li class="active"  style="height: 3em;"><span></span></li>
                                </ul>
                                <div class="bottom-box">
                                    <a href="#" class="more"><span class="fas fa-angle-right"></span> Daha Fazlası İçin!
                                    </a>
                                    <a href="#" class="btn btn-lg btn-warning clearfix">Satın Al!</a>
                                </div>
                            </div>
                        </div>
                        <!---Gümüş Paket-->
                        <div class="col-md-6 col-lg-3">
                            <div class="pricing pricing-warning text-center">
                                <div class="title" style="text-align: center"><a href="#">Gümüş Paket</a></div>
                                <div class="price-box" style="text-align: center">
                                    <div class="price">$250<span>/Aylık</span></div>
                                </div>
                                <ul class="options">
                                    <li class="active"><span><i class="fas fa-check"></i></span>Pazaryeri Entegrasyonları
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Hasar Kontrolü</li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Yurtiçi Ücretsiz Depo
                                        Teslimi
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Eihracat Lojistik
                                        Danışmanlığı
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Gönderilerde %10 İndirim
                                    </li>
                                    <li class="active"  style="height: 3em;"><span></span></li>
                                </ul>
                                <div class="bottom-box">
                                    <a href="#" class="more"><span class="fas fa-angle-right"></span> Daha Fazlası İçin!
                                    </a>
                                    <a href="#" class="btn btn-lg btn-warning clearfix">Satın Al!</a>
                                </div>
                            </div>
                        </div>
                        <!---Altın Paket-->
                        <div class="col-md-6 col-lg-3">
                            <div class="pricing pricing-warning ">
                                <div class="title" style="text-align: center"><a href="#">Altın Paket</a></div>
                                <div class="price-box" style="text-align: center">
                                    <div class="price">$1.000<span>/Aylık</span></div>
                                </div>
                                <ul class="options">
                                    <li class="active"><span><i class="fas fa-check"></i></span>Pazaryeri Entegrasyonları
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Hasar Kontrolü</li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Yurtiçi Ücretsiz Depo
                                        Teslimi
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Eihracat Lojistik
                                        Danışmanlığı
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>İade/İptal Operasyon
                                        Yönetimi
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Gönderilerde %15 İndirim
                                    </li>
                                </ul>
                                <div class="bottom-box">
                                    <a href="#" class="more"><span class="fas fa-angle-right"></span> Daha Fazlası İçin!
                                    </a>
                                    <a href="#" class="btn btn-lg btn-warning clearfix">Satın Al!</a>
                                </div>
                            </div>
                        </div>
                        <!---Özel Paket-->
                        <div class="col-md-6 col-lg-3">
                            <div class="pricing pricing-warning">
                                <div class="title" style="text-align: center"><a href="#">Özel Paket</a></div>
                                <div class="price-box" style="text-align: center">
                                    <div class="price"><span>Fiyat Sorunuz</span></div>
                                </div>
                                <ul class="options">
                                    <li class="active"><span><i class="fas fa-check"></i></span>Pazaryeri Entegrasyonları
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Hasar Kontrolü</li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Yurtiçi Ücretsiz Depo
                                        Teslimi
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Eihracat Lojistik
                                        Danışmanlığı
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>İade/İptal Operasyon
                                        Yönetimi
                                    </li>
                                    <li class="active"><span><i class="fas fa-check"></i></span>Sınırsız Adet</li>
                                </ul>
                                <div class="bottom-box">
                                    <a href="#" class="more"><span class="fas fa-angle-right"></span> Daha Fazlası İçin!
                                    </a>
                                    <a href="#" class="btn btn-lg btn-warning clearfix">Satın Al!</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="container">
                        <div class="col-12 text-center text-md-start">
                            <p class="text-start">* Ödenen paket ücretleri hesabınıza yüklenecek tutarlardır. Bu tutarlardan herhangi bir kesinti yapılmaz. Gönderileriniz bu tutarlardan düşülerek işleme alınır.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>














@endsection
