@php($hideOnSuccess ='hide_on_success___ORDER_ID__')
@php($selectName ='select_service___ORDER_ID__')

<div class="template d-none">
    <!--// Label table top content -->
    <div id="label-top-content">
        <div class="row">
            <div class="col-3 {{ $hideOnSuccess }}" >
                {{ _('Shipping Service') }} :
                <select class="form-control form-control-sm {{ $selectName }} update_services" name="select_shippings[]" data-order_id="__ORDER_ID__" data-processed="0" data-loading_text="{{ _('Loading services') }}">

                    @foreach($shippings['list'] as $shippingItem)
                        <option value="{{ $shippingItem->id }}" __SELECTED_{{ $shippingItem->id }}__>
                            {{ $shippingItem->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-6 {{ $hideOnSuccess }}">
                {{ _('Description') }} :
                <input name="description[]" type="text" class="form-control form-control-sm" value="__DESCRIPTION__">
            </div>

            <div class="col-3">
                <br>
                <button type="button" class="btn btn-primary btn-sm btn-icon-split print-label"
                        data-order_id="__ORDER_ID__"
                        data-text_success="{{ _('Success') }}"
                        data-text_processing="{{ _('Processing') }}"
                        data-text_error="{{ _('Failed') }}" >
                <span class="icon text-white-50">
                    <i class="fas fa-qrcode"></i>
                </span>
                    <span class="text">{{ _('Label') }}</span>
                </button>
            </div>
        </div>
    </div>

    <div id="table_header">
        <table>
            <thead class="thead-dark">
            <tr>
                <th>{{ _('Check') }}</th>
                <th>{{ _('Height') }}</th>
                <th>{{ _('Width') }}</th>
                <th>{{ _('Length') }}</th>
                <th>{{ _('Weight') }}</th>
            </tr>
            </thead>
        </table>
    </div>
</div>
