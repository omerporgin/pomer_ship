@extends( theme('layout.empty') , [
    'header' => '',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('content')

    <div class="container" id="app_dashboard">

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

    </div>

@endsection

