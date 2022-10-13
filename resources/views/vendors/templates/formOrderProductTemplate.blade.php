<!-- // Content for package : js -->

<div id="package_div_content">
    <table class="table package">
        <thead class="thead-dark"></thead>
        <tbody class="sortable_tbody">
        <tr>
            <td class="text-center">
                <i class="fas fa-arrows-alt fa-xs"></i>
            </td>
            <td class="text-center">
                <select name="type[]" class="form-control form-control-sm">
                    <option value="0" selected>Product</option>
                    <option value="1">Cargo</option>
                    <option value="2">Surcharge</option>
                </select>
            </td>
            <td>
                <input type="hidden" value="" name="product_id[]">
                <input type="text" value="" class="form-control form-control-sm" name="name[]">
            </td>
            <td>
                <div class="position-relative">
                    <input type="text" value="" class="form-control form-control-sm show_by_type" name="quantity[]">
                </div>
            </td>
            <td>
                <div class="position-relative">
                    <div class="input-group input-group-sm">

                        <input type="text" value="" class="form-control form-control-sm" name="unit_price[]">
                        <div class="input-group-append">
                            <span class="input-group-text js_currency"></span>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="position-relative">
                    <input type="text" value="" class="form-control form-control-sm show_by_type" name="sku[]">
                </div>
            </td>
            <td>
                <div class="position-relative">
                    <div class="input-group input-group-sm show_by_type">
                        <input type="text" value=""
                               class="form-control form-control-sm gtip_target_#COUNT#"
                               name="gtip_code[]">
                        <div class="input-group-append">
                            <span class="input-group-text select_gtip_button"
                                  data-target=".gtip_target_#COUNT#"> {{ _('Gtip') }}</span>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <input type="text" value="" class="form-control form-control-sm show_by_type" name="product_width[]">
            </td>
            <td>
                <input type="text" value="" class="form-control form-control-sm show_by_type" name="product_height[]">
            </td>
            <td>
                <input type="text" value="" class="form-control form-control-sm show_by_type" name="product_length[]">
            </td>
            <td>
                <input type="text" value="" class="form-control form-control-sm show_by_type" name="product_weight[]">
            </td>
            <td class="text-center">
                <button class="btn btn-danger btn-circle btn-sm delete_row" type="button">
                    <i class="fas fa-trash fa-xs"></i>
                </button>
            </td>
        </tr>
        </tbody>
        <tfoot></tfoot>
    </table>
</div>


