@extends( vendorTheme().'.layout.admin' , [
    'header' => 'New Order',
    'header_desc' => '',
    'breadcrumb' => []
])

@section('top')


@endsection

@section('content')

    <div class="card shadow mb-4" id="vendor_payment">
        <div class="card-header py-3">
            <div class="row">
                <div class="col-12">
                    <h6 class="m-0 font-weight-bold text-primary">{{ _('Shipping Prices') }}</h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            <x-ShippingPriceTable :id="$id" :userGroupId="$userGroupId" />
        </div>
    </div>

@endsection
