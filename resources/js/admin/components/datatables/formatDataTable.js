import {TimeAgoObj} from "../timeago";

/**
 *
 * @param {*} rows
 * @param {*} methodName
 * @param {*} data
 */
export class FormatDataTable {

    constructor() {
        this.time_ago = new TimeAgoObj();
    }

    /**
     * time ago function
     *
     * @param date
     * @returns {*}
     */
    timeAgo(date) {
        return this.time_ago.showUnixTimeStamp(date);
    }

    /**
     *
     * @param {*} item
     * @returns
     */
    ImageColumn(item) {
        var count_image = '';
        if (item.img.count != 0) {
            count_image = '<span class="badge badge-danger navbar-badge">' + item.img.count + '</span>';
        }
        return `
        <div class="open_form text-center"  data-target="image"
            data-id="` + item.id + `"
            data-onload="formImages"
            data-data="` + item.img.entity + `">
            <div class="position-relative hasBgImg tableImgDiv">
            <picture>
            <img src="` + item.img.src + `" alt="data table img">
            </picture>
                ` + count_image + `
            </div>
        </div>`;
    }

    /**
     *
     * @param {*} item
     * @returns
     */
    deleteButton(item) {
        if (item.deletable) {
            return `
            <div class="text-center">
                <form method="post" action="` + item.DestroyUrl + `" class="ajax_form" data-true="rowDeleted">
                    <input type="hidden" name="_method" value="DELETE">
                    <span href="#" class="btn btn-danger btn-circle btn-sm">
                        <i class="fas fa-trash main_button"></i>
                    </span>
                </form>
            </div>`;
        } else {
            var $tooltipClass = '';

            if (item.deletableMsg != null) {
                $tooltipClass = 'hasTooltip';
            }
            return `
            <div class="text-center">
                <div class="btn bg-gray-200 btn-circle btn-sm ` + $tooltipClass + `" data-tooltip="tooltip" data-html="true" data-placement="right" title="` + item.deletableMsg + `">
                    <i class="fas fa-trash bg-gray-100"></i>
                </div>
            </div>`;
        }
    }

    /**
     *
     * @param {*} item
     * @param {*} target
     * @param {*} onloadFunctionName
     * @param {*} data
     * @returns
     */
    /**
     *
     * @param {*} item
     * @param {*} target
     * @param {*} onloadFunctionName
     * @param {*} data
     * @returns
     */
    updateButton(data, id, onloadFunctionName = '') {

        if (typeof data.update_url == 'undefined') {
            console.log('url required ' + this.constructor.name);
            return;
        }
        var url = data.update_url.replace('###', id)

        if (onloadFunctionName != '') {
            onloadFunctionName = ' data-onload="' + onloadFunctionName + '" ';
        }

        let dataString = JSON.stringify(data);
        dataString = btoa(dataString);
        return `<div class="text-center">
            <span class="btn btn-success btn-circle btn-sm open-modal" data-url="` + url + `" ` + onloadFunctionName + ` data-data="` + dataString + `">
                <i class="fas fa-pen" ></i>
                </span>
        </div>`
    }

    /**
     *
     * @param {*} item
     * @returns
     */
    activeButton(item) {
        var active = '<span class="badge badge-success">Active</span>';
        if (item.active == 0) {
            active = '<span class="badge badge-danger">Passive</span>';
        }
        return '<div class="text-center">' + active + '</div>';
    }

    /**
     *
     * @param {*} item
     * @returns
     */
    yesNo(item) {
        var active = '<span class="badge badge-success">Yes</span>';
        if (item == 0) {
            active = '<span class="badge badge-danger">No</span>';
        }
        return '<div class="text-center"><div class="text-center">' + active + '</div></div>';
    }

    /**
     *
     * @param {*} item
     * @returns
     */
    isActive(status) {
        var isActive = '<span class="badge badge-success">Yes</span>';

        if (status == 0) {
            isActive = '<span class="badge badge-danger">No</span>';
        }
        return `
        <div class="text-center">
            <div class="text-center">
                ` + isActive + `
            </div>
        </div>`;
    }

    /**
     *
     * @param {*} item
     * @returns
     */
    checkButton(item) {
        var active = '<span class="badge badge-success">True</span>';
        if (item == 0) {
            active = '<span class="badge badge-danger">False</span>';
        }
        return '<div class="text-center">' + active + '</div>';
    }

}
