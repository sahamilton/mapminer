<li>
    <a class="dropdown-item dropdown-toggle" href="#">
        <i class="fas fa-users-cog"></i> Users</a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" 
            href="{{ route('users.create') }}">
                <i class="fas fa-user-plus"></i> Create User
            </a>
        </li>
       
        <li>
            <a class="dropdown-item" 
            href="{{ route('users.index') }}">
                <i class="fas fa-users"></i> All Users
            </a>
        </li>
        <li>
            <a class="dropdown-item" 
            href="{{ route('managers.livewire') }}">
                <i class="fas fa-tasks"></i> Management Teams
            </a>
        </li>

        <div class="dropdown-divider"></div>
        <li>
            <a class="dropdown-item" 
            href="{{ route('roles.index') }}">
                <i class="fas fa-wrench"></i> Roles
            </a>
        </li>
        
        <li>
            <a class="dropdown-item" 
            href="{{ route('permissions.index') }}">
                <i class="fas fa-check-double"></i> Permissions
            </a>
        </li>
        
        
    </ul>
</li>