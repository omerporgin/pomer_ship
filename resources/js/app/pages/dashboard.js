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

        $(".user-avatar-top").on('click', function (e) {
            $("input[name=avatar]").trigger('click');
        });

        $("input[name=avatar]").on('change',function () {
            $(".user-avatar form").submit()
        })
    }
}
