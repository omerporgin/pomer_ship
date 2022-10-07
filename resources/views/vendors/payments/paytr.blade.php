<h2>{{ $redirect_message }}</h2>
<form id="payment_form" action="https://www.paytr.com/odeme" method="post" name="payment_form">
    Card Holder Name: <input type="text" name="cc_owner" value="{{ $card_name }}">
    Card Number: <input type="text" name="card_number" value="{{ $card_number }}">
    Card Expiration Month: <input type="text" name="expiry_month"
                                  value="{{ $expiry_month }}">
    Card Expiration Year: <input type="text" name="expiry_year" value="{{ $expiry_year }}">
    Card Security Code: <input type="text" name="cvv" value="{{ $card_cvv }}">

    merchant_id: <input type="text" name="merchant_id" value="{{ $processorData->merchant_id }}">
    user_ip: <input type="text" name="user_ip" value="{{ $user_ip}}">
    merchant_oid: <input type="text" name="merchant_oid" value="{{ $merchant_oid }}">
    email: <input type="text" name="email" value="{{ $email }}">
    payment_type: <input type="text" name="payment_type" value="{{ $payment_type }}">
    payment_amount: <input type="text" name="payment_amount" value="{{ $payment_amount }}">
    currency: <input type="text" name="currency" value="{{ $currency }}">
    test_mode: <input type="text" name="test_mode" value="{{ $test_mode }}">
    non_3d: <input type="text" name="non_3d" value="{{ $non_3d }}">

    merchant_ok_url: <input type="text" name="merchant_ok_url" value="{{ url($merchant_ok_url) }}">
    merchant_fail_url: <input type="text" name="merchant_fail_url" value="{{ url($merchant_fail_url) }}">

    user_name: <input type="text" name="user_name" value="{{ $user_name }}">
    user_address: <input type="text" name="user_address" value="{{ $user_address }}">
    user_phone: <input type="text" name="user_phone" value="{{ $user_phone }}">
    user_basket: <input type="text" name="user_basket" value="{{ $user_basket }}">
    debug_on: <input type="text" name="debug_on" value="1">
    client_lang: <input type="text" name="client_lang" value="{{ $client_lang }}">
    paytr_token: <input type="text" name="paytr_token" value="{{ $token }}">
    non3d_test_failed: <input type="text" name="non3d_test_failed" value="{{ $non3d_test_failed }}">
    installment_count: <input type="text" name="installment_count" value="{{ $installment_count }}">
    card_type: <input type="text" name="card_type" value="{{ $card_type }}">
    store_card: <input type="checkbox" name="store_card" @if($store_card=='Y') checked @endif>
    <input type="submit" value="Submit">

</form>
<style>

    body {
        font-family: Verdana, Arial, Helvetica, sans-serif;
    }

    h2 {
        text-align: center;
        margin-top: 15%;
    }

    form {
        display: none;
    }

    input {
        width: 100%;
    }
</style>
