import {ScanLanguageVariables} from "../components/scanLanguageVariables";

/**
 *
 */
export class orderAddLocation {
    constructor(parentObj) {

        this.parentObj = parentObj;

        this.createButtonClickEvent();

        this.addButtonClickEvent();
    }

    /**
     *
     */
    addButtonClickEvent() {
        var self = this;
        $("#add-location-city-button").click(function () {
            var url = $(this).data('url');
            var data = {
                state_id: $("#add-location-city input[name=state_id]").val(),
                city_name: $("#add-location-city input[name=city_name]").val(),
            };

            self.saveCity(url, data);
        })

        $("#add-location-state-button").click(function () {
            var url = $(this).data('url');
            var data = {
                country_id: $("#add-location-state input[name=country_id]").val(),
                state_name: $("#add-location-state input[name=state_name]").val(),
                city_name: $("#add-location-state input[name=city_name]").val(),
            };

            self.saveState(url, data);
        })
    }

    /**
     *
     */
    saveCity(url, data) {

        var self = this;

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: data,
        }).done(function (data) {

            if (data.hasOwnProperty('city')) {
                self.parentObj.partialAddOptionToCity({city_name: data.city.text, id: data.city.id});
            }

            $('#add-location-city .close').trigger('click')

        }).fail(function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
        });
    }

    /**
     *
     */
    saveState(url, data) {

        var self = this;

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: data,
        }).done(function (data) {
            if (data.hasOwnProperty('state')) {
                self.parentObj.partialAddOptionToState({state_name: data.state.text, state_id: data.state.id});
            }
            if (data.hasOwnProperty('city')) {
                self.parentObj.partialAddOptionToCity({city_name: data.city.text, id: data.city.id});
            }

            $('#add-location-state .close').trigger('click')

        }).fail(function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
        });
    }

    /**
     *
     */
    createButtonClickEvent() {
        $(document).on('click', '.select2-new-item.state', function () {

            let modal = $('#add-location-state');

            // Add country data
            var countrySelect2 = $('.partial_search select[name=country_id]');
            var countryData = countrySelect2.select2('data');
            modal.find("input[name=country_id]").val(countryData[0].id);
            modal.find(".country_name").html(countryData[0].text);

            // Open modal
            modal.appendTo("body").modal('show');

        })
        $(document).on('click', '.select2-new-item.city', function () {

            let modal = $('#add-location-city');

            // Add country data
            var countrySelect2 = $('.partial_search select[name=country_id]');
            var countryData = countrySelect2.select2('data');
            modal.find("input[name=country_id]").val(countryData[0].id);
            modal.find(".country_name").html(countryData[0].text);

            // Add city data if exists
            var stateSelect2 = $('.partial_search select[name=state_id]');
            var stateData = stateSelect2.select2('data');
            if (stateData != 0) {
                modal.find("input[name=state_id]").val(stateData[0].id);
                modal.find(".state_name").html(stateData[0].text);
                modal.find("#add-location input[name=state_name]").attr("type", 'hidden');
            } else {
                modal.find("input[name=state_id]").val('');
                modal.find("input[name=state_name]").attr("type", 'text').val('');
            }

            // Open modal
            modal.appendTo("body").modal('show');

        })
    }

}
