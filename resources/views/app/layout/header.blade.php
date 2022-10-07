<header>
    <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light">

            <a href="{{ ifExistRoute('app.index', ['lang' => $lang ]) }}">
                <img src="{{ asset('img/logo.png') }}" alt="" style="width:150px;"
                     class="d-inline-block align-text-top">
            </a>

            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#top-menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between " id="top-menu">
                <div class="navbar-nav">
                    <a href="{{ ifExistRoute('app.feature', ['lang' => $lang ]) }}"
                       class="nav-item nav-link active">Hizmetler</a>
                    <a href="{{ ifExistRoute('app.partners', ['lang' => $lang ]) }}" class="nav-item nav-link active">Entegrasyonlar</a>
                    <a href="{{ ifExistRoute('app.price', ['lang' => $lang ]) }}" class="nav-item nav-link active">Fiyatlar</a>
                    <a href="{{ ifExistRoute('app.contact', ['lang' => $lang ]) }}"
                       class="nav-item nav-link active">İletişim</a>
                </div>

                <div class="d-flex">

                    <div class="navbar-nav d-none d-lg-block" style="padding-right: 15px">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-globe"></i>
                            </a>
                            <div class="dropdown-menu">
                                @if(isset($routeName))
                                    @foreach(langsAll() as $lang)
                                        <a href="{{ ifExistRoute( $routeName, ['lang' => $lang->id]) }}"
                                           class="dropdown-item">
                                            {{ $lang->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    @include('app.components.user_menu')

                </div>
            </div>

        </nav>
    </div>
</header>



