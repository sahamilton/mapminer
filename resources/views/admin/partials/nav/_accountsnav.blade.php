<li>
    <a class="dropdown-item dropdown-toggle" href="#">Accounts</a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" 
            href="{{ route('company.index') }}">
                <i class="fas fa-wrench"></i> Manage Accounts
            </a>
        </li>
        <div class="dropdown-divider"></div>
        <li>
            <a class="dropdown-item" 
            href="{{route('serviceline.index')}}">
                <i class="fas fa-concierge-bell"></i> Manage Servicelines
            </a>
        </li>
        <li>
            <a class="dropdown-item" 
            href="{{ route('searchfilters.index') }}">
                <i class="fas fa-filter"></i> Manage Filters
            </a>
        </li>
        <div class="dropdown-divider"></div>
        <li>
            <a class="dropdown-item" 
            href="{{ route('salesnotes.index') }}"> 
                <i class="fas fa-pen-fancy"></i> Manage Sales Notes
            </a>
        </li>
        <li>
            <a class="dropdown-item" 
            href="{{ route('locations.index') }}">
            <i class="fas fa-flag-checkered"></i>Manage Location Notes
            </a>
        </li>
    </ul>
</li>