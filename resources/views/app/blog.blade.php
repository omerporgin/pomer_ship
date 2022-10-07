@extends( theme('layout.app') , [
     'title' => $blog->headline,
    'description' => $blog->headlined,
    'canonical' => url($blog->url),
])

@section('content')

    <div class="container margin_top">
    <div class="row">
    <div class="col-12">
        <h1 class="h2">{{ $blog->headline }}</h1>
        <div class="p-5 mb-4 bg-light border rounded-3" style="box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">

            <p class="lead">
                {{ $blog->lead }}
            </p>
        </div>
        <p class="lead">
           <img src="{{ $blog->img() }}" alt="{{ $blog->headline }}" class="blog-image">
            {!!  $blog->body !!}
        </p>
    </div>
    </div>
    </div>


@endsection
