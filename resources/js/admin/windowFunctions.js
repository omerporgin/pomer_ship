import $ from "jquery";

/**
 * This file includes window.functions
 */
export function windowFunctions() {

    /**
     * Returns site base url
     *
     * @returns {string}
     */
    window.baseUrl = function () {
        return $("base").attr('href');
    }

    /**
     * Closes form modal
     */
    window.closeForm = function () {
        forms.close();
    }

    /**
     *  Bugfix for bootstrap tabs
     */
    window.fixBootstrapTabs = function () {

        $('.fixedTabs a').on("click", function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        $('.fixedTabs').each(function () {
            $(this).find('li:first a').trigger("click");
        });
    }

    /**
     * Returns lg_switch value;
     */
    window.getLgSwitchVal = function (inputName) {
        var val = $('input[name=' + inputName + ']').val();
        var checkBoxVal = $('input[name=' + inputName + ']:checked').val();
        if (typeof checkBoxVal != 'undefined') {
            val = checkBoxVal;
        }
        return val;
    }

    /**
     * select2
     */
    window.select2 = function (selector = '.select2_minimal', containerSelector = '#modal_theme_primary') {
        $(selector).css('width', '100%').select2({
            dropdownParent: $(containerSelector)
        });
    }


    /**
     *
     */
    window.richText = function () {
        $('.rich_text').trumbowyg({
            svgPath: '/fonts/icons.svg'
        });
    }

    /**
     *
     */
    window.tooltip = function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('.hasTooltip').tooltip();

        $('.not_allowed').removeAttr('href').attr('aria-disabled', 'true').attr('disabled', 'true').tooltip({
            'title': 'You are not allowed',
            'placement': 'left'
        });
    }

    /**
     * datetimepicker requires id attribute
     *
     * @param className
     */
    window.dateTimePicker = function (className) {

        if (className != '') {
            className = '.hasDateTimePicker';
        }
        $(className).each(function () {
            var id = $(this).attr("id");
            console.log(11)
            $('#' + id).datetimepicker({
                icons: {
                    time: "far fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },
                format: 'YYYY-MM-DD HH:mm:ss',
                allowInputToggle: true,
            });
        });
    }

    /**
     * @param className
     */
    window.datePicker = function (className) {

        if (className != '') {
            className = '.hasDatePicker';
        }
        $(className).each(function () {
            var id = $(this).attr("id");
            $('#' + id).datetimepicker({
                icons: {
                    time: "far fa-clock",
                    date: "fa fa-calendar",
                    up: "fa fa-arrow-up",
                    down: "fa fa-arrow-down"
                },
                format: 'YYYY-MM-DD',
                allowInputToggle: true,
            });
        });
    }

    /**
     * Transfer user data from PHP -> HTML -> JS
     * in both admin and vendor page.
     */
    window.getUserData = function () {
        var data = $("#user_data").attr('data-data');
        data = JSON.parse(data);
        return data;
    }

    /**
     *
     * @param variable
     */
    window.dd = function (variable) {
        console.log(variable);
    }

    /**
     * .pretty_json işaretçisine ait tüm divlerin içindeki jsonları düzenler.
     */
    window.prettyJson = function () {
        $(".pretty_json").each(function () {
            var jjson = $(this).html();
            $(this).jJsonViewer(jjson);
        })
    }

    /**
     * Show/open current menu item.
     */
    window.openCurrentMenuItem = function () {

        var currentUrl = window.location.href;

        // Check 'dashboard' link
        if ($('#dashboard-link .nav-link').attr("href") == currentUrl) {
            $('#dashboard-link .nav-link').addClass("bg-light").addClass("text-primary");
            $('#dashboard-link .nav-link i').addClass("text-primary");
            return;
        }

        // Check other links
        $(".collapse-item").each(function (index, item) {
            var link = $(this).attr("href");
            if (link == currentUrl) {
                $(this).css({
                    'border': '1px dotted #ccc',
                    'background': '#eee'
                }).parent().parent().addClass("show");
            }
        })
    }
}
