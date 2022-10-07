<div class="accordion" id="accordionHelp">
    @php($count=1)
    @foreach($list as $question=>$answer)
        <div class="card">
            <div class="card-header" id="headingHelp{{ $count }}">
                <h2 class="mb-0">
                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse"
                            data-target="#collapseHelp{{ $count }}" aria-expanded="true" aria-controls="collapseHelp{{ $count }}">
                        {{ $question }}
                    </button>
                </h2>
            </div>

            <div id="collapseHelp{{ $count }}" class="collapse" aria-labelledby="headingHelp{{ $count }}"
                 data-parent="#accordionHelp">
                <div class="card-body">
                    {!! $answer !!}
                </div>
            </div>
        </div>
        @php($count++)
    @endforeach

</div>
