<!-- Modal -->
<div class="modal fade" id="add-location-state" tabindex="-1" aria-labelledby="add-location-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="add-location-label">{{ _('New Location') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col">
                        <div><small>{{ _('Country name') }} : </small></div>
                        <span class="country_name"></span>
                        <input type="hidden" value="" name="country_id">
                    </div>
                    <div class="col">
                        <div><small>{{ _('City name') }} :</small></div>
                        <span class="state_name"></span>
                        <input type="text" value="" name="state_name" class="form-control">
                    </div>
                    <div class="col">
                        <div><small>{{ _('District name') }} :</small></div>
                        <input type="text" value="" name="city_name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ _('Close') }}</button>
                <button type="button" class="btn btn-primary" id="add-location-state-button" data-url="{{ route('api.add_state') }}">
                    <i class="fas fa-map-marked-alt mr-2"></i> {{ _('Add Location') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-location-city" tabindex="-1" aria-labelledby="add-location-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="add-location-label">{{ _('New Location') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col">
                        <div><small>{{ _('Country name') }} : </small></div>
                        <span class="country_name"></span>
                        <input type="hidden" value="" name="country_id">
                    </div>
                    <div class="col">
                        <div><small>{{ _('City name') }} :</small></div>
                        <span class="state_name"></span>
                        <input type="hidden" value="" name="state_id">
                        <input type="hidden" value="" name="state_name" class="form-control">
                    </div>
                    <div class="col">
                        <div><small>{{ _('District name') }} :</small></div>
                        <input type="text" value="" name="city_name" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ _('Close') }}</button>
                <button type="button" class="btn btn-primary" id="add-location-city-button" data-url="{{ route('api.add_city') }}">
                    <i class="fas fa-map-marked-alt mr-2"></i> {{ _('Add Location') }}
                </button>
            </div>
        </div>
    </div>
</div>
