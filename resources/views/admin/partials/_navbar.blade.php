<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top"><div class="logo"><a href="{{{ route('findme') }}}"><img src="{{{ asset('assets/img/PRlogo.png')}}}" width ='164'/></a></div>
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
                 			
                    	<li><a href="{{route('dashboard')}}">Dashboard</a></li>

                        <li class="dropdown{{ (Request::is('admin/company*','admin/locations*') ? ' class="active"' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ route('company.index') }}}">
							<span class="glyphicon glyphicon-wrench"></span> Data Management<span class="caret"></span>
						</a> 
                        
						<ul class="dropdown-menu multi-level">
                         @can('manage_accounts') <!-- Manage accounts -->
                            <li class="dropdown-submenu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Accounts</a>
                                <ul class="dropdown-menu">
        							<li{{ (Request::is('company*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('company.index') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage Accounts</a>
                                    </li>
                                    <li{{ (Request::is('admin/locations*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('locations.index') }}}">
                                    <span class="glyphicon glyphicon-import"></span> Import Locations</a>
                                    </li>
                                    
                                    <li{{ (Request::is('admin/companies/download') ? ' class = "active"' : '') }}>
                                    <a href =" {{{ route('companies.download') }}}">                                
                                    <span class="glyphicon glyphicon-export"></span> Export Companies</a></li>
                                    
                                     <li{{ (Request::is('admin/companies/export') ? ' class="active"' : '') }}>
                                     <a href="{{{ route('companies.locationsexport') }}}">
                                     <span class="glyphicon glyphicon-export"></span> Export Locations</a></li>
                                    
                                    <li class="divider"></li>
                                    
                                    <li{{ (Request::is('serviceline*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('serviceline.index') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage Service Lines</a>
                                    </li>
                                    <li{{ (Request::is('admin/searchfilters*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('searchfilters.index') }}}">
                                    <span class="glyphicon glyphicon-filter"></span> Manage Filters</a>
                                    </li> 
                                   
                                    <li class="divider"></li>
                                    
                                    <li{{ (Request::is('admin/salesnote*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('salesnotes.index') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage Salesnotes</a>
                                    </li>
                                     <li{{ (Request::is('admin/locationnotes*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('locations.notes') }}}">
                                    <span class="glyphicon glyphicon-import"></span> Review / Manage Location Notes</a>
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
                                    <a href="{{{ route('branches.index') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage Branches
                                    </a>
                                 </li>


                                <li{{ (Request::is('admin/branch*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('branches.import') }}}">
                                <span class="glyphicon glyphicon-import"></span> 
                                Import Branches
                                </a>
                                </li>
                                <li{{ (Request::is('admin/branches/export') ? ' class="active"' : '') }}><a href="{{{ route('branches.export') }}}"><span class="glyphicon glyphicon-export"></span> Export Branches</a></li>


                            </ul>
                         </li>
                         @endcan

                             
                         <!-- Manage Users  -->  
                         @can('manage_users')  
                        <li class="dropdown-submenu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Users</a>
                            <ul class="dropdown-menu">


                                <li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ route('users.index') }}}"><span class="glyphicon glyphicon-user"></span> Users</a></li>
                                <li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ route('roles.index') }}}"><span class="glyphicon glyphicon-wrench"></span> Roles</a></li>
                                <li{{ (Request::is('admin/permissions*') ? ' class="active"' : '') }}><a href="{{{ route('permissions.index') }}}"><span class="glyphicon glyphicon-check"></span> Permissions</a></li>



                                <li{{ (Request::is('admin/companies*') ? ' class="active"' : '') }}><a href="{{{ route('admin.users.import') }}}"><span class="glyphicon glyphicon-import"></span> Import Users</a></li>
                                <li{{ (Request::is('admin/users/export') ? ' class="active"' : '') }}><a href="{{{ route('person.export') }}}"><span class="glyphicon glyphicon-export"></span> Export Users</a></li>
                            </ul>
                        </li>
                        @endcan
              
                      </ul>

          <!-- Manage Resources  -->      
            <li class="dropdown{{ (Request::is('admin/lead*','admin/document*','admin/search*') ? ' active' : '') }}">
                <a class="dropdown" data-toggle="dropdown" href="{{{ route('users.index') }}}">
                    <span class="glyphicon glyphicon-wrench"></span> 
                     Resources 
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
					<li{{ (Request::is('admin/news*') ? ' class="active"' : '') }}><a href="{{{ route('news.index') }}}">
                    <span class="glyphicon glyphicon-folder-open"></span> News</a></li>
                    @can('manage_sales_campaigns')
                        <li class="divider">Campaigns</li>
                        <li{{ (Request::is('admin/documents*') ? ' class="active"' : '') }}><a href="{{{ route('documents.index') }}}">
                        <span class="glyphicon glyphicon-book"></span>
                         Sales Library</a></li>
                        <li {{ (Request::is('admin/process*') ? ' class="active"' : '') }}><a href="{{{ route('process.index') }}}">
                        <span class="glyphicon glyphicon-tower"></span>
                         Sales Process</a></li>
                        <li {{ (Request::is('admin/salesactivity*') ? ' class="active"' : '') }}><a href="{{{ route('salesactivity.index') }}}">
                        <span class="glyphicon glyphicon-briefcase"></span> 
                         Sales Campaigns</a></li>
                    @endcan
                    @can('manage_leads')
                        <li class="divider">Leads</li>
                        <li{{ (Request::is('admin/leads') ? ' class="active"' : '') }}><a href="{{{ route('leads.index') }}}">
                        <span class="glyphicon glyphicon-folder-open"></span> Leads</a></li>
                        <li{{ (Request::is('admin/leadsource*') ? ' class="active"' : '') }}><a href="{{{ route('leadsource.index') }}}">
                        <span class="glyphicon glyphicon-book"></span> Lead Sources</a></li>
                        <li {{ (Request::is('admin/leadstatus*') ? ' class="active"' : '') }}><a href="{{{ route('leadstatus.index') }}}">
                        <span class="glyphicon glyphicon-tower"></span> Lead Statuses</a></li>
                        <li class="divider">Industries</li>
                    @endcan
                    <li {{ (Request::is('admin/search*') ? ' class="active"' : '') }}><a href="{{{ route('vertical.analysis') }}}">
                    
                    <span class="glyphicon glyphicon-tower"></span> Industries</a></li>

                </ul>
			</li>
                        
          
    </ul>
    <ul class="nav navbar-nav pull-right">
    					
		<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="glyphicon glyphicon-user"></span> {{{ Auth::user()->username }}}	<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
                    <a href="{{{ route('profile') }}}">
                    <span class="glyphicon glyphicon-wrench"></span> 
                    Profile</a>
                    </li>
                    <li>
                    <a href="{{route('about')}}">About</a></li>
					<li class="divider"></li>
					<li>
                        <a href="{{ url('/logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
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