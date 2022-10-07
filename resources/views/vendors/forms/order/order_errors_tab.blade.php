<!--// BOOTSTRAP ACORDEON -->
<small>65535 karakter ile sınırlandırılmıştır. Bu sınırı geçen durumlarda kayıt edilmez.</small>
<div id="accordion">
    @if(!is_null($item->log))
        @php ($i = 1)
        @foreach(json_decode($item->log) as $topKey=>$list)
            <div class="card">
                <div class="card-header" id="heading_log{{ $i }}">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link btn-block"
                                data-toggle="collapse"
                                data-target="#collapse_log{{ $i }}" aria-expanded="true"
                                aria-controls="collapse_log{{ $i }}">
                            Log {{ $topKey }}
                        </button>
                    </h5>
                </div>

                <div id="collapse_log{{ $i }}" class="collapse"
                     aria-labelledby="heading_log{{ $i }}" data-parent="#accordion">
                    <div class="card-body pretty_json">{{ json_encode($list) }}</div>
                </div>
            </div>
            @php ($i++)
        @endforeach
    @else
        @include(vendorTheme().'.components.empty-div')
    @endif
</div>
