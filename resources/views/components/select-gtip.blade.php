<!-- Button trigger modal -->
<button type="button" class="d-none btn btn-primar select_gtip_button" data-target="">
    Test button
</button>

<!-- Modal -->
<div class="modal fade" id="select_gtip_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ _('Select Gtip') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <select class="select2_gtip" data-url="{{ route("api_gtip") }}" data-target=""></select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ _('Close') }}</button>
                <button type="button" class="btn btn-primary" id="select_button">{{ _('Select') }}</button>
            </div>
        </div>
    </div>
</div>
