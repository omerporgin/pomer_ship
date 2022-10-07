/**
 *
 */
let SelectPostCode = function () {
    this.events();
};

/**
 *
 */
SelectPostCode.prototype.events = function () {
    $("input[name=post_code]").on('focus ', function () {
        var url = $(this).data('url');
        $.ajax({
            url: url,
            method: 'POST',
            type: 'json',
            'data': {
                data: $("#selected_location div:first").text(),
            },
            'success': function (data) {
                $("#postCodeRecommendations option").remove();
                $.each(data, function (index, item) {
                    $("#postCodeRecommendations").append('<option value="' + index + '">' + item + '</option>');
                });

            }
        });
    });

    var url = $("select[name=post_code_dhl]").data('url');

    $("select[name=post_code_dhl]").css({'width': '100%'}).select2({
        placeholder: 'placeholder',
        dropdownParent: $("#modal_theme_primary"),
        minimumInputLength: 5,
        ajax: {
            url: url,
            delay: 250, // wait 250 milliseconds before triggering the request
            method: 'post',
            dataType: 'json',
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            data: function (params) {
                var query = {
                    search: params.term,
                    type: 'public',
                    country_id: $("#selected_location").attr('data-country')
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data, params) {
                $("#postal-code-err").text("");
                return {
                    results: data.results
                };
            },
            error: function (jqXHR, status, error) {
                $("#postal-code-err").text(jqXHR.responseJSON.err);
                return {results: []}; // Return dataset to load after
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


export {
    SelectPostCode
}
