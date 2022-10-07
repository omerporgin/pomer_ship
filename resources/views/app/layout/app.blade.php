<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@if(isset($title)){{ $title }}@else{{'Ship Exporgin'}}@endif</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <base href="{{ url('') }}/" target="_self">

    <link rel="canonical" href="@if(isset($canonical)){{ $canonical }}@else{{ ifExistRoute(Route::current()->getName(), ['lang'=>$lang]) }}@endif" />
</head>
<body>

@include( theme("layout.header"))


<div class="container">
    <div class="row">
        <div class="col-12">

            @yield('header')
        </div>
    </div>
</div>



@yield('content')

@include(theme("layout.footer"))

<script src="{{ asset('js/app.js') }}"></script>


@if ($errors->any())
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ _('Error') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body alert-danger">

                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#errorModal').modal('show');
        });
    </script>
@endif

</body>
</html>
