<div class="col-lg-2">
    <div class="dashboard_menu">
        <ul>
            <li >
                <a class="{{ (request()->is('user/dashboard')) ? 'active' : '' }}" href="{{ route('client_home') }}">Profile</a>
            </li>
            <li >
                <a class="{{ (request()->is('user/my-courses')) ? 'active' : '' }}" href="{{ route('client_Courses') }}">My Courses</a>
            </li>
            <li >
                <a class="{{ (request()->is('user/live-events')) ? 'active' : '' }}" href="{{ route('client_events') }}">Live Events</a>
            </li>

            <li >
                <a href="{{ route('logout') }}">Sign Out</a>
            </li>
        </ul>
    </div>
</div>
