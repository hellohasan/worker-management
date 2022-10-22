<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>Dashboard</p>
    </a>
</li>

@can('new-hire')
    <li class="nav-item">
        <a href="{{ route('hire.new') }}" class="nav-link">
            <i class="nav-icon fas fa-shopping-bag"></i>
            <p>New Worker Order</p>
        </a>
    </li>
@endcan

@can('hire-history')
    <li class="nav-item">
        <a href="{{ route('hire.history') }}" class="nav-link">
            <i class="nav-icon fas fa-history"></i>
            <p>Order History</p>
        </a>
    </li>
@endcan

@can('categories')
    <li class="nav-item">
        <a href="{{ route('categories.index') }}" class="nav-link">
            <i class="nav-icon fas fa-th-large"></i>
            <p>Categories</p>
        </a>
    </li>
@endcan

@can('workers')
    <li class="nav-item">
        <a href="{{ route('workers.index') }}" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Manage Workers</p>
        </a>
    </li>
@endcan

@can('companies')
    <li class="nav-item">
        <a href="{{ route('companies.index') }}" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Manage Companies</p>
        </a>
    </li>
@endcan


@role('Super Admin|Admin')
    <li class="nav-header">General Settings</li>

    <li class="nav-item has-treeview">
        <a role="button" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>Basic Setting<i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('basic-content') }}" class="nav-link">
                    <i class="fas fa-cog nav-icon"></i>
                    <p>Basic Content</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logo-favicon') }}" class="nav-link">
                    <i class="far fa-image nav-icon"></i>
                    <p>Logo & Favicon</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="{{ route('system-information') }}" class="nav-link">
            <i class="nav-icon fas fa-server"></i>
            <p>System Information</p>
        </a>
    </li>

    <li class="nav-item has-treeview">
        <a role="button" class="nav-link">
            <i class="nav-icon fas fa-fingerprint"></i>
            <p>Role & Permission<i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link">
                    <i class="fas fa-user-secret nav-icon"></i>
                    <p>Manage Role</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('permissions.index') }}" class="nav-link">
                    <i class="fas fa-shield-alt nav-icon"></i>
                    <p>Manage Permission</p>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item has-treeview">
        <a role="button" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Manage Users<i class="right fas fa-angle-left"></i></p>
        </a>
        <ul class="nav nav-treeview">
            @can('users-create')
                <li class="nav-item">
                    <a href="{{ route('users.create') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Create User</p>
                    </a>
                </li>
            @endcan
            @can('users')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>User List</p>
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endrole

<li class="nav-item">
    <a href="{{ route('edit-profile') }}" class="nav-link">
        <i class="nav-icon fas fa-user-edit"></i>
        <p>Edit Profile</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('change-password') }}" class="nav-link">
        <i class="nav-icon fas fa-lock-open"></i>
        <p>Change Password</p>
    </a>
</li>
<li class="nav-item">
    <a href="##" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Sign Out</p>
    </a>
</li>
