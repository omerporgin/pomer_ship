@extends('app.layout.empty', [
    'title' => _('Log In'),
    'description' => _('Let\'s check if you already have an account'),
])

@section('header')
    <h1 class="h2 text-center">{{ _('LOGIN') }}</h1>
@endsection

@section('content')

    <div class="container" id="app_login">
        <div class="row justify-content-md-center">
            <div class="col-12 col-md-6">
                @if (session('status'))
                    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
                        {{ $status }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <div class="font-medium text-red-600">
                            {{ __('Whoops! Something went wrong.') }}
                        </div>

                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login', ['lang' => $lang]) }}">

                    <h3 class="text-uppercase">{{ _('ShipExporgin User Login') }}n3</h3>
                    <p>
                        {{ _('Log in or ') }} <a href="{{ ifExistRoute('register',  ['lang' => $lang ]) }}"><b> {{ _('Create a new account!') }}</b></a>
                    </p>

                    @csrf

                    @if( isset($_GET['returnPage']))
                        <input type="hidden" name="return_page" value="{{ $_GET['returnPage'] }}">
                @endif

                <!-- Email Address -->
                    <div>
                        <x-label for="email" :value="_('Email')"/>

                        <x-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                                 required autofocus/>
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-label for="password" :value="_('Password')"/>

                        <x-input id="password" class="form-control" type="password" name="password" required
                                 autocomplete="current-password"/>
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ _('Remember me') }}</span>
                        </label>
                    </div>


                    <div class="text-end">

                        @if (Route::has('password.request'))
                            <a class="btn btn-secondary text-sm text-gray-600 hover:text-gray-900"
                               href="{{ route('password.request', ['lang'=>109]) }}">
                                {{ _('Forgot your password?') }}
                            </a>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            {{ _('Log in') }}
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
