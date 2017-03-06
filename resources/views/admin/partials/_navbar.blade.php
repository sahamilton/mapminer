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
                     
						<!-- Manage accounts -->
                        	<li><a href="/admin">Main Admin View</a></li>

                            <li class="dropdown{{ (Request::is('admin/company*','admin/locations*') ? ' class="active"' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ route('company.index') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Accounts<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            <li 
    							<li{{ (Request::is('company*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('company.index') }}}">
                                <span class="glyphicon glyphicon-wrench"></span>  Manage Accounts</a>
                                </li>
                                <li{{ (Request::is('serviceline*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('serviceline.index') }}}">
                                <span class="glyphicon glyphicon-wrench"></span>  Manage Service Lines</a>
                                </li>

                                <li{{ (Request::is('admin/salesnote*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('salesnotes.index') }}}">
                                <span class="glyphicon glyphicon-wrench"></span>  Manage Salesnotes</a>
                                </li>
                                 <li{{ (Request::is('admin/locationnotes*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('locations.notes') }}}">
                                <span class="glyphicon glyphicon-import"></span> Review / Manage Location Notes</a>
                                </li>
                                
                                <li{{ (Request::is('admin/searchfilters*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('searchfilters.index') }}}">
                                <span class="glyphicon glyphicon-filter"></span> Manage Filters</a>
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
                                
                                
                                

    						</ul>
                            <!-- Manage People -->
  
                            <li class="dropdown{{ (Request::is('person*') ? ' class="active"' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ route('person.index') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> People<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            
                                <li{{ (Request::is('company*') ? ' class="active"' : '') }}>
                                    <a href="{{{ route('person.index') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage People
                                    </a>
                                 </li>
                                
                                
                                 <li{{ (Request::is('admin/person*') ? ' class="active"' : '') }}>
                                 <a href="{{{ route('person.bulkimport') }}}">
                                 <span class="glyphicon glyphicon-import"></span> 
                                 Import Managers
                                 </a>
                                 </li>
                                 
                                  <li{{ (Request::is('admin/person/export') ? ' class="active"' : '') }}><a href="{{{ route('person.export') }}}"><span class="glyphicon glyphicon-export"></span> Export Managers</a></li>
                            </ul>
                            </li>
                           <!-- Manage Branches  -->           

							
                               
                            <li class="dropdown{{ (Request::is('admin/branch*') ? ' class="active"' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ route('branches.index') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Branches<span class="caret"></span>
    						</a>
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
                             
                         <!-- Manage Users  -->    
				

                         <li class="dropdown{{ (Request::is('admin/users*','admin/roles*') ? ' active' : '') }}">
    						<a class="dropdown" data-toggle="dropdown" href="{{{ route('users.index') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Users <span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
    							<li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ route('users.index') }}}"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    							<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ route('roles.index') }}}"><span class="glyphicon glyphicon-wrench"></span> Roles</a></li>
                  <li{{ (Request::is('admin/permissions*') ? ' class="active"' : '') }}><a href="{{{ route('permissions.index') }}}"><span class="glyphicon glyphicon-check"></span> Permissions</a></li>



                 <li{{ (Request::is('admin/companies*') ? ' class="active"' : '') }}><a href="{{{ route('admin.users.import') }}}"><span class="glyphicon glyphicon-import"></span> Import Users</a></li>
                 <li{{ (Request::is('admin/users/export') ? ' class="active"' : '') }}><a href="{{{ route('person.export') }}}"><span class="glyphicon glyphicon-export"></span> Export Users</a></li>
    						</ul>
    					</li>
                       <!-- Manage Ness  -->      

    							<li{{ (Request::is('admin/news*') ? ' class="active"' : '') }}><a href="{{{ route('news.index') }}}"><span class="glyphicon glyphicon-wrench"></span> Updates</a></li>
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
    								<li class="divider"></li>
    								<li><a href="{{{ route('logout') }}}"><span class="glyphicon glyphicon-share"></span> Logout</a></li>
    							</ul>
    					</li>
    				</ul>

					<!-- ./ nav-collapse -->
				</div>
                
			</div>
		</div>
        
		<!-- ./ navbar -->