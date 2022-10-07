import $ from "jquery";

export class SelectCity {

    constructor() {
        this.events();
    }

    /**
     *
     */
    events() {
        var self = this;

        $('.select_city').on('change', function () {
            var id = $(this).val();
            var target = $($(this).data('target'));
            var ajaxUrl = $(this).data('url');

            self.getCityAjax(ajaxUrl, id, target)
        })

        $(function () {
            if ($('.select_city').val() != '') {
                $('.select_city').trigger('change');
            }
        })
    }

    /**
     *
     * @param ajaxUrl
     * @param id
     * @param target
     */
    getCityAjax(ajaxUrl, id, target) {

        var self = this;
        $.ajax({
            type: 'post',
            url: ajaxUrl,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: {
                id: id
            },
        }).done(function (result) {
            self.fillSelectTarget(target, result)
            return true;
        }).fail(function (xhr, textStatus, errorThrown) {
            var jsonErrorList = JSON.parse(xhr.responseJSON);
            return false;
        });
    }

    /**
     * Fills selectbox options
     *
     * @param target
     * @param result
     */
    fillSelectTarget(target, result) {
        target.find('option').remove().end();
        target.append('<option value="">Seçiniz</option>')
        result.results.map((item) => {
            target.append('<option value="' + item.id + '">' + item.text + '</option>')
        })

        // Old value
        var oldValue = target.data('old_value');
        if (oldValue != '') {
            target.find('option[value=' + oldValue + ']').prop('selected', true);
        }
    }
}
