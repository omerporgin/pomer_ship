@extends('app.layout.empty', ['lang'=>109])

@section('content')

    <main id="page-page">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-6">


                    <div class="mb-4 text-sm text-gray-600">
                        {{ _('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

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

                    <form method="POST" action="{{ route('password.email',['lang'=>109]) }}">
                    @csrf

                    <!-- Email Address -->
                        <div>
                            <x-label for="email" :value="_('Email')"/>

                            <x-input id="email" class="form-control" type="email" name="email" :value="old('email')"
                                     required autofocus/>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                {{ _('Email Password Reset Link') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
