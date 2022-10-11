import {orderAddLocation} from "../../../pages/orderAddLocation";

/**
 * Order Modalı içinde şehir ülke seçimi ile ilgili işlemler.
 */
export class VendorOrderModalLocation {


    constructor(parentId) {

        this.partialSearch = []

        /**
         * Select2 modal üzerinde düzgün çalışması için gerekli.
         *
         * @type {*|jQuery|HTMLElement}
         */
        this.dropdownParent = $(parentId)

        this.createPartialSearch()

        new orderAddLocation(this)

        this.addInsertButtons()
    }

    /**
     *
     */
    showOnlyCountry() {
        $(".partial_search").show();
        this.toogled = false;
    }

    /**
     *
     * @param data['country_name']
     * @param data['country_id']
     */
    partialAddOptionToCoutry(data) {
        var newOption = new Option(data.country_name, data.country_id, false, false);
        $('.partial_search select[name=country_id]').empty().append(newOption).trigger('change');
    }

    /**
     *
     * @param data['state_name']
     * @param data['state_id']
     */
    partialAddOptionToState(data) {
        var newOption = new Option(data.state_name, data.state_id, false, false);
        $('.partial_search select[name=state_id]').empty().append(newOption).trigger('change');
    }

    /**
     *
     * @param data['city_name']
     * @param data['id']
     */
    partialAddOptionToCity(data) {
        var newOption = new Option(data.city_name, data.id, false, false);
        $('.partial_search select[name=city_id]').empty().append(newOption); // Do not trigger this time -> .trigger('change')

    }

    /**
     * Creates partial selection select2
     */
    createPartialSearch() {
        var self = this;
        var country = $('.partial_search select[name=country_id]');
        var state = $('.partial_search select[name=state_id]');
        var city = $('.partial_search select[name=city_id]');

        self.createPartialSelect2(country, null, city);

        self.createPartialSelect2(state, country, null);

        self.createPartialSelect2(city, state, null);

        country.on('select2:select', function (e) {
            state.empty();
            city.empty();
        });

        state.on('select2:select', function (e) {
            city.empty();
        });

        city.on('select2:select', function (e) {
        });
    }

    /**
     * Creates select2
     *
     * @param item
     * @param targetItem
     * @param resetItem
     */
    createPartialSelect2(item, targetItem, resetItem) {

        var self = this;

        item.css({'width': '100%'});
        var placeholder = item.data('placeholder');
        var url = item.data('url');

        let select2 = item.select2({
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

                    return {
                        search: params.term,
                        type: 'public',
                        id: targetVal,
                    };
                },
                processResults: function (data, params) {
                    var res = {
                        results: data.results
                    }

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
        select2.on('select2:open', (e) => {

            $('.select2-new-item').remove()

            switch (item.attr('name')) {
                case 'country_id':
                    break
                case 'state_id':
                    $(".select2-results").append(`
                        <small class="select2-new-item state">
                            <i class="fas fa-plus-circle"></i> <b>Create new item</b>
                        </small>`)
                    break
                case 'city_id':
                    $(".select2-results").append(`
                        <small class="select2-new-item city">
                            <i class="fas fa-plus-circle"></i> <b>Create new item</b>
                        </small>`)
                    break
            }
        });

        self.partialSearch.push(select2)

    }

}
