<div class="container-fluid">

    <div class="form-group row">
        <div class="col-md-2 text-right">{{ _('Türkçe') }} :</div>
        <div class="col-md-8">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="invoice_tr_desc">
                        <i class="far fa-file-alt"></i>
                    </span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="invoice_tr"
                           aria-describedby="invoice_tr_desc" data-order_id="{{ $item->id }}"
                           data-type_code="INV">
                    <label class="custom-file-label" for="invoice_tr">
                        {{ _('Choose file') }}
                    </label>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-2 text-right">{{ _('İngilizce') }} :</div>
        <div class="col-md-8">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="invoice_en_desc">
                        <i class="far fa-file-alt"></i>
                    </span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="invoice_en"
                           aria-describedby="invoice_en_desc" data-order_id="{{ $item->id }}">
                    <label class="custom-file-label" for="inputGroupFile01">
                        {{ _('Choose file') }}
                    </label>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-2 text-right">{{ _('Proforma') }} :</div>
        <div class="col-md-8">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="invoice_en_desc">
                        <i class="far fa-file-alt"></i>
                    </span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="invoice_en"
                           aria-describedby="invoice_en_desc" data-order_id="{{ $item->id }}"
                           data-type_code="PNV">
                    <label class="custom-file-label" for="inputGroupFile01">
                        {{ _('Choose file') }}
                    </label>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-2 text-right">{{ _('Custom Declaration') }} :</div>
        <div class="col-md-8">

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="invoice_en_desc">
                        <i class="far fa-file-alt"></i>
                    </span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="invoice_en"
                           aria-describedby="invoice_en_desc" data-order_id="{{ $item->id }}"
                           data-type_code="DCL">
                    <label class="custom-file-label" for="inputGroupFile01">
                        {{ _('Choose file') }}
                    </label>
                </div>
            </div>

        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-2 text-right"></div>
        <div class="col-md-8">

            <button id="upload_invoice_button" type="button" class="btn btn-primary btn-block">
                {{ _('UPLOAD INVOICES') }}
            </button>

        </div>
    </div>

</div>
