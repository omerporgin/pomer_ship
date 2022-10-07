@extends( theme('layout.app') , [])

@section('header')
    <h2 class="h2 text-center" style="margin-top: 20px;">ShipExporgin Yardım Hizmetleri</h2>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">

            <div class="container" style="margin-top: 50px;">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-7">
                        <div class="title-wrapper">
                            <h2 class="title">Nasıl Yardımcı Olabiliriz?</h2>
                            <p class="text-center">İhtiyacınız olan desteği almanın daha fazla yolu var. Bizimle iletişime
                                geçebilirsiniz, sizinle tanışmaktan mutluluk duyarız.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">

                <div class="row pb-5">
                    <div class="col-lg-12 col-md-12 mt-5">
                        <div class="d-flex justify-content-sm-center p-3 rounded text" style="background-color: #ffc107">
                            <div>
                                <h5 class="mb-1 text-lg-center text"><span class="pe-2"><i class="bi bi-clock"></i></span>Çalışma Saatlerimiz:</h5>
                                <div class="d-flex align-items-center">
                                    <span class="pe-3"><span class="is-weight-700">Pazartesi-Cuma:</span> 9:00 - 18:00</span> |
                                    <span class="px-3"><span class="is-weight-700">Cumartesi:</span> 9:00 - 14:30 (Sadece paket kabul ve transfer)</span> |
                                    <span class="ps-3"><span class="is-weight-700">Pazar:</span> Kapalıyız </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row py-5">
                    <div class="col-12 col-lg-7">
                        <div>
                            <h2>Nasıl yardımcı olabiliriz?</h2>
                            <p class="lead">İletişim formunu doldurarak, telefon açarak veya e-posta göndererek
                                taleplerinizi bize iletebilirsiniz. </p>
                        </div>

                        <form>
                            <!-- 2 column grid layout with text inputs for the first and last names -->
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="form-outline">
                                        <input type="text" id="form6Example1" class="form-control"/>
                                        <label class="form-label" for="form6Example1">Ad</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-outline">
                                        <input type="text" id="form6Example2" class="form-control"/>
                                        <label class="form-label" for="form6Example2">Soyad</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" id="form6Example5" class="form-control"/>
                                <label class="form-label" for="form6Example5">E-mail</label>
                            </div>

                            <!-- Number input -->
                            <div class="form-outline mb-4">
                                <input type="number" id="form6Example6" class="form-control"/>
                                <label class="form-label" for="form6Example6">Telefon</label>
                            </div>

                            <!-- Message input -->
                            <div class="form-outline mb-4">
                                <textarea class="form-control" id="form6Example7" rows="4"></textarea>
                                <label class="form-label" for="form6Example7">Mesajınız...</label>
                            </div>
                            <!-- Submit button -->
                            <button type="submit" class="btn btn-warning">Gönder</button>
                        </form>
                        <div class="mt-1" id="return"></div>
                    </div>
                    <div class="col-12 col-lg-5 mt-5 mt-lg-0">
                        <div class="map h-100">
                            <iframe class="w-100 h-100 border-0"
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3017.603901704243!2d29.283280715409443!3d40.858619479316225!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14cadc66a0c140c9%3A0xa3733686fcd98ed0!2sPorgin!5e0!3m2!1str!2str!4v1661154272297!5m2!1str!2str"
                                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row pb-5">
                    <div class="col-lg-12 col-md-12 mt-5">
                        <div class="new-card-text-center">
                            <div class="card-body">
                                <h5 class="card-title">Bize Ulaşın</h5>
                                <div class="row">
                                    <div class="col-6 col-sm-4"><span class="text-bolder"> Yardım İçin Bize Yazın!</span><p><i class="fas fa-envelope"></i> : info@shipexpogin.com</p></div>
                                    <div class="col-6 col-sm-4">Merkezimize Ulaşın!<p><i class="fas fa-map-marker-alt"></i> : Hürriyet Mah. Yakacık D-100 Kuzey YanYol No: 49/1 34876 Kartal, Istanbul</p></div>
                                    <div class="col-6 col-sm-4">Telefonla Ulaşın!<p><i class="fas fa-phone"></i> : 0850 885 10 70</p></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

    </div>

</div>

</div>



@endsection

