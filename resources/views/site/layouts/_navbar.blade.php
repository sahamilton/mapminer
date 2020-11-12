<nav class="flex w-100 h-30" >

  <a href="{{ route('welcome') }}" class="pt-4 pl-4"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164' ></a> <!--     <nav class="navbar navbar-toggleable-sm navbar-light bg-faded">
<nav class="navbar navbar-toggleable-sm navbar-light bg-faded"> -->
   
  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon" style="color:white"></span>
    </button>


  <!-- Navbar links -->
  <div class="flex justify-between items-center w-100 h-100 font-sans" id="collapsibleNavbar">
    <ul class="flex justify-around items-center mt-2 ml-12 h-20 w-50"> 
     @if (! auth()->check())
            <li class="nav-item" >Welcome</li>
                                     
    @else
    
      <li class="flex justify-center items-center">
        <button type="button" class="text-lg text-gray-600 group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span>Search</span>
            <svg class="text-gray-400 h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
        <div class="dropdown-menu p-3 top-12" aria-labelledby="navbarDropdownMenuLink">
          
          <a class="dropdown-item p-2" href="{{{ route('findme') }}}">
          <i class="far fa-map" aria-hidden="true"> </i> Maps</a>
        
          <a class="dropdown-item p-2" href="{{{ route('company.index') }}}">
          <i class="far fa-building" aria-hidden="true"> </i> Accounts</a>
         
          <a class="dropdown-item p-2" href="{{{ route('branches.map') }}}">
          <i class="fab fa-pagelines"></i> Branches</a>
          <a class="dropdown-item p-2" href="{{route('salesorg.index')}}">
                <i class="fas fa-sitemap" aria-hidden="true"> </i>
                 People</a>
          @if(auth()->user()->hasRole('branch_manager'))
          <a class="dropdown-item" href="{{route('search.leads')}}">
                <i class="fas fa-arrow-right" aria-hidden="true"> </i>
                 Leads</a>
          @endif
          
        </div>
        </li>

        <li class="nav-item dropdown">
        
         <button type="button" class="text-base text-gray-600 group inline-flex items-center space-x-2 leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span>My Activity</span>
            <svg class="text-gray-400 h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          <div class="dropdown-menu p-3" aria-labelledby="navbarDropdownMenuLink">
                    
              <a class="dropdown-item p-2" 
                href="{{{ route('dashboard.index') }}}">
                <i class="fas fa-tachometer-alt"></i> 
                My Dashboard
              </a>
              <a class="dropdown-item p-2" 
                  href="{{{ route('branchcampaigns.index') }}}">
                 <i class="fas fa-chart-line"></i>
                  My Sales Initiatives</a> 
              
              <a class="dropdown-item p-2" href="{{{ route('training.index') }}}">
              <i class="fas fa-graduation-cap" aria-hidden="true"></i>
              Mapminer Training</a>

              
             
            </div>
          </li>

        <li class="nav-item dropdown">
        <button type="button" class="text-gray-600 group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span>Sales Resources</span>
            <svg class="text-gray-400 h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
            <div class="dropdown-menu p-3" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('salesorg.index')}}">
                <i class="fas fa-sitemap" aria-hidden="true"> </i>
                  Sales Organization</a>
                  
                  
                  
                  
                @can ('manage_opportunities')
                    
                    <a class="dropdown-item" href="{{route('branch.leads')}}">
                    <i class="fas fa-arrow-right"></i> Branch Leads</a>
                   <a class="dropdown-item p-2"  href="{{route('opportunity.index')}}">
                    <i class="far fa-envelope" aria-hidden="true"> </i> 
                    Branch Opportunities</a>
                    
                    <a class="dropdown-item p-2" href="{{route('contacts.index')}}">
                    <i class="far fa-address-card"></i> Branch Contacts</a>
                     <a class="dropdown-item p-2" href="{{ route('activity.index') }}">
                    <i class="far fa-calendar-alt"></i> Branch Activities</a>
                     <a class="dropdown-item p-2" href="{{ route('orders.index') }}">
                    <i class="far fa-calendar-alt"></i> Branch Accounts</a>
                  @endCan

                  @if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('national_account_manager'))
                    
                    <a class="dropdown-item p-2" href="{{route('managers.view')}}">
                    <i class="far fa-eye" aria-hidden="true"> </i> 
                    Account Managers View</a>
                  @endif
                  
                  

                </div>
              
            </li> 
             
              @if(auth()->user()->hasRole('branch_manager'))
              
                @include('branchleads.partials._searchbar')
              
              
              <li class="nav-item">
                  <a  class="nav-link" 
                  href="#"
                  data-href="" 
                  data-toggle="modal" 
                  data-target="#add_lead" >
                      <i class="fas fa-plus" style="color:green"> </i> Add Lead</a>
                  </li>

              @include('branchleads.partials._mylead')
              @endif          
          </ul>

          @include('site.layouts.partials._rightnav')
               
          <!-- ./ nav-collapse -->
        </div>
    @endif
      </li>
    </ul>

</nav>
