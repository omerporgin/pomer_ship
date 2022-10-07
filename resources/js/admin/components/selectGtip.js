/**
 *
 */
let SelectGtip = function () {

    this.defaulText = "";
    this.scanningText = "";
    this.afterScanText = "";

    this.events();
};

/**
 *
 */
SelectGtip.prototype.events = function () {

    $(document).off('click', '.select_gtip_button').on('click', '.select_gtip_button', function () {
        $("#select_gtip_modal").modal('show');
        var target = $(this).attr('data-target');
        $("#select_gtip_modal").attr('data-target', target);
    });

    /**
     * .select2_gtip must contain this attributes :
     *      url -> Ajax url
     *      placeholder -> Placeholder
     *      target -> On select target
     */
    $('.select2_gtip').each(function () {

        // Destroy
        if ($(this).data('select2')) {
            $(this).select2('destroy');
        }

        let url = $(this).data("url");
        let placeholder = $(this).data("placeholder");

        $(this).css({'width':'100%'}).select2({
            placeholder: placeholder,
            dropdownParent: $(this).parent(),
            ajax: {
                url: url,
                delay: 250, // wait 250 milliseconds before triggering the request
                method: 'post',
                dataType: 'json',
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                data: function (params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data, params) {
                    return {
                        results: data.results
                    };
                }
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            templateResult: function(data) {
                return data.html;
            },
            templateSelection: function(data) {
                return data.text;
            }
        });

        $(this).on('select2:select', function (e) {

        });
    });

    // Select gtip on click
    $("#select_gtip_modal #select_button").on('click', function (e) {
        var target = $("#select_gtip_modal").attr("data-target");
        var val = $("#select_gtip_modal .select2_gtip").val();
        console.log(target, val);
        $(target).val(val);
        if (val != null && val !='') {
            $("#select_gtip_modal").modal('hide');
        }
    });

}

export {
    SelectGtip
}
