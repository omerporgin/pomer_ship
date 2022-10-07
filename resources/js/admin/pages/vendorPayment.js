import $ from "jquery";

let VendorPayment = function () {
    this.ratios = [];
    this.events();
}

/**
 *
 */
VendorPayment.prototype.events = function () {
    var self = this;

    // Cardnumber change
    $(document).on('keyup', "input[name=card_number]", function () {

        self.emptyInstallmentContainer();

        var cardNumber = $(this).val();
        if (cardNumber.length > 15) {
            self.getInstallments();
        }
    });

    // Click installment
    $(document).on('click', '#installment_container .installment', function () {
        self.unselectAllInstallments();
        self.selectInstallment($(this));
        self.enablePayButton();
    });

    $(document).on('keyup', 'input[name=card_number]', function () {
        self.enablePayButton();
    });

    $(document).on('keyup', 'input[name=card_name]', function () {
        self.enablePayButton();
    });

    $(document).on('keyup', 'input[name=card_cvv]', function () {
        self.enablePayButton();
    });

    /**
     * Dikkat : Tüm installmentlar baştan oluşacak. Ancak taksit seçili ise değer değişmemeli.
     */
    $(document).on('keyup', 'input[name=total_payment]', function () {
        var selectedValue = $("input[name=installment]:checked").val();
        self.showInstallments();
        var selectedInstallment = $("input[name=installment][value=" + selectedValue + "]").parent().parent().parent();
        self.selectInstallment(selectedInstallment);
        self.enablePayButton();
    });

    $(document).on('click', '.disable_on_start', function () {
        self.onSubmit();
    });

}

/**
 *
 */
VendorPayment.prototype.onSubmit = function () {

    var url = $("#form_payment").attr('action');

    var data = {
        card_number: $("input[name=card_number]").val(),
        card_name: $("input[name=card_name]").val(),
        card_cvv: $("input[name=card_cvv]").val(),
        total_payment: $("input[name=total_payment]").val(),
        installment: $("input[name=installment]:checked").val(),
        expiry_month: $("select[name=expiry_month]").val(),
        expiry_year: $("select[name=expiry_year]").val(),
        card_type: $("input[name=card_type]").val(),
        payment_id: $("input[name=payment_id]").val(),
    };

    $.ajax({
        type: 'post',
        url: url,
        dataType: 'json',
        cache: false,
        crossDomain: true,
        data: data,
    }).done(function (result) {
        if (result.result) {
            $("#payment_form_container").html(result.form);
            $("#payment_form").submit();
        } else {
            alert('Hata : ' + result.err);
        }
        return true;

    }).fail(function (xhr, textStatus, errorThrown) {

        var jsonErrorList = JSON.parse(xhr.responseJSON);
        return false;

    });
}

/**
 *
 */
VendorPayment.prototype.getInstallments = function () {

    var self = this;

    $.ajax({
        type: 'post',
        url: '/vendor/payment_installments',
        dataType: 'json',
        cache: false,
        crossDomain: true,
        data: {
            card_number: $("input[name=card_number]").val(),
            payment_id: $("input[name=payment_id]").val(),
        }
    }).done(function (result) {

        console.log(result);
        if (result.status == 'success') {
            self.ratios = result.ratios;
            self.showInstallments();
            self.selectFirstInstallment();

            $("input[name=card_type]").val(result.brand);

            self.enablePayButton();

        } else {
            alert('Hata : ' + result.err);
        }
        return true;

    }).fail(function (xhr, textStatus, errorThrown) {

        var jsonErrorList = JSON.parse(xhr.responseJSON);
        return false;

    });
}

/**
 *
 */
VendorPayment.prototype.showInstallments = function () {

    var self = this;
    var total = $("input[name=total_payment]").val();
    var html = '';

    $.each(self.ratios, function (index, item) {
        var ratio = item.ratio.toFixed(2);
        html += `
            <div class="installment row">
                <div class="col-3">
                    <div class="d-none">
                        <input type="radio" value="` + item.installment + `" name="installment">
                    </div>
                    ` + item.installment + ` Taksit :
                </div>
                <div class="col-3">
                    ` + ratio + `
                </div>
                 <div class="col-3">
                    ` + (((10 + ratio) * total) / 100).toFixed(2) + ` TL
                </div>
                <div class="col-3">
                    <div class="is_selected d-none text-success">
                        <i class="fas fa-check"></i> Selected
                    </div>
                </div>
            </div>`;
    });

    $("#installment_container").html(html);

}

/**
 * Check first installment
 */
VendorPayment.prototype.selectFirstInstallment = function () {
    $("#installment_container .installment:first").trigger('click');
}

/**
 *
 */
VendorPayment.prototype.emptyInstallmentContainer = function () {
    $('#installment_container').html('');
}

/**
 *
 */
VendorPayment.prototype.unselectAllInstallments = function () {
    $('#installment_container .installment').removeClass('dark');
    $('#installment_container .installment .is_selected').addClass('d-none');
}

/**
 *
 * @param item ->  .installment element
 */
VendorPayment.prototype.selectInstallment = function (item) {
    item.find('input[name=installment]').attr('checked', true);
    item.addClass('dark');
    item.find('.is_selected').removeClass('d-none');
}

/**
 * Checks if valid or not and enables button
 */
VendorPayment.prototype.isValid = function () {
    var parameters = ['total_payment', 'installment', 'card_number', 'card_name', 'card_cvv'];
    $.each(parameters, function (index, val) {
        if (typeof $("input[name=" + val + "]").val() == 'undefined') {
            console.log('Not defined : ' + val)
            return false
        }
    });
    let totalPayment = parseFloat($("input[name=total_payment]").val());

    if (  totalPayment == 0 || isNaN(totalPayment)) {
        console.warn('total_payment ');
        return false;
    }
    if (parseInt($("input[name=installment]").val()) == 0) {
        console.warn('installment');
        return false;
    }
    if ($("input[name=card_number]").val().length < 15) {
        console.warn('card_number');
        return false;
    }
    if ($("input[name=card_name]").val().length < 5) {
        console.warn('card_name');
        return false;
    }
    if ($("input[name=card_cvv]").val().length < 3 || $("input[name=card_cvv]").val().length > 4) {
        console.warn('card_cvv');
        return false;
    }
    return true;
}

/**
 *
 */
VendorPayment.prototype.enablePayButton = function () {

    if (this.isValid()) {
        $(".disable_on_start").attr('disabled', false);
    } else {
        $(".disable_on_start").attr('disabled', true);
    }
}

export {
    VendorPayment
}
