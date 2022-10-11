import $ from "jquery";

export class UploadInvoice {

    constructor() {
        this.events();
    }

    events() {

        var self = this;

        $(document).off('change', ".custom-file-input").on('change', ".custom-file-input", function () {
            let fileInput = $(this);

            let fileList = fileInput[0].files;

            let file = fileList[0];
            let reader = new FileReader(file);
            reader.readAsText(file);
            reader.onload = function () {
                fileInput.attr("data-base64", reader.result);
                self.upload(reader.result)
            };

            reader.onerror = function () {
                console.log(reader.error);
            };
        });

        $(document).off('click', "#upload_invoice_button").on('click', "#upload_invoice_button", function () {
            self.upload()
        });
    }


    /**
     *
     * @param {json} data
     */
    upload() {

        var self = this;
        var url = $("#order_table").attr('data-upload_invoice_url');

        var files = [];
        $(".custom-file-input").each(function(){
            var file = $(this).data('base64');
            files.push(file)
        })
        //var dfd = new $.Deferred();

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: {
                'file': files,
                'order_id': $(".custom-file-input").data('order_id')
            },
        }).done(function (result) {

            $("#payment_form_container").html(result.form);
            $("#payment_form").submit();

           // dfd.resolve();
        }).fail(function (xhr, textStatus, errorThrown) {
            //dfd.reject(xhr.responseJSON);
        });
       // return dfd.promise();
    }
}
