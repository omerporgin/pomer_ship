import {AjaxForm} from './ajaxForm'

/**
 *
 */
export class AjaxForms {

    constructor() {
        this.formList = [];
        this.createForms();
    }

    /**
     *
     */
    createForms() {

        var forms = $(document).find(".ajax_form");

        var self = this;
        forms.each(function () {
            var form = $(this);
            var item = new AjaxForm(form);
            self.formList.push(item);
        });
    }
}
