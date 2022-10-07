/**
 *
 */
export class formErrors {
    constructor(jsonErrorList, form) {

        this.errorList = jsonErrorList;
        this.form = form;

        this.showTotalErrors();

        this.showErrors();
    }

    /**
     *
     */
    showTotalErrors() {
        var total = Object.keys(this.errorList).length;
        if (total > 0) {
            this.form.before('<div class="totalErrorMessage">' + total + ' hata var. Hataları düzeltin.</div>');
        } else {
            $(".totalErrorMessage").remove();
        }
    }

    /**
     *
     */
    resetFormStyle() {
        $(".totalErrorMessage").remove();
        $(".errorMessage").remove();
        $("input, textarea, select").removeClass("errorInput");
    }

    /**
     *
     */
    showErrors() {
        var form = this.form;
        var self = this;

        this.resetFormStyle();
        $.each(this.errorList, function (key, val) {

            if (key.indexOf(".") !== -1) {
                var newKey = key.split('.');
                var item = $("input[name^=" + newKey[0] + "]").eq(newKey[1]);
                self.findEdit(item, val, form);
                return
            }
            var has_found = false;
            if (typeof form.find("input[name=" + key + "]").val() != 'undefined') {
                self.findEdit("input[name=" + key + "]", val, form);
                has_found = true;
            } else if (typeof form.find("textarea[name=" + key + "]").length == 1) {
                self.findEdit("textarea[name=" + key + "]", form);
                has_found = true;
            } else if (typeof form.find("select[name=" + key + "]").val() != 'undefined') {
                self.findEdit("select[name=" + key + "]", val, form);
                has_found = true;
            } else if (typeof form.find("button[name=" + key + "]").val() != 'undefined') {
                self.findEdit("button[name=" + key + "]", val, form);
                has_found = true;
            }

            // Check for multiple input, select...
            if (!has_found) {
                if (typeof form.find("input[name^=" + key + "]").val() != 'undefined') {
                    self.findEdit("input[name^=" + key + "]", val, form);
                    has_found = true;
                } else if (typeof form.find("textarea[name^=" + key + "]").val() != 'undefined') {
                    self.findEdit("textarea[name^=" + key + "]", val, form);
                    has_found = true;
                } else if (typeof form.find("select[name^=" + key + "]").val() != 'undefined') {
                    self.findEdit("select[name^=" + key + "]", val, form);
                    has_found = true;
                }
            }

            //
            if (!has_found) {
                console.log('Err not found : ', key);
            }
        });
    }

    /**
     *
     */
    findEdit(searchText, val, form) {
        if (typeof form != 'undefined') {
            var item = this.form.find(searchText);
            item.addClass("errorInput").after(`
            <div class="errorMessage">
                <i class="fas fa-sort-down"></i>` + val + `
            </div>`);
        }
    }

}
