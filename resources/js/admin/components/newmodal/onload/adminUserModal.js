import {VendorOrderModalLocation} from "./vendorOrderModalLocation";
import {Register} from "../../../../app/pages/register";
import $ from "jquery";

import {SelectCity} from "../../selectCity";
/**
 *
 */
export class FormModalOnLoad {

    constructor() {
        let location = new VendorOrderModalLocation('#modal_theme_primary');
        location.reset();

        $(".full_search").removeClass("d-none");
        $(".toggle_full_search").removeClass("d-none");

        new SelectCity();

        this.adminUserType();

        $(".admin_user_type").trigger('change')
    }

    /**
     * change_location button event
     */
    changeLocationButton() {
        $("#change_location").on("click", function () {
            $(this).hide();
            $(".full_search").removeClass("d-none");
            $(".toggle_full_search").removeClass("d-none");
        });
    }

    adminUserType() {
        $(".admin_user_type").on('change', function () {
            let val = $(this).val()

            $(".hide_on_0").show();
            $(".hide_on_1").show();

            $(".hide_on_" + val).hide();
        })
    }
}
