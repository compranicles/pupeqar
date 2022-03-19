<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    <li class="nav-sub-menu">
                        <a class="text-dark {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">Users</a>
                    </li>
                    <li class="nav-sub-menu">
                        <a class="text-dark {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">Roles</a>
                    </li>
                    <li class="nav-sub-menu">
                        <a class="text-dark {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">Permissions</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>