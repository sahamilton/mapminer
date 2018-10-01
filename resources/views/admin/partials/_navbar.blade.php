<nav class="navbar navbar-expand-md navbar-light bg-light">
        
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="color:white"></span>
    </button>
        <a href="{{ route('findme') }}"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164' class="navbar-brand"></a>
          <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" 
                    id="navbarDropdownMenuLink" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="false">
                        Data Management
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @can('manage_accounts')
                            @include('admin.partials.nav._accountsnav')
                        @endcan
                        @can('manage_branches')
                            @include('admin.partials.nav._branchesnav')
                        @endcan
                        @can('manage_users')
                            @include('admin.partials.nav._usersnav')
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class="fas fa-recycle"></i> Import / Export
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" 
                    href="#" id="navbarDropdownMenuLink" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="false">
                        Resources</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                       @include('admin.partials.nav._resources')
                        
                    </ul>
                </li>

            </ul>
            
                <input 
                id="search" 
                placeholder="Type to search users" 
                autocomplete="off"
                class="form-control mr-sm-2" 
                type="search" 
                aria-label="Search">

            @include('admin.partials.nav._rightnav')
        </div>
        
    </div>

</nav>


 