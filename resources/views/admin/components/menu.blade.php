@if( Gate::allows('order_see') )
    <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
       aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-cog"></i>
        <span>{{ _('Orders') }}</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">{{ _('Orders') }} :</h6>
            <a class="collapse-item" href="{{ route('admin_orders') }}">{{ _('Orders') }}</a>

            @foreach($orderStatusess as $status)
                <a class="collapse-item mb-1" href="{{ route('admin_orders', ['id' => $status->id]) }}">
                    <div style="background:#{{ $status->color }};display: inline-block;width:3px;height:1em;margin-right:10px"></div>
                    {{ $status->name }}
                </a>
            @endforeach

            <hr class="menu-divider">

            <a class="collapse-item" href="{{ ifExistRoute("admin.order_statusses") }}">
                {{ _('Order Statusses') }}
            </a>
        </div>
    </div>
</li>
@endif

@if( Gate::allows('user_see') )
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
       aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-users"></i>
        <span>{{ _('Users') }}</span>
    </a>
    <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">{{ _('Users') }} :</h6>
            <a class="collapse-item" href="{{ ifExistRoute("admin.users",['type'=>'all']) }}">{{ _('Users') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin.users",['type'=>'admins']) }}">{{ _('Admins') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_permissions.index") }}">{{ _('Permissions') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_gates") }}">{{ _('Gates') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_user_groups.index") }}">{{ _('User Groups') }}</a>
        </div>
    </div>
</li>
@endif

<hr class="sidebar-divider my-0">

@if( Gate::allows('setting_see') )
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
            <a class="collapse-item" href="{{ ifExistRoute("admin_languages.index") }}">{{ _('Languages') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_currencies.index") }}">{{ _('Currencies') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_localization.index") }}">{{ _('Localization') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_gtip.index") }}">{{ _('Gtip Data') }}</a>

            <hr class="menu-divider">

            <a class="collapse-item" href="{{ ifExistRoute("admin_shippings.index") }}">{{ _('Shippings') }}</a>

            <hr class="menu-divider">

            <a class="collapse-item" href="{{ ifExistRoute("admin_location_country.index") }}">{{ _('Location country') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_location_state.index") }}">{{ _('Location state') }}</a>
            <a class="collapse-item" href="{{ ifExistRoute("admin_location_city.index") }}">{{ _('Location city') }}</a>
        </div>
    </div>
</li>
@endif

@if( Gate::allows('content_see') )
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBlog"
       aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-users"></i>
        <span>{{ _('Blog') }}</span>
    </a>
    <div id="collapseBlog" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">{{ _('Blog') }} :</h6>
            <a class="collapse-item" href="{{ ifExistRoute('admin_blogs.index') }}">{{ _('Contents') }}</a>
        </div>
    </div>
</li>
@endif
