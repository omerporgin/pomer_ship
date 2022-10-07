<div id="component_shipping_prices">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        @php($count=0)
        @foreach($serviceList as $service)
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab{{ $count }}-tab" data-toggle="tab"
                        data-target="#tab{{ $count }}"
                        type="button" role="tab" aria-controls="home" aria-selected="true">
                    {{ $service }}
                </button>
            </li>
            @php($count++)
        @endforeach
    </ul>

    <div class="tab-content" id="myTabContent">
        @php($count=0)
        @foreach($serviceList as $service)
            <div class="tab-pane fade" id="tab{{ $count }}" role="tabpanel" aria-labelledby="tab{{ $count }}-tab">

                <a href="{{ route('admin_download_shipping_prices', [
                    'id' => $id,
                    'service' => $service,
                ]) }}" class="btn btn-primary mb-3">
                    {{ _('Download Price JSon') }}
                </a>

                <div class="overflow-auto">

                    <table class="table table-striped" id="shipping-price-table">
                        <thead class="thead-dark">
                        <tr>
                            <th>Desi</th>
                            @foreach($regionList as $region)
                                <th>Zone - {{ $region }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($desiList as $desi)
                            <tr>
                                <td scope="row">{{ $desi }}</td>
                                @foreach($regionList as $region)
                                    <td>
                                        {{ $list[$service][$desi][$region]['price'] }}<span
                                            class="currency">{{ $list[$service][$desi][$region]['currency'] }}</span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

            @php($count++)
        @endforeach
    </div>

</div>
