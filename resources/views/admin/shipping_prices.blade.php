@extends( adminTheme('layout.admin'))

@section('top')

@endsection

@section('content')

    <div class="card shadow mb-4" id="admin_shipping_prices">
        <div class="card-header py-3">
            <img src="{{ asset($shipping->img()) }}" class="float-right" style="height:22px">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Shipping Prices') }} : <b>{{ $shipping->name }}</b></h6>

        </div>
        <div class="card-body">

            <x-ShippingPriceTable :id="$id" :userGroupId="null" />

        </div>
    </div>

@endsection
