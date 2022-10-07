import {orderAddLocation} from "../../../pages/orderAddLocation";

/**
 * Order Modalı içinde şehir ülke seçimi ile ilgili işlemler.
 */
let VendorOrderModalLocation = function (parentId) {

    /**
     * Select2 modal üzerinde düzgün çalışması için gerekli.
     *
     * @type {*|jQuery|HTMLElement}
     */
    this.dropdownParent = $(parentId);

    this.toogled = false;

    this.createFullSearch();

    this.createPartialSearch();

    new orderAddLocation(this);

    this.toggleFullSearch();

    this.fullSearchOnChangeEvent();

    this.partialSearchCityOnChangeEvent();

}

/**
 *
 */
VendorOrderModalLocation.prototype.reset = function () {
    var self = this;

    $(".full_search").show();
    $(".partial_search").hide();
    self.toogled = false;
}

/**
 *
 */
VendorOrderModalLocation.prototype.showOnlyCountry = function () {
    this.reset();
    $(".partial_search").show();
    this.toogled = false;
}

/**
 * Partial search on change
 */
VendorOrderModalLocation.prototype.fullSearchOnChangeEvent = function () {

    var self = this;

    $('.full_search select[name=fake_city_id]').on('change', function () {

        var data = $(this).select2('data')[0];

        self.partialAddOptionToCoutry(data);
        self.partialAddOptionToState(data);
        self.partialAddOptionToCity(data);

        var val = $(this).val();

        // Update input data
        $("input[name=city_id]").val(val);
        $("input[name=country_id]").val(data.country_id);
        $("input[name=state_id]").val(data.state_id);
    })
}

/**
 *
 * @param data['country_name']
 * @param data['country_id']
 */
VendorOrderModalLocation.prototype.partialAddOptionToCoutry = function (data) {
    var newOption = new Option(data.country_name, data.country_id, false, false);
    $('.partial_search select[name=fake_country_id]').empty().append(newOption).trigger('change');
}

/**
 *
 * @param data['state_name']
 * @param data['state_id']
 */
VendorOrderModalLocation.prototype.partialAddOptionToState = function (data) {
    var newOption = new Option(data.state_name, data.state_id, false, false);
    $('.partial_search select[name=fake_state_id]').empty().append(newOption).trigger('change');
}

/**
 *
 * @param data['city_name']
 * @param data['id']
 */
VendorOrderModalLocation.prototype.partialAddOptionToCity = function (data) {
    var newOption = new Option(data.city_name, data.id, false, false);
    $('.partial_search select[name=fake_city_id]').empty().append(newOption); // Do not trigger this time -> .trigger('change')

}

/**
 * Full search on change
 */
VendorOrderModalLocation.prototype.partialSearchCityOnChangeEvent = function () {
    $('.partial_search select[name=fake_city_id]').on('change', function () {

        var data = $(this).select2('data')[0];

        var newOption = new Option(data.full_text_name, data.id, false, false);
        $('.full_search select[name=fake_city_id]').empty().append(newOption); // Do not trigger this time -> .trigger('change')

        var val = $(this).val();
        $("input[name=city_id]").val(val);
    });
}

/**
 * Toggles between full search, partial search.
 */
VendorOrderModalLocation.prototype.toggleFullSearch = function () {
    var self = this;

    $(".partial_search").hide();

    $(document).on('click', ".toggle_full_search", function () {

        if (!self.toogled) {
            $(".full_search").hide();
            $(".partial_search").show();
            self.toogled = true;
        } else {
            $(".full_search").show();
            $(".partial_search").hide();
            self.toogled = false;
        }

        var newText = $(this).data('on_toogled_text');
        var oldText = $(this).html();
        $(this).html(newText);
        $(this).data('on_toogled_text', oldText);
    })
}

/**
 * Creates full searchs select2
 */
VendorOrderModalLocation.prototype.createFullSearch = function () {

    var self = this;

    var fullSearch = $(".full_search select");
    fullSearch.css({'width': '100%'});
    var placeholder = fullSearch.data('placeholder');
    var url = fullSearch.data('url');

    fullSearch.select2({
        placeholder: placeholder,
        dropdownParent: self.dropdownParent,
        minimumInputLength: 3, // only start searching when the user has input 3 or more characters
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
        escapeMarkup: function (markup) {
            return markup;
        },
        templateResult: function (data) {
            return data.html;
        },
        templateSelection: function (data) {
            return data.text;
        }
    });
}

/**
 * Creates partial selection select2
 */
VendorOrderModalLocation.prototype.createPartialSearch = function () {
    var self = this;
    var country = $('.partial_search select[name=fake_country_id]');
    var state = $('.partial_search select[name=fake_state_id]');
    var city = $('.partial_search select[name=fake_city_id]');

    self.createPartialSelect2(country, null, city);

    self.createPartialSelect2(state, country, null);

    self.createPartialSelect2(city, state, null);


    country.on('select2:select', function (e) {
        state.empty();
        city.empty();
        self.updateData();
    });
    state.on('select2:select', function (e) {
        city.empty();
        self.updateData();
    });
    city.on('select2:select', function (e) {
        self.updateData();
    });
}

/**
 * Creates select2
 *
 * @param item
 * @param targetItem
 * @param resetItem
 */
VendorOrderModalLocation.prototype.createPartialSelect2 = function (item, targetItem, resetItem) {

    var self = this;

    item.css({'width': '100%'});
    var placeholder = item.data('placeholder');
    var url = item.data('url');

    var partialSearch = item.select2({
        placeholder: placeholder,
        dropdownParent: self.dropdownParent,
        minimumInputLength: 1, // only start searching when the user has input 3 or more characters
        ajax: {
            url: url,
            delay: 250, // wait 250 milliseconds before triggering the request
            method: 'post',
            dataType: 'json',
            data: function (params) {

                var targetVal = null;
                if (targetItem != null) {
                    targetVal = targetItem.select2('data')[0];
                    targetVal = targetVal.id;
                }

                var query = {
                    search: params.term,
                    type: 'public',
                    id: targetVal,
                }

                return query;
            },
            processResults: function (data, params) {
                var res = {
                    results: data.results
                };

                if (resetItem != null) {
                    resetItem.empty();
                }

                return res;
            }
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        templateResult: function (data) {
            return data.html;
        },
        templateSelection: function (data) {
            return data.text;
        }
    });

    // Add "Create new item" button
    if (resetItem == null) {
        partialSearch.on('select2:open', () => {
            var ifNotExistSelector = ":not(:has(.select2-new-item))";
            $(".select2-results" + ifNotExistSelector).append(`
                <small class="select2-new-item">
                    <i class="fas fa-plus-circle"></i> <b>Create new item</b>
                </small>
            `);
        });
    }
}

/**
 *
 */
VendorOrderModalLocation.prototype.updateData = function () {
    dd("updateData");
    var country = $('.partial_search select[name=fake_country_id]');
    var state = $('.partial_search select[name=fake_state_id]');
    var city = $('.partial_search select[name=fake_city_id]');

    $("input[name=city_id]").val(city.val());
    $("input[name=country_id]").val(country.val());
    $("input[name=state_id]").val(state.val());
}

export {
    VendorOrderModalLocation
}
