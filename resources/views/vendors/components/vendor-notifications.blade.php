
<li class="nav-item dropdown no-arrow mx-1" data-notifications="{{ $jsonNotifications }}">
    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <span class="badge badge-danger badge-counter badge-notificaitons">0</span>
    </a>
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
         aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
            Alerts Center
        </h6>
        <div id="notification_container" class="alert_container"></div>
        <a class="dropdown-item text-center small text-gray-500" href="{{ route('vendor_notifications') }}">Show All Alerts</a>
    </div>
</li>
