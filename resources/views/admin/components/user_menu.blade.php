<!-- Nav Item - User Information -->
<li class="nav-item dropdown no-arrow">

    <span class="d-none" id="user_data" data-data="{{ json_encode($user) }}"></span>

    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <img class="img-profile rounded-circle" src="{{ asset($user->avatar) }}">
    </a>

    <!-- Dropdown - User Information -->
    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <div>
            <span class="p-2"><b>{{ $user->full_name }}</b></span>
        </div>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('app.index', ['lang'=>$user->lang]) }}">
            <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
            {{ _('Home') }}
        </a>
        <a class="dropdown-item" href="{{ route('vendor') }}">
            <i class="fas fa-user-cog fa-sm fa-fw mr-2 text-gray-400"></i>
            {{ _('Vendor Panel') }}
        </a>
        <a class="dropdown-item" href="{{ route('app.dashboard') }}">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
            {{ _('Profile') }}
        </a>
        <a class="dropdown-item" href="{{ route('app.dashboard') }}">
            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
            {{ _('Settings') }}
        </a>

        <div class="dropdown-divider"></div>

        <form action="{{ route('logout') }}" method="post">
            <button class="dropdown-item">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                {{ _('Logout') }}
            </button>
            @csrf
        </form>
    </div>
</li>
