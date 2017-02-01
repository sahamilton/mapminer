<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top"><div style="width:80;position:relative;float:left"><a href="{{{ URL::to('/findme') }}}"><img src="{{{ asset('assets/img/TrueBlue_cw.png')}}}" /></a></div>
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
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('company') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Accounts<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            <li 
    							<li{{ (Request::is('company*') ? ' class="active"' : '') }}>
                                <a href="{{{ URL::to('company') }}}">
                                <span class="glyphicon glyphicon-wrench"></span>  Manage Accounts</a>
                                </li>
                                <li{{ (Request::is('admin/serviceline*') ? ' class="active"' : '') }}>
                                <a href="{{{ route('admin.serviceline.index') }}}">
                                <span class="glyphicon glyphicon-wrench"></span>  Manage Service Lines</a>
                                </li>

                                <li{{ (Request::is('admin/salesnote*') ? ' class="active"' : '') }}>
                                <a href="{{{ URL::to('admin/salesnotes') }}}">
                                <span class="glyphicon glyphicon-wrench"></span>  Manage Salesnotes</a>
                                </li>
                                 <li{{ (Request::is('admin/locationnotes*') ? ' class="active"' : '') }}>
                                <a href="{{{ URL::to('admin/locationnotes') }}}">
                                <span class="glyphicon glyphicon-import"></span> Review / Manage Location Notes</a>
                                </li>
                                
                                <li{{ (Request::is('admin/searchfilters*') ? ' class="active"' : '') }}>
                                <a href="{{{ URL::to('admin/searchfilters') }}}">
                                <span class="glyphicon glyphicon-filter"></span> Manage Filters</a>
                                </li>
                                
                                <li{{ (Request::is('admin/locations*') ? ' class="active"' : '') }}>
                                <a href="{{{ URL::to('admin/locations') }}}">
                                <span class="glyphicon glyphicon-import"></span> Import Locations</a>
                                </li>
                                
                                <li{{ (Request::is('admin/companies/download') ? ' class = "active"' : '') }}>
                                <a href =" {{{ route('companies.download') }}}">                                
                                <span class="glyphicon glyphicon-export"></span> Export Companies</a></li>
                                
                                 <li{{ (Request::is('admin/companies/export') ? ' class="active"' : '') }}>
                                 <a href="{{{ URL::to('admin/companies/export') }}}">
                                 <span class="glyphicon glyphicon-export"></span> Export Locations</a></li>
                                
                                
                                

    						</ul>
                            <!-- Manage People -->
  
                            <li class="dropdown{{ (Request::is('person*') ? ' class="active"' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('person') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> People<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            
                                <li{{ (Request::is('company*') ? ' class="active"' : '') }}>
                                    <a href="{{{ URL::to('person') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage People
                                    </a>
                                 </li>
                                
                                
                                 <li{{ (Request::is('admin/person*') ? ' class="active"' : '') }}>
                                 <a href="{{{ URL::to('admin/person/import') }}}">
                                 <span class="glyphicon glyphicon-import"></span> 
                                 Import Managers
                                 </a>
                                 </li>
                                 
                                  <li{{ (Request::is('admin/person/export') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/person/export') }}}"><span class="glyphicon glyphicon-export"></span> Export Managers</a></li>
                            </ul>
                            </li>
                           <!-- Manage Branches  -->           

							
                               
                            <li class="dropdown{{ (Request::is('admin/branch*') ? ' class="active"' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('branch') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Branches<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            
                                <li{{ (Request::is('branches*') ? ' class="active"' : '') }}>
                                    <a href="{{{ URL::to('branch') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span>  Manage Branches
                                    </a>
                                 </li>
                                
                                
                             <li{{ (Request::is('admin/branch*') ? ' class="active"' : '') }}>
                             <a href="{{{ URL::to('admin/branches/import') }}}">
                             <span class="glyphicon glyphicon-import"></span> 
                             Import Branches
                             </a>
                             </li>
                             <li{{ (Request::is('admin/branches/export') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/branches/export') }}}"><span class="glyphicon glyphicon-export"></span> Export Branches</a></li>
                             
                             
                             
                            </ul>
                            </li>
                             
                         <!-- Manage Users  -->    
				

                         <li class="dropdown{{ (Request::is('admin/users*','admin/roles*') ? ' active' : '') }}">
    						<a class="dropdown" data-toggle="dropdown" href="{{{ URL::to('users') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Users <span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
    							<li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}"><span class="glyphicon glyphicon-wrench"></span> Users</a></li>
    							<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}"><span class="glyphicon glyphicon-wrench"></span> Roles</a></li>
 <li{{ (Request::is('admin/companies*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users/import') }}}"><span class="glyphicon glyphicon-import"></span> Import Users</a></li>
 <li{{ (Request::is('admin/users/export') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users/export') }}}"><span class="glyphicon glyphicon-export"></span> Export Users</a></li>
    						</ul>
    					</li>
                       <!-- Manage Ness  -->      

    							<li{{ (Request::is('admin/news*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/news') }}}"><span class="glyphicon glyphicon-wrench"></span> Updates</a></li>
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
                                    <a href="{{{ URL::to('user/settings') }}}">
                                    <span class="glyphicon glyphicon-wrench"></span> 
                                    Profile</a>
                                    </li>
    								<li class="divider"></li>
    								<li><a href="{{{ URL::to('user/logout') }}}"><span class="glyphicon glyphicon-share"></span> Logout</a></li>
    							</ul>
    					</li>
    				</ul>

					<!-- ./ nav-collapse -->
				</div>
                
			</div>
		</div>
        
		<!-- ./ navbar -->