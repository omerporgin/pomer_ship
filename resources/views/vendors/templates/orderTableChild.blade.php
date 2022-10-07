<table id="table-order-child" class="d-none" data-url="{{ ifExistRoute('api_print_label') }}">
    <tbody>
    <tr>
        <td class="text-center">
            <div class="form-check">
                <input class="form-check-input" type="checkbox">
            </div>
        </td>
        <td>
            <input type="hidden" class="form-control form-control-sm" name="package_id[]" value="__PAKAGE_ID__">
            <input type="text" class="form-control form-control-sm" name="description[]" value="__DESCTIPTION__">
        </td>
        <td>
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control form-control-sm"
                       name="height[]"
                       value="__HEIGHT__">
                <div class="input-group-append">
                    <span class="input-group-text">cm</span>
                </div>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control form-control-sm"
                       name="width[]"
                       value="__WIDTH__">
                <div class="input-group-append">
                    <span class="input-group-text">cm</span>
                </div>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control form-control-sm"
                       name="length[]"
                       value="__LENGTH__">
                <div class="input-group-append">
                    <span class="input-group-text">cm</span>
                </div>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm mb-3">
                <input type="text" class="form-control form-control-sm"
                       name="weight[]"
                       value="__WEIGHT__">
                <div class="input-group-append">
                    <span class="input-group-text">kg</span>
                </div>
            </div>
        </td>
    </tr>
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right d-none" colspan="8"></td>
        </tr>
    </tfoot>
</table>
