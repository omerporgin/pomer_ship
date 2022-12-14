@extends( adminTheme().'.layout.admin')

@section('top')

@endsection

@section('content')

    <div class="card shadow mb-4" id="admin_shipping">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ _('Shippings') }}</h6>
        </div>
        <div class="card-body">

            <div class="table_container">
                <table id="shipping-table" class="table datatable-generated table-hover no-footer dataTable"
                       data-url="{{ ifExistRoute('admin.shippings.ajax') }}"
                       data-orderable="1,5,6"
                       data-success="adminShipping"
                       data-placeholder="{{ _('ID, Name') }}"
                       data-admin_update_shipping_zone="{{ ifExistRoute('admin_update_shipping_zone') }}"
                       data-admin_update_shipping_prices="{{ ifExistRoute('admin_update_shipping_prices') }}"
                       data-admin_shippin_prices="{{ ifExistRoute('admin_shipping_prices', ['id'=>'#']) }}"
                       data-data="{{ base64data([
                            'update_url' => route('admin_shippings.show', '###'),
                            'update_shipping_price_url' => route('admin_shipping_prices_update', [ 'shipping_id'=>'###']),
                       ]) }}">
                    <thead>
                    <tr>
                        <th>{{ _('ID') }}</th>
                        <th>{{ _('Image') }}</th>
                        <th>{{ _('Account') }}</th>
                        <th class="w-50">{{ _('Name') }}</th>
                        <th>{{ _('Update Zones') }}</th>
                        <th>{{ _('Update Prices') }}</th>
                        <th>{{ _('Prices') }}</th>
                        <th>{{ _('IS Active') }}</th>
                        <th>{{ _('Update') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                @php
                    $list = [
                        'Zone g??ncelleme.' => 'Zonelar kargo b??lgeleridir. Her kargo processoru i??in farkl??d??r.
                        Bu bilgi <b>app/Libraries/Shippings/Zones/Items/*</b> klas??r??nde yer al??r. Bu sayfada g??ncellendikten sonra update butonuna bas??lmal??d??r.'
                    ];
                @endphp

                <x-Accordion :list="$list"></x-Accordion>

            </div>
        </div>
    </div>

@endsection
