<div id="popup_form">
    <div class="modal-header">
        <h6 class="modal-title">{{ $header }}</h6>

        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
    </div>

    @if (isset($item->name) and !is_null($item->name))
    <div class="card-body text-primary">
        {{ _('Updating') }} : <b>{{ $item->name }}</b>
    </div>
    @endif

    @yield('content')

</div>
