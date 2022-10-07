@extends('app.layout.empty', [
    'title' => _('Become a member'),
    'description' => _('CREATE YOUR ACCOUNT'),
])

@section('header')
    <h1 class="h2 text-center">{{ _('REGISTER') }}</h1>
@endsection

@section('content')

    <div class="container" id="app_register">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6">

                @include(theme().'.components.auth-show-errors')

                <form method="POST" action="{{ route( 'register', ['lang' => 109]) }}">
                    <div class="row">
                        <h3 class="text-uppercase">{{ _('ShipExporgin become a member') }}</h3>
                        <p>
                            {{ _('Create your account or ') }} <a href="{{ route('login', ['lang'=>$lang]) }}">
                                <b>{{ _('Go to the sign in page!') }}</b>
                            </a>
                        </p>

                        @include('app.components.register-form', [
                            'data' => (object)Session::getOldInput(),
                            'is_register' => true
                        ])

                        <div class="col-12 mb-2 d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ _('Register') }}
                            </button>
                        </div>



                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
