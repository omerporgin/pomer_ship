@extends( theme('layout.app') , [])

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="p-5 text-center " style="color: #F68122">
                    <h3 class="mb-3" style="margin-top: 20px;">Merhaba,</h3>
                    <h1 class="mb-3">Size Nasıl Yardımcı Olabiliriz?</h1>
{{--                    <div class="search-container">--}}
{{--                        <div class="input-group rounded">--}}
{{--                            <input type="search" class="form-control rounded" placeholder="Merak Edilenler Arasında Ara"--}}
{{--                                   aria-label="Search" aria-describedby="search-addon"/>--}}
{{--                            <span class="input-group-text border-0" id="search-addon">--}}
{{--                                <i class="fas fa-search"></i>--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
                @php
                    $count=0;
                    $countI=0;
                @endphp
                @foreach($list as $key=> $value)
                    <h5>{{ $key }}</h5>
                    <div class="accordion" id="accordionExample_{{ $count }}">

                        @foreach($value as $keyNew => $valueNew)
                            <div class="accordion-item">
                                <h6 class="accordion-header" id="headingOne_{{ $countI }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne_{{ $countI }}" aria-expanded="true"
                                            aria-controls="collapseOne_{{ $countI }}">
                                        {{ $keyNew  }}
                                    </button>
                                </h6>
                                <div id="collapseOne_{{ $countI }}" class="accordion-collapse collapse"
                                     aria-labelledby="headingOne_{{ $count }}"
                                     data-bs-parent="#accordionExample_{{ $count }}">
                                    <div class="accordion-body">
                                        {{ $valueNew }}
                                    </div>
                                </div>
                            </div>
                            @php
                                $countI ++;
                            @endphp
                        @endforeach
                    </div>
                    @php
                        @endphp
                @endforeach

            </div>
        </div>
    </div>





























@endsection()
