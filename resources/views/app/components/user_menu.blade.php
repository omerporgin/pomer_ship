@if (Route::has('login'))
    @auth
        <div class="navbar-nav" id="user-menu">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuUser"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    {{ _('User Menu') }}
                </button>
                <ul class="dropdown-menu shadow" aria-labelledby="dropdownMenuUser">
                    <li class="user-avatar">
                        <form action="{{ ifExistRoute('upload-image') }}" method="post" enctype="multipart/form-data"
                              class="user-avatar-form">
                            <div class="user-avatar-top">ds</div>
                            @csrf

                            <div class="position-absolute d-none">
                                <i class="fas fa-upload"></i>
                            </div>

                            <div class="d-none">
                                <input type="file" name="avatar">
                            </div>

                            <img src="{{ asset(auth()->user()->avatar) }}" class="img-fluid">
                        </form>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ ifExistRoute('app.dashboard') }}">Dashboard</a>
                    </li>

                    @if( Gate::allows('admin_see') )
                        <li>
                            <a class="dropdown-item" href="{{ ifExistRoute('admin') }}">Admin Paneli</a>
                        </li>
                    @endif

                    <li>
                        <a class="dropdown-item" href="{{ ifExistRoute('vendor') }}">Kargo Paneliniz</a>
                    </li>
                    <li>
                        <form action="{{ ifExistRoute('logout') }}" method="post">
                            <button class="dropdown-item">Logout</button>
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    @else
        <div class="d-none">
            <div class="d-none d-lg-block">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ ifExistRoute('login' , ['lang' => $lang ]) }}"
                           class="btn btn-warning mr-3" style="margin-right: 15px">{{ _('Log in') }}</a>
                        <a href="{{ ifExistRoute('register',  ['lang' => $lang ]) }}"
                           class="btn btn-outline-warning">{{ _('Register') }}</a>
                    </div>
                </div>
            </div>
            <div class="d-block d-lg-none">
                <div class="row mt-3">
                    <div class="col-6 d-grid gap-2">
                        <a href="{{ ifExistRoute('login', ['lang' => $lang ]) }}"
                           class=" btn btn-warning mr-3">{{ _('Log in') }}</a>
                    </div>
                    <div class="col-6 d-grid gap-2">
                        <a href="{{ ifExistRoute('register',  ['lang' => $lang ]) }}"
                           class="btn btn-outline-warning">{{ _('Register') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endauth

@endif
