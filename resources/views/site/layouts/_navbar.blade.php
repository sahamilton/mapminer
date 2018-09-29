<nav class="navbar navbar-expand-md navbar-static-top navbar-light">
  <!--     <nav class="navbar navbar-toggleable-sm navbar-light bg-faded">
<nav class="navbar navbar-toggleable-sm navbar-light bg-faded"> -->
   
  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="color:white"></span>
    </button>

<a href="{{ route('findme') }}" class="navbar-brand"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164' ></a> 
  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav mr-auto"> 
     @if (! auth()->check())
            <li class="nav-item" >Welcome</li>
                                     
    @else
    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="far fa-search" aria-hidden="true"></i> Search<span class="caret"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          
          <a class="dropdown-item" href="{{{ route('findme') }}}">
          <i class="far fa-map" aria-hidden="true"> </i> Maps</a>
        
          <a class="dropdown-item" href="{{{ route('company.index') }}}">
          <i class="far fa-building" aria-hidden="true"> </i> Accounts</a>
         
          <a class="dropdown-item" href="{{{ route('branches.map') }}}">
          <i class="fab fa-pagelines"></i> Branches</a>
          
          <a class="dropdown-item" href="{{{ route('person.index') }}}">
          <i class="fas fa-users" aria-hidden="true"> </i> People</a>
          @can('view_projects')
              <a class="dropdown-item" href="{{{ route('projects.index') }}}">
              <i class="far fa-flag" aria-hidden="true"> </i> Projects</a>
          @endcan
                           
          
        </div>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          My Activity
          <span class="caret"></span>
                            </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    
              <a class="dropdown-item" href="{{{ route('watch.index') }}}">
              <i class ="far fa-eye"></i> My Watch List</a>
              
              <a class="dropdown-item" href="{{{ route('mynotes') }}}">
              <i class="fas fa-folder-open" aria-hidden="true"></i>
              My Notes</a>
              
              <a class="dropdown-item" href="{{{ route('training.index') }}}">
              <i class="fas fa-graduation-cap" aria-hidden="true"></i>
              Mapminer Training</a>

              @can('service_branches')
              <a class="dropdown-item" href="{{{ route('branchassignments.show',auth()->user()->id) }}}">
              <i class="fas fa-search-location"></i> My Branch Assignments</a>
              @endcan
            </div>
          </li>

        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Sales Resources<span class="caret"></span>
                            </a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('salesorg')}}">
                <i class="fas fa-sitemap" aria-hidden="true"> </i>
                  Sales Organization</a>
                  @if(auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales') or  auth()->user()->hasRole('Sales Manager'))
                            
                  <a class="dropdown-item" href="{{route('resources.view')}}">
                  <i class="fas fa-book" aria-hidden="true"> </i>
                      Sales Library</a>
                  <a class="dropdown-item" href="{{route('salescampaigns')}}">
                  <i class="fas fa-calendar-check-o" aria-hidden="true"> </i> 
                      Sales Campaigns</a>

                  @endif    
                  
                  @if(auth()->user()->can('accept_leads') or auth()->user()->can('manage_leads'))
                    <a class="dropdown-item"  
                        href="{{route('salesrep.newleads',auth()->user()->person->id)}}">

                    <i class="fas fa-envelope" aria-hidden="true"> </i> 
                      Sales Prospects</a>
                  @endif
                  
                  @if(auth()->user()->hasRole('Branch Manager'))
                    <a class="dropdown-item"  href="{{route('branchmanager.newleads')}}">
                    <i class="far fa-envelope-open-o" aria-hidden="true"> </i> 
                    Branch Prospects</a>
                  @endif

                  @if (auth()->user()->hasRole('Admin') or auth()->user()->hasRole('National Account Manager'))
                    
                    <a class="dropdown-item" href="{{route('managers.view')}}">
                    <i class="far fa-eye" aria-hidden="true"> </i> 
                    Account Managers View</a>
                  @endif
                  
                  @can('manage_projects')
                   
                  <a class="dropdown-item" href="{{route('projects.myprojects')}}">
                  <i class="far fa-flag" aria-hidden="true"> </i> 
                  My Construction projects</a>
                  @endcan

                  @can('manage_prospects')
                           
                  @endcan
                </div>
              
            </li>    
                        
          </ul>
          @include('site.layouts.partials._rightnav')
               
          <!-- ./ nav-collapse -->
        </div>
    @endif
      </li>
    </ul>

</nav>

