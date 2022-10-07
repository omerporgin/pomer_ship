import $ from "jquery";

/**
 *
 * @constructor
 */
export class PrintLabel {

    constructor() {
        this.events();
    }

    /**
     *
     */
    events() {
        var self = this;

        $(document).off('click', ".btn_label").on('click', ".btn_label", function () {

            self.button = $(this);
            self.save();
        });
    }

    /**
     *
     * @param parentTable
     * @param data
     */
    reset(parentTable) {
        $("#payment_form_container").html('');
        $(".label_msg_container td").html('').addClass("d-none");

        parentTable.find(".label_msg_container td").html('').addClass("d-none");
        parentTable.find('input').removeClass('border border-danger')

    }

    /**
     *
     * @param {json} data
     */
    save() {
        var self = this;
        var parentTable = self.button.parent().parent().parent().parent().find('table')//.css({'border':'1px solid #00f'})

        let order_id = self.button.data('order_id')
        var url = $("#table-order-child").attr('data-url')

        self.reset(parentTable)

        self.buttonProcessing()

        var data = {
            width: parentTable.find('input[name^=width]').val(),
            length: parentTable.find('input[name^=length]').val(),
            height: parentTable.find('input[name^=height]').val(),
            weight: parentTable.find('input[name^=weight]').val(),
            description: parentTable.find('input[name^=description]').val(),
            package_id: parentTable.find('input[name^=package_id]').val(),
            shipment_id: parentTable.parent().find('select[name^=select_shippings]').val(),
        };
        console.log(data)
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: data,
        }).done(function (result) {

            $("#payment_form_container").html(result.form);
            $("#payment_form").submit();

            $(".hide_on_success_" + order_id).addClass("d-none")
            self.buttonSuccess()

        }).fail(function (xhr, textStatus, errorThrown) {

            self.showErrors(parentTable, xhr);
            self.buttonError()

        });
    }

    /**
     *
     * @param parentTable
     * @param jsonErrorList
     */
    showErrors(parentTable, xhr) {

        var string = [];
        let jsonErrorList = xhr.responseJSON

        if (xhr.status == 403) {
            string.push(jsonErrorList.message)
        }

        // Request errors
        if (typeof jsonErrorList.errors != 'undefined') {
            $.each(jsonErrorList.errors, function (index, item) {
                parentTable.find('input[name^=' + index + ']').addClass('border border-danger')
                string.push(item)
            });
        }

        // Shiiping service errors
        if (typeof jsonErrorList.errorList != 'undefined') {

            $.each(jsonErrorList.errorList, function (index, item) {
                parentTable.find('input[name^=' + index + ']').addClass('border border-danger')
                string.push(item)
            });
        }
        if (string.length > 0) {
            parentTable.find(".label_msg_container td").html('<b>'+string.length + ' Errors</b><br><ol clas="text-right" style="list-style-position: inside;">' + '<li>' + string.join('</li><li>') + '</li>' + '</ol>');
        }

        parentTable.find(".label_msg_container td").removeClass("d-none");
    }

    buttonProcessing() {
        let processingText = this.button.data('text_processing');
        this.currentHtml = this.button.html();

        this.button.removeClass("btn-primary").addClass("btn-warning").html(`
            <span class="icon text-white-50">
                <i class="fas fa-circle-notch fa-spin"></i>
            </span>
            <span class="text">` + processingText + `</span> `);
    }

    buttonSuccess() {
        let successText = this.button.data('text_success');
        this.button.removeClass("btn-primary").removeClass("btn-warning").removeClass("btn-danger").addClass("btn-success").html(`
            <span class="icon text-white-50">
                <i class="fas fa-check"></i>
            </span>
            <span class="text">` + successText + `</span>`);
    }

    buttonError() {
        let errText = this.button.data('text_error');
        this.button.removeClass("btn-primary").addClass("btn-danger").html(`
            <span class="icon text-white-50">
                <i class="fas fa-exclamation"></i>
            </span>
            <span class="text">` + errText + `</span>`);
    }
}
