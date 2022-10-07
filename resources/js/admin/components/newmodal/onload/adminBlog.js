import $ from "jquery";

/**
 *
 * @constructor
 */
export class FormModalOnLoad {

    constructor() {
        this.event()
    }

    event() {
        var self = this;
        $('.delete_blog_image').on('click', function () {
            let url = $(this).data('url')
            let file = $(this).data('file')
            self.deleteImage(url, file, $(this));
        })
    }

    deleteImage(url, file, deleteButton) {
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            cache: false,
            crossDomain: true,
            data: {
                'file': file,
            },
        }).done(function (result) {
            console.log(result)
            if (result.result) {
                deleteButton.parent().hide();
            } else {
                alert('Err')
            }

        }).fail(function (xhr, textStatus, errorThrown) {

        });
    }
}
