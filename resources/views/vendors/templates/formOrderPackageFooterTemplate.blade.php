<!-- // Content for package : js -->

<tfoot id="template_package">
<tr>
    <td colspan="2">
        <button class="btn btn-success btn-icon-split add_product btn-sm" type="button">
            <span class="icon text-white-50">
                 <i class="fas fa-plus"></i>
            </span>
            <span class="text">{{ _('New Product') }}</span>
        </button>
    </td>

    <td colspan="10">
        <div class="form-row">

            <input type="hidden" name="package_id[]" value="{{ $packageID }}">
            {{--
            <div class="col">
                <input type="text" class="form-control form-control-sm"
                       name="package_description[]"
                       placeholder="{{ _('Description') }}"
                       value="{{ $package->description }}">
            </div>
            --}}
            <div class="col">

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control form-control-sm"
                           name="package_width[]"
                           placeholder="{{ _('Width') }}"
                           value="{{ $package->width }}">

                    <div class="input-group-append">
                        <span class="input-group-text">cm</span>
                    </div>
                </div>

            </div>

            <div class="col">

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control form-control-sm"
                           name="package_height[]"
                           placeholder="{{ _('Height') }}"
                           value="{{ $package->height }}">
                    <div class="input-group-append">
                        <span class="input-group-text">cm</span>
                    </div>
                </div>

            </div>
            <div class="col">

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control form-control-sm"
                           name="package_length[]"
                           placeholder="{{ _('Length') }}"
                           value="{{ $package->length }}">
                    <div class="input-group-append">
                        <span class="input-group-text">cm</span>
                    </div>
                </div>

            </div>
            <div class="col">

                <div class="input-group input-group-sm mb-3">
                    <input type="text" class="form-control form-control-sm"
                           name="package_weight[]"
                           placeholder="{{ _('Weight') }}"
                           value="{{ $package->weight }}">
                    <div class="input-group-append">
                        <span class="input-group-text">kg</span>
                    </div>
                </div>

            </div>
        </div>

    </td>
</tr>
</tfoot>
