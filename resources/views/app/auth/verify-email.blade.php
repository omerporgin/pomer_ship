@extends('app.layout.empty', ['lang'=>109])

@section('content')

<main id="page-page">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-6">

                <p>
                    {{ _('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                </p>

                @if (session('status') == 'verification-link-sent')
                <p>
                    {{ _('A new verification link has been sent to the email address you provided during registration.') }}
                </p>
                @endif

                <div class="row">
                    <form class="col-6" method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div>
                            <button type="submit" class="btn btn-primary  btn-block">
                                {{ _('Resend Verification Email') }}
                            </button>
                        </div>
                    </form>

                    <form class="col-6" method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" class="btn btn-danger btn-block">
                            {{ _('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
