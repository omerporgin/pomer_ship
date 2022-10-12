import {formErrors} from "../../forms/formErrors";
import {dataTable} from "../../datatables/dataTable";

/**
 *
 * @constructor
 */
export class FormModalOnLoad {

    constructor() {

        this.userGroup = $("input[name=user_group]").val();
        this.events();
        this.createTable();
    }

    /**
     *
     */
    events() {
        var self = this;
        $(document).off('click', "#add_user_group_price").on('click', "#add_user_group_price", function () {
            var ajaxUrl = $(this).data("url");
            self.addPrice(ajaxUrl);
        })

        /**
         * Create shippng servicess
         */
        $("select[name=shipping_id]").change(function () {
            let shippingId = $(this).val()
            let url = $(this).data('url')
            self.getShippingServiceName(shippingId, url)
        })
    }

    getShippingServiceName(shippingId, url) {
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: {
                shipping_id: shippingId
            },
        }).done(function (result) {
            let select =  $('select[name=shipping_service_name]');
            select.find('option').remove()
            select.append('<option value="">Select</option>')
            $.each(result, function (index, item) {
                select.append('<option value="'+item.name+'">'+item.name+'</option>')
            })

            return true;

        }).fail(function (xhr, textStatus, errorThrown) {

            var form = $("#user_group_form")
            var errors = new formErrors(xhr.responseJSON.errors, form);
            errors.showErrors();

            return false;

        });
    }

    /**
     * Updates shipping zones
     *
     * @param {number} shippingId
     * @param {object} Fbutton
     */
    addPrice(ajaxUrl) {

        var self = this;

        var data = {
            shipping_id: $("select[name=shipping_id]").val(),
            service_name: $("select[name=shipping_service_name]").val(),
            is_default: getLgSwitchVal('is_default'),
            min: $("input[name=min]").val(),
            max: $("input[name=max]").val(),
            price: $("input[name=price]").val(),
            discount: $("input[name=discount]").val(),
            user_group: this.userGroup,
        };

        $.ajax({
            type: 'post',
            url: ajaxUrl,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: data,
        }).done(function (result) {

            self.resetForm();
            return true;

        }).fail(function (xhr, textStatus, errorThrown) {

            var form = $("#user_group_form")
            var errors = new formErrors(xhr.responseJSON.errors, form);
            errors.showErrors();

            return false;

        });
    }

    /**
     *
     */
    createTable() {
        this.table = new dataTable(".price-data-table");
    }

    /**
     *
     */
    resetForm() {
        $("input[name=is_default]").val(0).trigger('change');
        $("input[name=min]").val('');
        $("input[name=max]").val('');
        $("input[name=price]").val('');
        $("input[name=discount]").val('');
        this.table.refresh()
    }
}
