@extends( theme('layout.app') , [])

@section('content')

    <div class="container" style="margin-top: 80px;">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <div class="row">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img
                                        src="https://www.exporgin.com/images/vivashop_demo_pages/hakkimizda-01.jpg?1632743735812"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img
                                        src="https://www.exporgin.com/images/vivashop_demo_pages/hakkimizda-01.jpg?1632743735812"
                                        class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                    <img
                                        src="https://www.exporgin.com/images/vivashop_demo_pages/hakkimizda-01.jpg?1632743735812"
                                        class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="container" style="margin-bottom: 20px;">
                    <div class="row">
                        <div class="col-12">
                            <!--texts and images-->
                            <div class="row align-items-center" style="margin-top: 20px; margin-bottom:20px;">
                                <div class="col-md-4 how-img">
                                    <img src="{{ asset('img/sn-1-min.jpg') }}"
                                         class="img-fluid"
                                         style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px; "/>
                                </div>
                                <div class="col-md-8">
                                    <h2 class="mb-3">E-İhracat süreçlerinizi ShipExporgin ile yönetin,zamandan ve
                                        paradan tasarruf
                                        edin!</h2>

                                    <p>Pazar yerlerindeki mağazalarınızı entegre etmenin,
                                        lojistik hizmetlerimiz ile yurt dışına en uygun kargo gönderimi yapmanın,
                                        teslimat ve gümrük evraklarınızı yönetmenin en hızlı ve en kolay yolu.</p>
                                </div>
                            </div>
                            <!--texts and images-->

                            <!--cards-->
                            <h2 class="text-center">Haberler & Bilgiler</h2>
                            <hr style="margin-bottom: 45px; color: #F68122 ;height: 4px;">
                            <div class="row row-cols-1 row-cols-md-3 g-4">
                                @foreach($blogList as $blog)
                                    <div class="col">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <a href="{{ $blog->url }}">
                                                    <h5 class="card-title">{{ $blog->headline }}</h5>
                                                </a>
                                                <p class="card-text">
                                                    {{ $blog->lead }}
                                                </p>
                                                <div class="text-end">
                                                    <a href="{{ $blog->url }}" style="text-decoration: none;color: #F68122"
                                                       class="more">Daha Fazlası İçin! <i
                                                            class="fas fa-angle-right"></i>
                                                    </a>
                                                </div>

                                            </div>
                                            <a href="{{ $blog->url }}">
                                                <img src="{{ asset($blog->img()) }}" class="card-img-bottom"
                                                     alt="{{ $blog->headline }}">
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                            <!--cards-->
                            <div class="d-flex flex-row-reverse">
                                <div class="p-2">

                                    <a class="btn btn-outline-warning mt-3"
                                       href="{{ route('app.blogs', ['lang'=>$lang]) }}" role="button">
                                        Blog'a göz atın!
                                        <i class="fas fa-chevron-right ps-2"></i>
                                    </a>

                                </div>

                            </div>


                            {{--                <!--Slider-->--}}
                            {{--              <div class="page-header" style="margin-top: 40px;">--}}
                            {{--                <h3>Haberler & Bilgi</h3>--}}
                            {{--                <hr style="height:5px; color:#ffc107;">--}}

                            {{--                <div id="carouselExampleCaptions" class="carousel slide new_carousel"--}}
                            {{--                     data-bs-ride="carousel">--}}
                            {{--                    <div class="carousel-indicators">--}}
                            {{--                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"--}}
                            {{--                                class="active"--}}
                            {{--                                aria-current="true" aria-label="Slide 1"></button>--}}
                            {{--                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"--}}
                            {{--                                aria-label="Slide 2"></button>--}}
                            {{--                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"--}}
                            {{--                                aria-label="Slide 3"></button>--}}
                            {{--                    </div>--}}
                            {{--                    <div class="carousel-inner ">--}}
                            {{--                        <div class="carousel-item active">--}}

                            {{--                            <div class="carousel-caption d-none d-md-block">--}}
                            {{--                                <h5>Avrupa Ekspres hizmetleri için UPS Para İade Garantisi yeniden--}}
                            {{--                                    uygulanmaya--}}
                            {{--                                    başlandı.</h5>--}}
                            {{--                                <p> Avrupa’da hizmetlerin genişletilmesi ve kapasitenin artılmasını--}}
                            {{--                                    destekleyecek--}}
                            {{--                                    şekilde--}}
                            {{--                                    hazırlanan ve--}}
                            {{--                                    26 Aralık 2021 tarihinden geçerli olacak yeni tarifeleri içeren 2022 UPS--}}
                            {{--                                    Tarife--}}
                            {{--                                    Kılavuzları--}}
                            {{--                                    hazır ve indirilebilir..</p>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                        <div class="carousel-item">--}}

                            {{--                            <div class="carousel-caption d-none d-md-block">--}}
                            {{--                                <h5>Yeni Tarife ve Hizmet kılavuzları</h5>--}}
                            {{--                                <p>İnternetten ulaşabileceğiniz UPS Hizmet ve Tarife Kılvuzları’ndaki son--}}
                            {{--                                    değişiklikleri--}}
                            {{--                                    bulun.</p>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                        <div class="carousel-item">--}}

                            {{--                            <div class="carousel-caption d-none d-md-block">--}}
                            {{--                                <h5>2022 Tarife Rehberleri</h5>--}}
                            {{--                                <p>Avrupa UPS Ekspres hizmetleri için UPS Para İade Garantisi 10 Mayıs 2021--}}
                            {{--                                    tarihinden itibaren--}}
                            {{--                                    yeniden uygulanmaya başlandı.</p>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}
                            {{--                    </div>--}}
                            {{--                    <button class="carousel-control-prev" type="button"--}}
                            {{--                            data-bs-target="#carouselExampleCaptions"--}}
                            {{--                            data-bs-slide="prev">--}}
                            {{--                        <span class="carousel-control-prev-icon" aria-hidden="true">Previous</span>--}}
                            {{--                        <span class="visually-hidden">Previous</span>--}}
                            {{--                    </button>--}}
                            {{--                    <button class="carousel-control-next" type="button"--}}
                            {{--                            data-bs-target="#carouselExampleCaptions"--}}
                            {{--                            data-bs-slide="next">--}}
                            {{--                        <span class="carousel-control-next-icon" aria-hidden="true">Next</span>--}}
                            {{--                        <span class="visually-hidden">Next</span>--}}
                            {{--                    </button>--}}
                            {{--                </div>--}}
                            {{--            </div> --}}
                            {{--            <!--Slider-->--}}

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


@endsection
