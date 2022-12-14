<nav class="navbar navbar-expand-md ml-auto navbar-light" >

  <a href="{{ route('welcome') }}" class="navbar-brand"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164' ></a> <!--     <nav class="navbar navbar-toggleable-sm navbar-light bg-faded">
<nav class="navbar navbar-toggleable-sm navbar-light bg-faded"> -->
   
  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="color:white"></span>
    </button>


  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav mr-auto"> 
     @if (! auth()->check())
            <li class="nav-item" >Welcome</li>
                                     
    @else
    
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-search" aria-hidden="true"></i> Search<span class="caret"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          
          <a class="dropdown-item" href="{{{ route('findme') }}}">
            <i class="far fa-map" aria-hidden="true"> </i> Maps</a>
        
          <a class="dropdown-item" href="{{{ route('company.index') }}}">
            <i class="far fa-building" aria-hidden="true"> </i> Accounts</a>

          <a class="dropdown-item" href="{{route('lead.list')}}">
            <i class="fa-solid fa-map-pin"></i>
                 Locations</a>
          <a class="dropdown-item" href="{{{ route('branches.map') }}}">
            <i class="fab fa-pagelines"></i> Branches</a>
          <a class="dropdown-item" href="{{route('nearby.show', 'people')}}">
            <i class="fa-solid fa-people-group"> </i>
                 People</a>
         
        </div>
        </li>
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          My Activity
          <span class="caret"></span>
                            </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    
              <a class="dropdown-item" 
                href="{{{ route('mydashboard') }}}">
                <i class="fas fa-tachometer-alt"></i> 
                My Dashboard
              </a>
              <a class="dropdown-item" 
                href="{{{ route('reports.index') }}}">
                <i class="fa-solid fa-file-excel"></i>
                Reports
              </a>
              
              <a class="dropdown-item" 
                  href="{{{ route('branchcampaigns.index') }}}">
                 <i class="fas fa-chart-line"></i>
                  My Sales Campaigns</a> 
              
              <a class="dropdown-item" href="{{{ route('training.index') }}}">
              <i class="fas fa-graduation-cap" aria-hidden="true"></i>
              Mapminer Training</a>

              
             
            </div>
          </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Sales Resources<span class="caret"></span>
                            </a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('salesorg.index')}}">
                <i class="fas fa-sitemap" aria-hidden="true"> </i>
                  Sales Organization</a>
               
                @can ('manage_opportunities')
                    
                    <a class="dropdown-item" href="{{route('branch.leads')}}">
                      <i class="far fa-envelope" aria-hidden="true"></i> 
                        Branch Leads / Customers
                    </a>

                    <a class="dropdown-item"  href="{{route('opportunity.index')}}">
                      <i class="fa-solid fa-money-check-dollar" aria-hidden="true"></i> 
                        Branch Opportunities
                      </a>
                    
                    <a class="dropdown-item" href="{{route('contacts.index')}}">
                      <i class="far fa-address-card" aria-hidden="true"></i> 
                        Branch Contacts
                    </a>
                    <a class="dropdown-item" href="{{ route('activity.index') }}">
                      <i class="far fa-calendar-alt"></i>
                        Branch Activities
                      </a>

                    <a class="dropdown-item" href="{{ route('branch.requirements') }}">
                      <i class="fa-solid fa-person-digging"></i>
                        Branch Staff Requirements
                    </a>

                  @endCan

                  @if (auth()->user()->hasRole(['admin','sales_ops', 'national_account_manager']))
                    
                    <a class="dropdown-item" href="{{route('managers.view')}}">
                    <i class="far fa-eye" aria-hidden="true"> </i> 
                    Account Managers View</a>
                  @endif
                  
                  

                </div>
              
            </li> 
          </ul>   
              @if(auth()->user()->hasRole(['branch_manager', 'staffing_specialist', 'market_manager']))
              
                @include('branchleads.partials._searchbar')
              
              
                 <div>
                  <button class="btn btn-success"  
                    href="#"
                    data-href="" 
                    data-toggle="modal" 
                    data-target="#add_lead" >
                      <i class="fas fa-plus text-white"> </i>  Add Lead </button>
                </div>

                @include('branchleads.partials._mylead')
              @endif


          

          @include('site.layouts.partials._rightnav')
               
          <!-- ./ nav-collapse -->
        </div>
    @endif
      </li>
    </ul>

</nav>
