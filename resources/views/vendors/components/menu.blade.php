<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
       aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>{{ _('Orders') }}</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">{{ _('Orders') }} :</h6>
            <a class="collapse-item" href="{{ ifExistRoute('vendor_orders') }}">
                <b>{{ _('All Orders') }}</b>
            </a>
            @foreach($orderStatusess as $status)
                <a class="collapse-item mb-1" href="{{ ifExistRoute('vendor_orders', ['id'=>$status->id]) }}">
                    <div
                        style="background:#{{ $status->color }};display: inline-block;width:3px;height:1em;margin-right:10px"></div>
                    {{ $status->name }} @if($status->total>0) <span
                        class="badge badge-pill badge-danger float-right">{{ $status->total }}</span> @endif
                </a>
            @endforeach

            <hr class="menu-divider">

            <a class="collapse-item  open-modal" data-onload="vendorOrderModal"
               data-url="{{ route('vendor_orders.create') }}">
                {{ _('New Order') }}
            </a>
            <a class="collapse-item" href="{{ ifExistRoute("vendor_order_status") }}">
                {{ _('Order Statusses') }}
            </a>
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseShipments"
       aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-truck-loading"></i>
        <span>Gönderiler</span>
    </a>
    <div id="collapseShipments" class="collapse" aria-labelledby="headingShipments"
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ ifExistRoute("vendor.packages", ['type'=>'all']) }}">Paketlerim</a>
            <h6 class="collapse-header">Gönderiler:</h6>
            <a class="collapse-item" href="{{ ifExistRoute("vendor.packages", ['type'=>'confirmed']) }}">Onaylı
                Gönderiler</a>
            <a class="collapse-item" href="{{ ifExistRoute("vendor.packages", ['type'=>'waiting']) }}">Onay
                Bekleyenler</a>
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBalance"
       aria-expanded="true" aria-controls="collapseBalance">
        <i class="fas fa-coins"></i>
        <span>{{ _('Balance Payment') }}</span>
    </a>
    <div id="collapseBalance" class="collapse" aria-labelledby="headingBalance"
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ ifExistRoute("vendor_payment_account.index") }}">
                {{ _('Payment Summary') }}
            </a>
            <a class="collapse-item" href="{{ ifExistRoute("vendor.shipping_prices") }}">
                {{ _('Shipping Prices') }}
            </a>
        </div>
    </div>
</li>

<hr class="sidebar-divider my-0">

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
       aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>{{ _('Settings') }}</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">{{ _('General Settings') }} :</h6>
            <a class="collapse-item" href="{{ url("vendor") }}">Gönderi Durumları</a>
        </div>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseEntegrations"
       aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-list"></i>
        <span>Entegrasyonlar</span>
    </a>
    <div id="collapseEntegrations" class="collapse" aria-labelledby="headingEntegrations"
         data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Entegrasyon Listesi:</h6>
            @foreach($orderServices as $entegration_id=>$entegrationData)
                <div class="collapse-item open-modal"  data-url="{{ route('vendor_entegration_data.show', $entegration_id ) }}">

                    <img src="{{ asset('img/entegrations/'.$entegrationData['entegration_id'].".png") }}"
                         class="vendor-img-sm">
                    {{ $entegrationData['name'] }}
                </div>
            @endforeach
            <h6 class="collapse-header">Yeni Entegrasyon:</h6>
            <a class="collapse-item" href="{{ ifExistRoute("vendor_entegrations") }}">Yeni ekle</a>
        </div>
    </div>
</li>
