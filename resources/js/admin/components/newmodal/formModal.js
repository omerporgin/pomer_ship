import {
    AjaxForms
} from '../forms/ajaxForms'

/**
 * Revealing Module Pattern
 */
export class newModal {

    constructor() {
        this.data = {};
        this.url;
        this.table;
        this.onload;
        this.init()
    }

    init() {
        var self = this;
        $(document).off("click", ".open-modal").on("click", ".open-modal", function (e) {
            e.preventDefault();

            self.url = $(this).data('url');
            self.onload = $(this).data('onload');
            self.data = {
                form: $(this).attr("data-target"),
                template: $(this).attr("data-template"),
                id: $(this).attr("data-id"),
                data: $(this).data('data'),
                onload: $(this).data('onload'),
            }

            self.getData();

        });
    }

    /**
     *
     */
    getData() {

        var self = this;
        $.ajax({
            type: "GET",
            url: self.url,
            dataType: 'text',
            cache: false,
            data: self.data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {

                self.onLoad(result);

            },
            error: function (xhr, ajaxOptions, thrownError) {

                self.onErr(xhr);

                console.log(thrownError, ajaxOptions);
                return false;
            }
        });
    }

    /**
     *
     */
    onLoad(result) {
        var self = this;
        $("#modal_theme_primary .modal-content").html(result);
        try {
            // Dynamic modal loading is not a good idea
            if (typeof this.onload != 'undefined') {
                var {FormModalOnLoad} = require("./onload/" + this.onload);
                new FormModalOnLoad;
            }
        } catch (e) {
            console.log('formModal.js -> ', e.toString());
        }

        new AjaxForms();

        self.openModal()

        fixBootstrapTabs();

        richText();

        select2();

        datePicker();

        dateTimePicker();

        // Bootstrap tooltip
        $('[data-toggle="tooltip"]').tooltip();


    }

    /**
     *
     */
    onErr(xhr) {
        $("#modal_theme_primary .modal-content").html(xhr.responseJSON);
    }

    /**
     *
     */
    openModal() {

        $("#modal_theme_primary").modal("show");

        $(".switch").each(function () {

            var on_txt = $(this).data("on_txt");
            var off_txt = $(this).data("off_txt");

            lc_switch($(this), {
                on_txt: on_txt,
                off_txt: off_txt,
                on_color: 'linear-gradient(140deg, #fabe1c 35%, #f88c21)', // (string) custom "on" color. Supports also gradients
                compact_mode: false
            });
        })

    }

    /**
     *
     */
    close() {
        $("#modal_theme_primary .close").trigger('click');
    }
}
