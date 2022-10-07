</section>

<footer class="text-center text-lg-start bg-light text-muted">
    <!-- Section: Social media -->
    <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom  d-none">
        <!-- Left -->
        <div class="me-5 d-none d-lg-block">
            <span></span>
        </div>
        <!-- Left -->

        <!-- Right -->
    {{--        <div>--}}
    {{--            <a href="" class="me-4 text-reset">--}}
    {{--                <i class="fab fa-facebook-f"></i>--}}
    {{--            </a>--}}
    {{--            <a href="" class="me-4 text-reset">--}}
    {{--                <i class="fab fa-twitter"></i>--}}
    {{--            </a>--}}
    {{--     --}}
    {{--            <a href="" class="me-4 text-reset">--}}
    {{--                <i class="fab fa-instagram"></i>--}}
    {{--            </a>--}}
    {{--            <a href="" class="me-4 text-reset">--}}
    {{--                <i class="fab fa-linkedin"></i>--}}
    {{--            </a>--}}

    {{--        </div>--}}
    <!-- Right -->
    </section>
    <!-- Section: Social media -->

    <!-- Section: Links  -->
    <section class="pt-4">
        <div class="container text-center text-md-start mt-5">
            <!-- Grid row -->
            <div class="row mt-3">
                <!-- Grid column -->
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <!-- Content -->
                    <h6 class="text-uppercase fw-bold mb-4">
                        <i class="fas fa-truck-loading me-3"></i>SHIPEXPORGIN
                    </h6>
                    <p>
                        Shipexporgin, farklı e-pazaryerlerinde satış yapan tedarikçilerin kargo süreçlerini daha hızlı ve daha az maliyetle yönetmelerini sağlamak için tasarlanmıştır.
                    </p>
                </div>

                <!-- Grid column -->
                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">
                       Müşteri Yardım
                    </h6>

                    <a href="{{ ifExistRoute('app.feature', ['lang' => $lang ]) }}"
                       class="d-block mb-2 text-reset">Hizmetler</a>
                    <a href="{{ ifExistRoute('app.partners', ['lang' => $lang ]) }}" class="d-block mb-2 text-reset">Entegrasyonlar</a>
                    <a href="{{ ifExistRoute('app.price', ['lang' => $lang ]) }}" class="d-block mb-2 text-reset">Fiyatlar</a>
                    <a href="{{ ifExistRoute('app.faq', ['lang' => $lang ]) }}" class="d-block mb-2 text-reset">Sıkça Sorulan
                        Sorular</a>
                    <a href="#" class="d-block mb-2 text-reset">Blog</a>
                    <a href="{{ ifExistRoute('app.contact', ['lang' => $lang ]) }}"
                       class="d-block mb-2 text-reset">İletişim</a>
                </div>
                <!-- Grid column -->

                <!-- Grid column -->
                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <!-- Links -->
                    <h6 class="text-uppercase fw-bold mb-4">
                        Bize Ulaşın
                    </h6>
                    <p><i class="fas fa-home me-3"></i> Yakacık D-100 Kuzey Yanyol No:49 D:1 İstanbul 34880</p>
                    <p>
                        <i class="fas fa-envelope me-3"></i>
                        info@shipexporgin.com
                    </p>
                    <p><i class="fas fa-phone me-3"></i> (+90) 0850 885 10 70</p>


                    <a href="https://www.facebook.com/exporgin" class="me-4 text-reset">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/exporgin" class="me-4 text-reset">
                        <i class="fab fa-twitter"></i>
                    </a>

                    <a href="https://www.instagram.com/exporgin/" class="me-4 text-reset">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/exporgin" class="me-4 text-reset">
                        <i class="fab fa-linkedin"></i>
                    </a>

                </div>

                <!-- Grid column -->
            </div>
            <!-- Grid row -->
        </div>
    </section>
    <!-- Section: Links  -->

    <!-- Copyright -->
    <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
        © 2022 Copyright:
        <a class="text-reset fw-bold" href="#">shipExporgin.com</a>
    </div>
    <!-- Copyright -->
</footer>
