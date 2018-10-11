<<<<<<< HEAD
<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top">
        <div class="logo" >
        
        <a href="{{ route('findme') }}"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164'/></a>
        </div>
			 <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                     @if (!Auth::check())
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ route('welcome') }}}">Welcome</a>
                       
                       </li>
                     @else
                        <li class="dropdown{{ (Request::is('company*','branch*' ,'person*','findme') ? ' active' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ route('findme') }}}">
    							<i class="fa fa-search" aria-hidden="true"></i> Search<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            <li {{ (Request::is('findme') ? ' class="active"' : '') }}><a href="{{{ route('findme') }}}">
                            <i class="fa fa-map-o" aria-hidden="true"> </i> Maps</a></li>
                            <li {{ (Request::is('company*') ? ' class="active"' : '') }}><a href="{{{ route('company.index') }}}">
                            <i class="fa fa-building-o" aria-hidden="true"> </i> Accounts</a></li>
                            <li {{ (Request::is('branch*') ? ' class="active"' : '') }}><a href="{{{ route('branches.map') }}}">
                            <i class="fa fa-shopping-bag" aria-hidden="true"> </i> Branches</a></li>
                            <li {{ (Request::is('person*') ? ' class="active"' : '') }}><a href="{{{ route('person.index') }}}">
                            <i class="fa fa-users" aria-hidden="true"> </i> People</a></li>
                            @can('view_projects')
                                <li {{ (Request::is('project*') ? ' class="active"' : '') }}><a href="{{{ route('projects.index') }}}">
                                <i class="fa fa-flag" aria-hidden="true"> </i> Projects</a></li>
                            @endcan
                            </ul>
                            </li>
                        <li class="dropdown{{ (Request::is('watch*','mynote*') ? ' active' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" >
                         My Activity<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                        <li {{ (Request::is('watch') ? ' class="active"' : '') }}><a href="{{{ route('watch.index') }}}">
                        <i class ="fa fa-eye"></i> My Watch List</a></li>
                         <li {{ (Request::is('mynotes') ? ' class="active"' : '') }}><a href="{{{ route('mynotes') }}}">
                        <i class="fa fa-folder-open-o" aria-hidden="true"></i>
                         My Notes</a>
                         <li {{ (Request::is('mytraining') ? ' class="active"' : '') }}><a href="{{{ route('training.index') }}}">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                         Mapminer Training</a>
                     </li>
                        </li>
                    </ul>
                </li>
                        <li class="dropdown{{ (Request::is('salesorg*','resources*') ? ' active' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" >
                         Sales Resources<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                        <li><a href="{{route('salesorg')}}">
                        <i class="fa fa-sitemap" aria-hidden="true"> </i>
                        Sales Organization</a></li>
                        @if(auth()->user()->hasRole('Admin') or Auth::user()->hasRole('Sales') or  Auth::user()->hasRole('Sales Manager'))
                            <li class="divider"></li>
                            <li><a href="{{route('resources.view')}}">
                            <i class="fa fa-book" aria-hidden="true"> </i>
                             Sales Library</a></li>
                            <li><a href="{{route('salescampaigns')}}">
                            <i class="fa fa-calendar-check-o" aria-hidden="true"> </i> Sales Campaigns</a></li>

                        @endif 
                         
                        @if(auth()->user()->can('accept_leads') or auth()->user()->can('manage_leads'))
                            <li><a href="{{route('salesrep.newleads',auth()->user()->person->id)}}">
                            <i class="fa fa-envelope-open-o" aria-hidden="true"> </i> Sales Prospects</a></li>
                        @endif
                        @if(auth()->user()->hasRole('Branch Manager'))
                            <li><a href="{{route('branchmanager.newleads')}}">
                            <i class="fa fa-envelope-open-o" aria-hidden="true"> </i> Branch Prospects</a></li>
                        @endif
                        @if (auth()->user()->hasRole('Admin') or Auth::user()->hasRole('National Account Manager'))
                        <li class="divider"></li>
                         <li><a href="{{route('managers.view')}}">
                         <i class="fa fa-eye" aria-hidden="true"> </i> Account Managers View</a></li>
                        
                        
                        @endif
                        @can('manage_projects')
                         <li class="divider"></li>
                         <li><a href="{{route('projects.myprojects')}}">
                         <i class="fa fa-flag" aria-hidden="true"> </i> My Construction projects</a></li>

                        @endcan
                        @can('manage_prospects')
                            <li class="divider"></li>
                            
                        @endcan
                        </ul>

                         
                        
					</ul>
                
					<ul class="nav navbar-nav pull-right">
 						<?php $news = new \App\News;?>
                        @if($news->currentNews()->count()>0)
                        <li>
                            <a href="{{route('currentnews')}}">
                            <i class="fa fa-bell-o" aria-hidden="true"> </i> News</a>


                        </li>
                        @endif
                        @if (Auth::user()->hasRole('Admin'))
                         <li>
    						<a href="{{{ route('dashboard') }}}">
                           <i class="fa fa-tachometer" aria-hidden="true"> </i> Admin </a>
    						
    					</li>
                        @endif

                         @if (Auth::user()->hasRole('Sales Operations'))
                         <li>
                            <a href="{{{ route('dashboard') }}}">
                           <i class="fa fa-tachometer" aria-hidden="true"> </i> Ops </a>
                            
                        </li>
                        @endif
    					<li class="divider-vertical"></li>
    					<li class="dropdown">
    							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
    								<i class="fa fa-user" aria-hidden="true"></i> {{ucfirst(strtolower( Auth::user()->person->firstname ))}}	<span class="caret"></span>
    							</a>
    							<ul class="dropdown-menu">
    								<li>
                                        <a href="{{{ route('user.show',auth()->user()->id) }}}">
                                            <i class="fa fa-user" aria-hidden="true"> </i> Your Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('about')}}">
                                            <i class="fa fa-info-circle" aria-hidden="true"> </i>  About Mapminer
                                        </a>
                                    </li>
                                
    								<li class="divider"></li>
    								<li>
                                        <a href="{{ route('logout') }}" 
                                             onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                              <i class="fa fa-sign-out" aria-hidden="true"> </i>
                                              Logout
                                        </a>
                                         <form id="logout-form" 
                                                action="{{ route('logout') }}" 
                                            method="POST" 
                                            style="display: none;">
                                                        {{ csrf_field() }}
                                          </form>
                                    </li>
    							</ul>
    					</li>
    				</ul>
                    @endif
					<!-- ./ nav-collapse -->
				</div>
                
			</div>
		</div>
        
		<!-- ./ navbar -->
=======
<nav class="navbar navbar-expand-md navbar-light" >
 <div class="container">
  <a href="{{ route('findme') }}" class="navbar-brand"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164' ></a> <!--     <nav class="navbar navbar-toggleable-sm navbar-light bg-faded">
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
                    <i class="far fa-envelope" aria-hidden="true"> </i> 
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
</div>
</nav>

>>>>>>> development
