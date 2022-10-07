import $ from "jquery";
import {VendorOrderModalLocation} from "./newmodal/onload/vendorOrderModalLocation";

/**
 *
 */
let componentGetPrice = function () {
    this.events();
}

/**
 *
 */
componentGetPrice.prototype.events = function () {
    var self = this;

    $("#open-shipping-price").on('click', function () {
        self.openModal();
    });

    $("#get-price").on('click', function () {
        var url = $(this).data("url");
        self.getPriceAjax(url);
    });
}

/**
 *
 */
componentGetPrice.prototype.openModal = function () {

    var self = this;

    self.reset();

    var location = new VendorOrderModalLocation('#modal_shipping_price');
    location.showOnlyCountry();

    $("#modal_shipping_price").modal('show');

    $('#modal_shipping_price').off('hide.bs.modal').on('hide.bs.modal', function (e) {
        self.reset();
    });

    self.countrySelect();
}

componentGetPrice.prototype.countrySelect = function () {

    var select = $('#modal_shipping_price .select2');
    var parent = $('#modal_shipping_price');
    var placeholder = select.data('placeholder');
    var url = select.data('url');

    select.css({'width':'100%'}).select2({
        placeholder: placeholder,
        dropdownParent: parent,
        minimumInputLength: 1, // only start searching when the user has input 3 or more characters
        ajax: {
            url: url,
            delay: 250, // wait 250 milliseconds before triggering the request
            method: 'post',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    type: 'public',
                }

                return query;
            },
            processResults: function (data, params) {

                var res = {
                    results: data.results
                };

                return res;
            }
        },
    });
}
/**
 *
 */
componentGetPrice.prototype.hideResponse = function () {
    $("#get-price-results").html('').hide();
}

/**
 *
 */
componentGetPrice.prototype.hideErrors = function () {
    $("#get-price-errors").html('').hide();
}

/**
 *
 */
componentGetPrice.prototype.reset = function () {
    this.hideErrors();
    this.hideResponse();

    $("#modal_shipping_price input[name=city_id]").val('');

    $("#modal_shipping_price input[name=width]").val('');
    $("#modal_shipping_price input[name=height]").val('');
    $("#modal_shipping_price input[name=length]").val('');
    $("#modal_shipping_price input[name=weight]").val('');
    $("#modal_shipping_price input[name=city_id]").val('');
    $("#modal_shipping_price input[name=post_code]").val('');
    $("#modal_shipping_price select[name=shipment_id]").val('').trigger('click');

    $("#modal_shipping_price .select2").empty();
    $("#modal_shipping_price #toggle_full_search").trigger('click');
}

/**
 *
 * @param url
 */
componentGetPrice.prototype.getPriceAjax = function (url) {

    var self = this;
    $("#get-price-results").show().html(self.loadingScreen());

    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        data: {
            order_id: self.order_id,
            width: $("#modal_shipping_price input[name=width]").val(),
            height: $("#modal_shipping_price input[name=height]").val(),
            length: $("#modal_shipping_price input[name=length]").val(),
            weight: $("#modal_shipping_price input[name=weight]").val(),
            country_id: $("#modal_shipping_price select[name=country_id]").val(),
            shipment_id: $("#modal_shipping_price select[name=shipment_id]").val(),
            post_code: $("#modal_shipping_price input[name=post_code]").val(),
        },
        success: function (response) {
            self.response(response);
            $(".save_pdf").text('Created');
        },
        error: function (request, status, error) {
            var result = JSON.parse(request.responseText);
            console.log(result.results);
            self.showErrors(result);
        }
    });
}

/**
 *
 */
componentGetPrice.prototype.response = function (response) {

    this.hideErrors();

    var content = '';
    $.each(response, function (index, item) {

        let currency = item.currency;
        currency = currency.replace(/USD/g, '<i class="fas fa-dollar-sign fa-xs"></i>')
        currency = currency.replace(/EUR/g, '<i class="fas fa-euro-sign fa-xs"></i>')

        let deliveryTimeData = item.estimatedDelivery;
        deliveryTimeData = deliveryTimeData.replace(/:/g, '')
        deliveryTimeData = deliveryTimeData.replace(/ /g, '')
        deliveryTimeData = deliveryTimeData.replace(/-/g, '')
        content += `
            <div class="row">
                <div class="col-4"> ` + item.name + ` </div>
                <div class="col-4 delivery-price"> ` + item.price.toFixed(2) + ` ` + currency + `</div>
                <div class="col-4 delivery-time" data-time="` + deliveryTimeData + `"> ` + item.estimatedDelivery + `</div>
            </div>`;
    });

    var header = $("#shipping-price-header-template").html();
    $("#get-price-results").html(header + content).show();

    this.findMinPrice();
    this.findMinDeliveryTime();

}

/**
 * Finds min delivery time an adds notification.
 */
componentGetPrice.prototype.findMinPrice = function () {
    let html = $(".delivery-price").eq(0).html();
    $(".delivery-price").eq(0).html(html + `<span class="badge badge-danger ml-3">
        En uygun
    </span>`);

}

/**
 * Finds min delivery time an adds notification.
 */
componentGetPrice.prototype.findMinDeliveryTime = function () {
    let prices = []
    $(".delivery-time").each(function () {
        prices.push($(this).data("time"));
    });
    prices.sort()
    $(".delivery-time").each(function () {
        if ($(this).data("time") == prices[0]) {
            let html = $(this).html();
            $(this).html(html + `<span class="badge badge-success ml-3">
                En uygun
            </span>`)
        }
    });
}

/**
 *
 */
componentGetPrice.prototype.showErrors = function (result) {

    this.hideResponse();

    $("#get-price-errors").html(result[0]).show();
}

componentGetPrice.prototype.loadingScreen = function () {
    return `
        <div class="loading text-center">
            <div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
        </div>`;
}

export {
    componentGetPrice
}
