
 <nav class="navbar navbar-expand-lg navbar-inverse"> 
   <a href="{{ route('findme') }}"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164' class="navbar-brand"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav mr-auto">
     @if (!Auth::check())
            <li class="nav-item" >Welcome</li>
                       
                     
    @else
    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-search" aria-hidden="true"></i> Search<span class="caret"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          
          <a class="dropdown-item" href="{{{ route('findme') }}}">
          <i class="fa fa-map-o" aria-hidden="true"> </i> Maps</a>
        
          <a class="dropdown-item" href="{{{ route('company.index') }}}">
          <i class="fa fa-building-o" aria-hidden="true"> </i> Accounts</a>
         
          <a class="dropdown-item" href="{{{ route('branches.map') }}}">
          <i class="fa fa-shopping-bag" aria-hidden="true"> </i> Branches</a>
          
          <a class="dropdown-item" href="{{{ route('person.index') }}}">
          <i class="fa fa-users" aria-hidden="true"> </i> People</a>
          @can('view_projects')
              <a class="dropdown-item" href="{{{ route('projects.index') }}}">
              <i class="fa fa-flag" aria-hidden="true"> </i> Projects</a>
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
              <i class ="fa fa-eye"></i> My Watch List</a>
              
              <a class="dropdown-item" href="{{{ route('mynotes') }}}">
              <i class="fa fa-folder-open-o" aria-hidden="true"></i>
              My Notes</a>
              
              <a class="dropdown-item" href="{{{ route('training.index') }}}">
              <i class="fa fa-graduation-cap" aria-hidden="true"></i>
              Mapminer Training</a>

            </div>
          </li>

        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Sales Resources<span class="caret"></span>
                            </a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('salesorg')}}">
                <i class="fa fa-sitemap" aria-hidden="true"> </i>
                  Sales Organization</a>
                  @if(auth()->user()->hasRole('Admin') or Auth::user()->hasRole('Sales') or  Auth::user()->hasRole('Sales Manager'))
                            
                  <a class="dropdown-item" href="{{route('resources.view')}}">
                  <i class="fa fa-book" aria-hidden="true"> </i>
                      Sales Library</a>
                  <a class="dropdown-item" href="{{route('salescampaigns')}}">
                  <i class="fa fa-calendar-check-o" aria-hidden="true"> </i> 
                      Sales Campaigns</a>

                  @endif    
                  
                  @if(auth()->user()->can('accept_leads') or auth()->user()->can('manage_leads'))
                    <a class="dropdown-item"  
                        href="{{route('salesrep.newleads',auth()->user()->person->id)}}">
                    <i class="fa fa-envelope-open-o" aria-hidden="true"> </i> 
                      Sales Prospects</a>
                  @endif
                  
                  @if(auth()->user()->hasRole('Branch Manager'))
                    <a class="dropdown-item"  href="{{route('branchmanager.newleads')}}">
                    <i class="fa fa-envelope-open-o" aria-hidden="true"> </i> 
                    Branch Prospects</a>
                  @endif

                  @if (auth()->user()->hasRole('Admin') or Auth::user()->hasRole('National Account Manager'))
                    
                    <a class="dropdown-item" href="{{route('managers.view')}}">
                    <i class="fa fa-eye" aria-hidden="true"> </i> 
                    Account Managers View</a>
                  @endif
                  
                  @can('manage_projects')
                   
                  <a class="dropdown-item" href="{{route('projects.myprojects')}}">
                  <i class="fa fa-flag" aria-hidden="true"> </i> 
                  My Construction projects</a>
                  @endcan

                  @can('manage_prospects')
                           
                  @endcan
                </div>
              
            </li>    
                        
          </ul>
          @incude('site.layouts.partials._rightnav')
               
          <!-- ./ nav-collapse -->
        </div>
@endif
      </li>
    </ul>
  </div>
</nav>