import $ from "jquery";

/**
 *
 */
export class Dashboard {

    constructor() {
        this.events();
    }

    /**
     *
     */
    events() {
        var self = this

        $(".js_copy_to_clipboard").on('click', function (e) {
            let text = $(this).text();
            self.copyToClipboard(text)
        });

        $(".user-avatar-top").on('click', function (e) {
            $("input[name=avatar]").trigger('click');
        });

        $("input[name=avatar]").on('change', function () {
            $(".user-avatar form").submit()
        })
    }

    copyToClipboard(text) {

        // Copy the text inside the text field
        navigator.clipboard.writeText(text);

        // Alert the copied text
        alert("Copied the text: " + text);
    }
}
