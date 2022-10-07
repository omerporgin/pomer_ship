<!--// BOOTSTRAP ACORDEON -->
<small>65535 karakter ile sınırlandırılmıştır. Bu sınırı geçen durumlarda kayıt edilmez.</small>

<div id="accordion">

    @if(!is_null($item->data))
        @php ($i = 1)
        @foreach(json_decode($item->data) as $topKey=>$list)
            <div class="card">
                <div class="card-header" id="heading_{{ $i }}">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link btn-block"
                                data-toggle="collapse"
                                data-target="#collapse_{{ $i }}" aria-expanded="true"
                                aria-controls="collapse_{{ $i }}">
                            Data : <small><b>{{ $topKey }}</b></small>
                        </button>
                    </h5>
                </div>

                <div id="collapse_{{ $i }}" class="collapse"
                     aria-labelledby="heading_{{ $i }}" data-parent="#accordion">
                    <div class="card-body pretty_json">{{ json_encode($list) }}</div>
                </div>
            </div>
            @php ($i++)
        @endforeach
    @else
        @include(vendorTheme().'.components.empty-div')
    @endif
</div>
