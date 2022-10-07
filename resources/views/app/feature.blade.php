@extends( theme('layout.app') , [])

@section('header')
    <h1 class="h2">HIZMETLERIMIZ</h1>
@endsection

@section('content')
    <div class="container" style="margin-top:50px;" >

        <div class="p-5 mb-4 bg-light border rounded-3"  style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
            <h1>E-İhracat süreçlerinizi ShipExporgin ile yönetin,zamandan ve paradan tasarruf edin!</h1>
            <p class="lead">Pazar yerlerindeki mağazalarınızı entegre etmenin, lojistik hizmetlerimiz ile yurt dışına en uygun kargo gönderimi yapmanın, teslimat ve gümrük evraklarınızı yönetmenin en hızlı ve en kolay yolu.
            <p><a href="https://www.tutorialrepublic.com" target="_blank" class="btn btn-warning">Ücretsiz Deneyin</a></p>
        </div>
    </div>

    <div class="container"style="margin-top: 50px;" >
        <div class="row justify-content-between">
            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                <img loading="lazy" src="{{ asset('img/pazaryeri.jpg') }}" class="img-fluid rounded shadow" alt="Pazaryeri Entegrasyonu">
            </div>
            <div class="col-12 col-lg-6 text-center text-md-start">
                <p class="lead text">Pazaryeri(Marketplace) sadece satıcılardan oluşan bir alan değildir. Pazaryeri, satıcılar ile alıcıları tek bir çatı altında toplayarak giyimden takıya, aksesuardan gıdaya birçok kategoriden oluşan ve binlerce ürünü içinde barındıran bir platformdur. Satıcılar satış yapmak istedikleri pazaryerleri ile anlaşma sağlayarak buralarda ürünlerini satışa sunabilirler. Her pazaryerinin satıcılarına sunduğu avantajlar bulunmaktadır. Satıcılar bu avantajlardan yararlanarak potansiyel hedef kitlesini genişletebilir ve satış oranını arttırabilir.</p>
                <p class="lead text">Yurtiçinde ve yurtdışında satış yapabileceğiniz birçok pazaryeri bulunmaktadır. Yurtiçinde satış işlemi gerçekleştirmek istiyorsanız Trendyol, Hespiburada, GittiGidiyor, n11 gibi pazaryerlerinde satış yapmaya başlayabilirsiniz.</p>
                <p class="lead text">Yurtdışında satış yapmak istiyorsanız ise Amazon, Etsy, Ebay, Shopify gibi global pazaryerlerinde satıcı hesabı açabilirsiniz.</p>
            </div>
        </div>
    </div>


        <div class="container" style="margin-top:50px;" >
            <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-5 mb-5 mb-lg-0 order-lg-1 text-center text-md-end">
                    <img loading="lazy" src="{{ asset('img/man-showind.svg') }}" alt="Ücretsiz Deneyin" class="img-fluid w-75">
                </div>
                <div class="col-12 col-lg-7 text-center text-md-start">
                    <h2 class="mb-0">Ücretsiz Denemek İster Misiniz?</h2>
                    <p class="lead text my-3">Sizin için doğru çözüm olup olmadığımızı öğrenmek için ekibimizle hızlı bir görüşme ayarlayın. Maliyet yok, minimum ücret yok ve sözleşme yok.</p>
                    <a href="https://www.shipentegra.com/my/user/register" class="btn btn-warning">Ücretsiz Deneyin</a>
                </div>
            </div>
        </div>

@endsection
