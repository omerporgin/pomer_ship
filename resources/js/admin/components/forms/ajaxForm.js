import {newModal} from '../newmodal/formModal'
import {formErrors} from './formErrors'
import {fileReader} from './fileReader'

/*
	@usage

    <form action="" class="ajax_form" data-true="function_name" data-false="function_name">// Data false yoksa
        ....
        <button class="main_button"> // Diğer buttonları dikkate almaz.
    </form>
*/

export class AjaxForm {

    constructor(form) {

        this.modal = new newModal()

        // Public
        this.form = form;

        this.button = this.form.find(".main_button");

        // Private
        var self = this;

        this.form.find("textarea").each(function () {
            var new_val = $(this).val().replace(/<br>/g, "\n");
            $(this).val(new_val);
        })

        this.disableButton();

        new fileReader(this);

        $(document).on('click', '.form_msg_container .close_msg', function (e) {
            e.preventDefault();
            $(".form_msg_container").hide();
        })

        self.enableButton();

        $(this).find('input').on("keypress", function (e) {
            if (e.which == 10 || e.which == 13) {
                this.button.trigger('click');
            }
        });

        this.button.attr("type", "button").off("click").on("click", function (e) {

            e.preventDefault();

            self.disableButton();

            new formErrors({}, this.form).resetFormStyle();

            self.ajax_post(self);

            return false;
        });
    }

    /**
     *
     */
    disableButton() {
        this.button.each(function () {
            var text = $(this).html();
            $(this).attr("data-text", text.replace(/"/g, "'"));
            $(this).html('<i class="fas fa-circle-notch fa-spin"></i> ... ').attr("disabled", true);
        })
    }

    /**
     *
     */
    enableButton() {
        this.button.each(function () {
            var text = $(this).attr("data-text");
            $(this).html(text).attr("disabled", false);
        });
    }

    /**
     *
     */
    ajax_post() {
        var self = this;
        var new_data = this.getFormData();
        var url = this.form.attr("action");
        var method = this.form.attr("method");

        //
        var methodName = 'post';
        if (this.form.find("input[name=_method]").length > 0) {
            methodName = this.form.find("input[name=_method]").val().toLowerCase();
        }
        if (methodName == 'put') {
            method = 'put';
        } else if (methodName == 'delete') {
            method = 'delete';
        }

        $.ajax({
            type: method,
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: new_data
        }).done(function (data) {

            self.enableButton();

            self.triggerTrueFunction(data);

            return true;

        }).fail(function (xhr, textStatus, errorThrown) {

            self.enableButton();
            if(xhr.status==403){
                alert('This action is unauthorized.')
                return
            }
            var jsonErrorList = xhr.responseJSON;

            // Response may be in errors message bag
            if(typeof jsonErrorList.errors != 'undefined'){
                jsonErrorList = jsonErrorList.errors;
            }
            self.showFromErrors(jsonErrorList);

            return false;

        });
    }

    /**
     *
     */
    triggerTrueFunction(data) {

        this.refreshDataTable();

        var functionName = this.form.data("true");
        if (typeof functionName !== 'undefined' && functionName != '') {
            eval("this." + functionName + '( data );');
        } else {
            this.updated(data);
        }
    }

    /**
     * Ordering will refresh data table
     */
    refreshDataTable() {
        $(".reload-datatable").trigger("click");
    }

    /**
     *
     */
    showFromErrors(errJson) {
        new formErrors(errJson, this.form);
    }

    /**
     *
     */
    getFormData() {
        var new_data = {};
        var self = this;
        $('input[type=file]').each(function () {
            self.form.trigger("change");
        })

        // Input
        this.form.find("input").each(function () {
            var val = '';

            var isMultiple = false;
            if (typeof $(this).attr("name") != 'undefined' && $(this).attr("name").slice(-2) == "[]") {
                isMultiple = true;

                // Define array
                if (typeof new_data[$(this).attr("name")] == "undefined") {
                    new_data[$(this).attr("name")] = [];
                }
            }

            if ($(this).attr("type") == 'file') {
                val = $(this).attr("data-base64");
                new_data[$(this).attr("name")] = val;

            } else if ($(this).attr("type") == 'checkbox') {

                if ($(this).is(':checked')) {
                    var val = $(this).val();
                    if (isMultiple) {
                        new_data[$(this).attr("name")].push(val);
                    } else {
                        new_data[$(this).attr("name")] = val;
                    }
                }
            } else if ($(this).attr("type") == 'radio') {
                val = $(this).parent().find('input[name=' + $(this).attr("name") + ']:checked').val();
                new_data[$(this).attr("name")] = val;

            } else {
                // text or hidden
                val = $(this).val().replace(/\\/g, '/');
                if (typeof $(this).attr("name") != "undefined") {
                    if (isMultiple) {
                        new_data[$(this).attr("name")].push(val);
                    } else {
                        new_data[$(this).attr("name")] = val;
                    }
                }
            }
        });

        // Select
        this.form.find("select").each(function () {
            var isMultiple = false;
            if (typeof $(this).attr("name") != 'undefined' && $(this).attr("name").slice(-2) == "[]") {
                isMultiple = true;

                // Define array
                if (typeof new_data[$(this).attr("name")] == "undefined") {
                    new_data[$(this).attr("name")] = [];
                }
            }
            var val =  $(this).val();
            if (isMultiple) {
                new_data[$(this).attr("name")].push(val);
            } else {
                new_data[$(this).attr("name")] = val;
            }
        });

        this.form.find("textarea").each(function () {

            var val = $(this).val().replace(/\r\n/g, "\\n");
            val = val.replace(/\n/g, "<br>");
            new_data[$(this).attr("name")] = val;
        })

        return new_data;
    }

    /**
     *
     */
    updated(data) {
        this.modal.close();
    }

    /**
     * Delete data table row when an item deleted.
     */
    rowDeleted(data) {
        this.form.closest('tr').remove();
    }
}
