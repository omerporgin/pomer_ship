@extends( theme('layout.app') , [])

@section('content')

    <div class="container margin_top">
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-12 pt-3">
                    <a href="{{ $blog->url }}">
                        <h3>{{ $blog->headline }}</h3>
                    </a>
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <a href="{{ $blog->url }}">
                                <img src="{{ $blogList[$blog->id]->img() }}" alt="{{ $blog->headline }}"
                                     class="blog-image img-fluid">
                            </a>
                        </div>
                        <div class="col-12 col-md-8">{{ $blog->lead }}</div>
                    </div>

                </div>
            @endforeach

            <div class="d-flex pt-2">
                {{ $blogs->links() }}
            </div>

        </div>
    </div>



@endsection
