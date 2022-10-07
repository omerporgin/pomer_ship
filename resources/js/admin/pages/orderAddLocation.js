import {ScanLanguageVariables} from "../components/scanLanguageVariables";

/**
 *
 */
let orderAddLocation = function (parentObj) {

    this.parentObj = parentObj; this

    this.createButtonClickEvent();

    this.addButtonClickEvent();
}

/**
 *
 */
orderAddLocation.prototype.addButtonClickEvent = function () {
    var self = this;
    $("#add-location-button").click(function () {
        var url = $(this).data('url');
        var data = {
            country_id: $("#add-location input[name=country_id]").val(),
            state_id: $("#add-location input[name=state_id]").val(),
            state_name: $("#add-location input[name=state_name]").val(),
            city_name: $("#add-location input[name=city_name]").val(),
        };

        self.saveLocation(url, data);
    })
}

/**
 *
 */
orderAddLocation.prototype.saveLocation = function (url, data) {

    var self = this;

    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',
        cache: false,
        crossDomain: true,
        data : data,
    }).done(function (data) {
        if (data.hasOwnProperty('state')) {
            self.parentObj.partialAddOptionToState({state_name:data.state.text, state_id:data.state.id});
        }
        if (data.hasOwnProperty('city')) {
            self.parentObj.partialAddOptionToCity({city_name:data.city.text, id:data.city.id});
        }

        self.closeForm();

    }).fail(function (xhr, textStatus, errorThrown) {
        console.log(errorThrown);
    });
}

/**
 *
 */
orderAddLocation.prototype.createButtonClickEvent = function () {
    $(document).on('click', '.select2-new-item', function () {

        // Add country data
        var countrySelect2 = $('.partial_search select[name=country_id]');
        var countryData = countrySelect2.select2('data');
        $("#add-location input[name=country_id]").val(countryData[0].id);
        $('#add-location').find(".country_name").html(countryData[0].text);

        // Add city data if exists
        var stateSelect2 = $('.partial_search select[name=state_id]');
        var stateData = stateSelect2.select2('data');
        if (stateData != 0) {
            $("#add-location input[name=state_id]").val(stateData[0].id);
            $('#add-location').find(".state_name").html(stateData[0].text);
            $("#add-location input[name=state_name]").attr("type", 'hidden');
        } else {
            $("#add-location input[name=state_id]").val('');
            $("#add-location input[name=state_name]").attr("type", 'text').val('');
        }

        // Open modal
        $('#add-location').appendTo("body").modal('show');

    })
}

/**
 *
 */
orderAddLocation.prototype.closeForm = function () {
    $('#add-location .close').trigger('click');
}

export {
    orderAddLocation
}
