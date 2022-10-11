@extends( theme('layout.empty') , [
    'header' => '',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="container" id="app_dashboard">

        <div class="text-right" style="text-align:right">
        <a href="{{ route('vendor') }}" class="btn btn-primary">Vendor Menu</a>
        </div>

        @include(theme().'.components.auth-show-errors')

        @if(Session::has('is_updated'))
            <p class="alert alert-success">
                <i class="fas fa-check"></i> {{ _('Your data updated.') }}
            </p>
        @endif

        <form action="{{ route('app.user_update') }}" method="post">
            <div class="row">

                @csrf

                <h3 class="text-uppercase">ShipExporgin Üyeliğiniz</h3>

                @include('app.components.register-form', [
                    'data' => $user,
                    'is_register' => false
                ])

                <div class="col-12 mb-2 d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ _('Update') }}
                    </button>
                </div>

            </div>
        </form>
        <p>
            Your api credentals : User : <span role="button" class="js_copy_to_clipboard btn btn-outline-info btn-sm">{{ $user->email }}</span> Key : <span role="button" class="js_copy_to_clipboard btn btn-outline-info btn-sm">{{ $user->api_pass }}</span>
        </p>
    </div>

@endsection

