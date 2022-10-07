import $ from "jquery";

/**
 *
 * @constructor
 */
export class AdminShipping {

    constructor() {
        this.events();
    }

    /**
     *
     */
    events() {
        var self = this;
        $(document).on('click', ".update-zones", function () {
            var shippingId = $(this).data("id");
            self.updateZones(shippingId, $(this));
        })

        $(document).on('click', ".update-prices", function () {
            var shippingId = $(this).data("id");
            self.updatePrices(shippingId, $(this));
        })

        $(document).on('click', ".goto-price-url", function () {
            var shippingId = $(this).data("id");
            var url = $('#shipping-table').data('admin_shippin_prices');
            window.location.href = url.replace('#', shippingId);
        })
    }

    /**
     * Updates shipping zones
     *
     * @param {number} shippingId
     * @param {object} Fbutton
     */
    updateZones(shippingId, button) {

        var ajaxUrl = $("#shipping-table").data("admin_update_shipping_zone");

        $.ajax({
            type: 'post',
            url: ajaxUrl,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: {
                shippingId: shippingId
            },
        }).done(function (result) {

            if (result.result) {
                button.html('<b>Updated</b><div stlye="font-size:0.8em">Not found items listed in console</div>');
                console.table(result.notfoundItems);
            } else {
                alert('Hata : ' + result.error);
            }
            return true;

        }).fail(function (xhr, textStatus, errorThrown) {

            var jsonErrorList = JSON.parse(xhr.responseJSON);
            return false;

        });
    }

    /**
     * Updates shipping prices
     *
     * @param {number} shippingId
     * @param {object} button
     */
    updatePrices(shippingId, button) {

        var ajaxUrl = $("#shipping-table").data("admin_update_shipping_prices");

        $.ajax({
            type: 'post',
            url: ajaxUrl,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: {
                shippingId: shippingId
            },
        }).done(function (result) {

            if (result.result) {
                button.html('Updated.<div stlye="font-size:0.8em">Not found items listed in console</div>');
                console.table(result.notfoundItems);
            } else {
                alert('Hata : ' + result.error);
            }
            return true;

        }).fail(function (xhr, textStatus, errorThrown) {

            var jsonErrorList = JSON.parse(xhr.responseJSON);
            return false;

        });

    }

}
