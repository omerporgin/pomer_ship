import {formErrors} from './formErrors.js'

/**
 *
 */
export class fileReader {

    constructor(AjaxForm) {
        this.AjaxForm = AjaxForm;
        this.max_size = 10000;
        this.init();
    }

    /**
     *
     */
    maxFileSize() {
        if (typeof (this.AjaxForm.form.find("input[name=MAX_FILE_SIZE]").val()) != 'undefined') {
            this.max_size = this.AjaxForm.form.find("input[name=MAX_FILE_SIZE]").val() / 1000;
        }
        return this.max_size;
    }

    /**
     *
     */
    init() {
        var files = this.AjaxForm.form.find("input[type=file]");
        var self = this;
        files.on("change", function (evt) {
            var files = evt.target.files; // FileList object
            self.onChange($(this), files);
        })
    }

    /**
     *
     */
    onChange(target, files) {
        var self = this;
        this.files = files;
        this.AjaxForm.disableButton('Dosya hazırlanıyor.');

        if (this.files && this.files[0]) {

            var FR = new FileReader();

            var size = this.files[0].size / 1024 / 1024;
            size = Math.round(size * 100) / 100;

            if (size < this.maxFileSize()) {

                FR.addEventListener("load", function (e) {

                    var ext = e.target.result.split(';')[0].match(/jpeg|jpg|png|gif|webp|xml|octet-stream/)[0];

                    if (ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'gif' || ext == 'webp' || ext == 'xml' || ext == 'octet-stream') {
                        target.attr("data-base64", e.target.result);

                        //resim varsa sil
                        $('#' + target.name + '_output').remove();
                        var beforeText = '<i class="fas fa-file-code" style="font-size:2em;"></i>';
                        if (ext != 'xml' || ext != 'octet-stream') {
                            beforeText = '<img id="' + this.name + '_output" src="' + e.target.result + '" style="width:100px;height:40px;margin-right:10px" />';
                            target.parent().find("label").css({'margin-left': '110px'});
                        }
                        target.before(beforeText);

                        //Butonu aktif et
                        self.AjaxForm.enableButton();
                    } else {
                        self.showErrors(self.name, 'Resim formatı yanlızca jpg olabilir.');
                    }
                });

            } else {
                self.showErrors(this.name, 'Boyut en fazla ' + this.maxFileSize() + 'kb olabilir.');
            }
            FR.readAsDataURL(this.files[0]);
        } else {
            self.showErrors(this.name, 'Dosya ekleyin');
        }
    }

    /**
     *
     */
    showErrors(name, err) {
        var obj = {};

        obj[name] = err;
        new formErrors({
            err: obj
        }, this.AjaxForm.form);
        $(this).val('');
    }

}
