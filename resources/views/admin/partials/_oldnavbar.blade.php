<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top"><div class="logo"><a href="{{ route('findme') }}"><img src="{{ asset('assets/img/PRlogo.png')}}" width ='164'/></a></div>
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
                      
                 		@if(auth()->user()->hasRole('admin'))
                    	<li><a href="{{route('dashboard')}}">
                        <i class="far fa-tachometer" aria-hidden="true"> </i> Dashboard</a></li>
                        @endif
                        <li class="dropdown{{ (Request::is('admin/company*','admin/locations*') ? ' class="active"' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('company.index') }}">
							<i class="far fa-wrench" aria-hidden="true"></i> Data Management<span class="caret"></span>
						</a> 
                        
						<ul class="dropdown-menu multi-level">
                          <!-- Manage accounts -->
                            <li class="dropdown-submenu">
                            @can('manage_accounts')
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Accounts</a>
                                <ul class="dropdown-menu">
        							<li{{ (Request::is('company*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('company.index') }}">
                                    <i class="far fa-wrench" aria-hidden="true"></i>  Manage Accounts</a>
                                    </li>
                                    <li{{ (Request::is('admin/locations*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('locations.index') }}">
                                    <i class="far fa-upload" aria-hidden="true"> </i> Import Locations</a>
                                    </li>
                                    
                                    <li{{ (Request::is('admin/companies/download') ? ' class = "active"' : '') }}>
                                    <a href =" {{ route('companies.download') }}">                                
                                    <i class="far fa-download" aria-hidden="true"> </i> Export Companies</a></li>
                                    
                                     <li{{ (Request::is('admin/companies/export') ? ' class="active"' : '') }}>
                                     <a href="{{ route('companies.locationsexport') }}">
                                     <i class="fas fa-cloud-download-alt" aria-hidden="true"></i> Export Locations</a></li>
                                    
                                    <li class="divider"></li>
                                    
                                    <li{{ (Request::is('serviceline*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('serviceline.index') }}">
                                    <i class="far fa-wrench" aria-hidden="true"></i>  Manage Service Lines</a>
                                    </li>
                                    <li{{ (Request::is('admin/searchfilters*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('searchfilters.index') }}">
                                    <i class="far fa-filter" aria-hidden="true"></i> Manage Filters</a>
                                    </li> 
                                   
                                    <li class="divider"></li>
                                    
                                    <li{{ (Request::is('admin/salesnote*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('salesnotes.index') }}">
                                    <i class="far fa-wrench" aria-hidden="true"></i>  Manage Salesnotes</a>
                                    </li>
                                     <li{{ (Request::is('admin/locationnotes*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('locations.notes') }}">
                                    <i class="far fa-upload" aria-hidden="true"></i> Review / Manage Location Notes</a>
                                    </li>
                                    
                                 </ul> 

                                 </li>  
                                    
                                  @endcan       
                              
                              
                            
                           @can('manage_branches')
                            <!-- Manage Branches  -->   

						    <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Branches</a>
                            <ul class="dropdown-menu">
                                

                                <li{{ (Request::is('branches*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('branch.management') }}">
                                    <i class="far fa-wrench" aria-hidden="true"></i>  Manage Branches
                                    </a>
                                 </li>
                                 <li{{ (Request::is('branches*') ? ' class="active"' : '') }}>
                                    <a href="{{ route('branch.check') }}">
                                    <i class="far fa-stethoscope"></i>  Check Assignments
                                    </a>
                                 </li>
                                 

<!--
                                <li{{ (Request::is('admin/branch*') ? ' class="active"' : '') }}>
                                <a href="{{ route('branches.import') }}">
                               <i class="far fa-upload" aria-hidden="true"> </i> 
                                Import Branches
                                </a>
                                </li>-->
                                <li{{ (Request::is('admin/branches/export') ? ' class="active"' : '') }}><a href="{{ route('branches.export') }}">
                                <i class="far fa-download" aria-hidden="true"> </i> Export Branches</a></li>


                            </ul>
                         </li>
                         @endcan

                             
                         <!-- Manage Users  -->  
                         @can('manage_users')  
                        <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users</a>
                            <ul class="dropdown-menu">
                                <li{{ (Request::is('admin/users/create') ? ' class="active"' : '') }}><a href="{{ route('users.create') }}">
                                <i class="far fa-user-plus" aria-hidden="true"></i> Create User</a></li>

                                <li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{ route('users.index') }}">
                                <i class="far fa-user" aria-hidden="true"></i> All Users</a></li>
                                <li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{ route('roles.index') }}">
                                <i class="far fa-wrench" aria-hidden="true"></i> Roles</a></li>
                                <li{{ (Request::is('admin/permissions*') ? ' class="active"' : '') }}><a href="{{ route('permissions.index') }}"><i class="far fa-check" aria-hidden="true"></i> Permissions</a></li>
                                <li{{ (Request::is('admin/users/nomanager') ? ' class="active"' : '') }}>
                                <a href="{{ route('nomanager') }}">
                                <i class="fas fa-minus-circle" aria-hidden="true"></i> Without Manager</a></li>

                                <li{{ (Request::is('admin/users/export') ? ' class="active"' : '') }}><a href="{{ route('person.export') }}">
                                <i class="far fa-download" aria-hidden="true"> </i> Export Users</a></li>
                            </ul>
                        </li>
                        <li{{ (Request::is('admin/import*') ? ' class="active"' : '') }}>
                        <a href="{{ route('imports.index') }}">
                            <i class="far fa-level-up"></i>Import / Export</li>
                        </a></li>
                        @endcan
              
                      </ul>

          <!-- Manage Resources  -->      
            <li class="dropdown{{ (Request::is('admin/lead*','admin/document*','admin/search*','admin/projects*') ? ' active' : '') }}">
                <a class="dropdown" data-toggle="dropdown" href="{{ route('users.index') }}">
                    <i class="far fa-wrench" aria-hidden="true"></i> 
                     Resources 
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
					<li{{ (Request::is('admin/news*') ? ' class="active"' : '') }}>
                    <a href="{{ route('news.index') }}">
                    <i class="far fa-newspaper-o" aria-hidden="true"> </i> News</a></li>

                    <li{{ (Request::is('admin/training*') ? ' class="active"' : '') }}>
                    <a href="{{ route('training.index') }}">
                    <i class="far fa-graduation-cap" aria-hidden="true"> </i> Training</a></li>

                    <li{{ (Request::is('admin/emails*') ? ' class="active"' : '') }}>
                    <a href="{{ route('emails.index') }}">
                    <i class="far fa-envelope-o" aria-hidden="true"> </i> Emails</a></li>
                    @can('manage_sales_campaigns')
                        <li class="divider">Campaigns</li>
                        <li{{ (Request::is('admin/documents*') ? ' class="active"' : '') }}><a href="{{ route('documents.index') }}">
                        <i class="far fa-book" aria-hidden="true"> </i> Sales Library</a></li>
                        <li {{ (Request::is('admin/process*') ? ' class="active"' : '') }}><a href="{{ route('process.index') }}">
                        <i class="far fa-step-forward" aria-hidden="true"> </i> Sales Process</a></li>
                        <li {{ (Request::is('admin/salesactivity*') ? ' class="active"' : '') }}><a href="{{ route('salesactivity.index') }}">
                        <i class="far fa-calendar-check-o" aria-hidden="true"> </i> Sales Campaigns</a></li>
                    @endcan
                    @can('manage_prospects')
                        <li class="divider">Prospects</li>
                        <li{{ (Request::is('ops/webleads*') ? ' class="active"' : '') }}><a href="{{ route('leads.search') }}">
                        <i class="fas fa-plus" aria-hidden="true"> </i> Add New Prospect</a></li>
                        <li{{ (Request::is('admin/leadsource*') ? ' class="active"' : '') }}><a href="{{ route('leadsource.index') }}">
                        <i class="far fa-diamond" aria-hidden="true"> </i> Prospect Sources</a></li>
                        <li {{ (Request::is('admin/leadstatus*') ? ' class="active"' : '') }}><a href="{{ route('leadstatus.index') }}">
                        <i class="far fa-star-o" aria-hidden="true"> </i> Prospect Statuses</a></li>
                     @endcan   
                   
                    
                    <li class="divider">Industries</li>
                    <li {{ (Request::is('admin/search*') ? ' class="active"' : '') }}><a href="{{ route('vertical.analysis') }}">
                    <i class="far fa-building-o" aria-hidden="true"> </i> Industries</a></li>
                    @can('view_projects')
                    <li {{ (Request::is('admin/projects*') ? ' class="active"' : '') }}>
                    <a href="{{ route('project.stats') }}">
                    
                    <i class="far fa-flag" aria-hidden="true"> </i> Projects</a></li>
                    @endcan
                </ul>
			</li>
                       
          
    </ul>
    <div style="margin-top:10px">
                        <input  type="text" id="search" placeholder="Type to search users" autocomplete="off" ><i class="fas fa-search"></i>
                    </div> 
    <ul class="nav navbar-nav float-right">
                        
                        
                        <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                    <i class="far fa-user" aria-hidden="true"></i> {{ucfirst(strtolower( auth()->user()->person->firstname ))}}  <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                    <a href="{{ route('user.show',auth()->user()->id) }}">
                                    <i class="far fa-user" aria-hidden="true"> </i> 
                                    Your Profile
                                    </a>
                                    </li>
                                    @if(auth()->user()->hasRole('admin')) 
                                        <li>
                                            <a href="{{route('about')}}">
                                                <i class="far fa-info-circle" aria-hidden="true"> </i> 
                                                About Mapminer
                                            </a>
                                        </li>
                                    @endif
                                    <li class="divider"></li>
                                    <li>
                                        <a href="{{ route('logout') }}" 
                                             onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                              <i class="far fa-sign-out" aria-hidden="true"> </i>
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

					<!-- ./ nav-collapse -->
				</div>
                
			</div>
		</div>
        
		<!-- ./ navbar -->