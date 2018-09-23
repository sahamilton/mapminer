 @can('manage_branches')
                            <!-- Manage Branches  -->   

						    <li class="nav-item dropdown-submenu">
                            <a class="nav-link" href="#" class="dropdown-toggle" data-toggle="dropdown">Branches</a>
                            
                                <ul class="dropdown-menu">
                                

                                <li class="dropdown-item">
                                    <a class="nav-link" href="{{ route('branch.management') }}">
                                    <i class="fa fa-wrench" aria-hidden="true"></i>  Manage Branches
                                    </a>
                                 </li>
                                 <li class="dropdown-item">
                                    <a class="nav-link" href="{{ route('branch.check') }}">
                                    <i class="fa fa-stethoscope"></i>  Check Assignments
                                    </a>
                                 </li>
                             </ul>
                         </li>

                      
                         @endcan
                        
                         <li class="dropdown-submenu">
                                <ul class="dropdown-menu">
                             
                         <!-- Manage Users  -->  
                         @can('manage_users')  
                        <li class="nav-item dropdown-submenu">
                        <a class="nav-link" href="#" class="dropdown-toggle" data-toggle="dropdown">Users</a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-item">
                                    <a class="nav-link" href="{{ route('users.create') }}">
                                    <i class="fa fa-user-plus" aria-hidden="true"></i> Create User</a>
                                </li>

                                <li class="dropdown-item">
                                    <a class="nav-link" 
                                    href="{{ route('users.index') }}">
                                    <i class="fa fa-user" aria-hidden="true"></i> All Users</a></li>
                                <li class="dropdown-item">
                                    <a class="nav-link" href="{{ route('roles.index') }}">
                                    <i class="fa fa-wrench" aria-hidden="true"></i> Roles</a>
                                </li>
                                <li class="dropdown-item">
                                    <a class="nav-link" href="{{ route('permissions.index') }}">
                                        <i class="fa fa-check" aria-hidden="true"></i> Permissions
                                    </a>
                                </li>
                                <li class="dropdown-item">
                                    <a class="nav-link" href="{{ route('nomanager') }}">
                                    <i class="fa fa-minus-circle" aria-hidden="true"></i> Without Manager</a>
                                </li>

                        
                        <li class="dropdown-item">
                        <a class="nav-link" href="{{ route('imports.index') }}">
                            <i class="fa fa-level-up"></i>Import / Export</li>
                        </a></li>
                        @endcan
                  </ul>
                        </li>
                      </ul>

          <!-- Manage Resources  -->      
            <li class="nav-item dropdown{{ (Request::is('admin/lead*','admin/document*','admin/search*','admin/projects*') ? ' active' : '') }}">
                <a class="dropdown" data-toggle="dropdown" href="{{ route('users.index') }}">
                    <i class="fa fa-wrench" aria-hidden="true"></i> 
                     Resources 
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
					<li{{ (Request::is('admin/news*') ? ' class="active"' : '') }}>
                    <a class="nav-link" href="{{ route('news.index') }}">
                    <i class="fa fa-newspaper-o" aria-hidden="true"> </i> News</a></li>

                    <li{{ (Request::is('admin/training*') ? ' class="active"' : '') }}>
                    <a class="nav-link" href="{{ route('training.index') }}">
                    <i class="fa fa-graduation-cap" aria-hidden="true"> </i> Training</a></li>

                    <li{{ (Request::is('admin/emails*') ? ' class="active"' : '') }}>
                    <a class="nav-link" href="{{ route('emails.index') }}">
                    <i class="fa fa-envelope-o" aria-hidden="true"> </i> Emails</a></li>
                    @can('manage_sales_campaigns')
                        <li class="divider">Campaigns</li>
                        <li{{ (Request::is('admin/documents*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('documents.index') }}">
                        <i class="fa fa-book" aria-hidden="true"> </i> Sales Library</a></li>
                        <li {{ (Request::is('admin/process*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('process.index') }}">
                        <i class="fa fa-step-forward" aria-hidden="true"> </i> Sales Process</a></li>
                        <li {{ (Request::is('admin/salesactivity*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('salesactivity.index') }}">
                        <i class="fa fa-calendar-check-o" aria-hidden="true"> </i> Sales Campaigns</a></li>
                    @endcan
                    @can('manage_prospects')
                        <li class="divider">Prospects</li>
                        <li{{ (Request::is('ops/webleads*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('leads.search') }}">
                        <i class="fa fa-plus" aria-hidden="true"> </i> Add New Prospect</a></li>
                        <li{{ (Request::is('admin/leadsource*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('leadsource.index') }}">
                        <i class="fa fa-diamond" aria-hidden="true"> </i> Prospect Sources</a></li>
                        <li {{ (Request::is('admin/leadstatus*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('leadstatus.index') }}">
                        <i class="fa fa-star-o" aria-hidden="true"> </i> Prospect Statuses</a></li>
                     @endcan   
                   
                    
                    <li class="divider">Industries</li>
                    <li {{ (Request::is('admin/search*') ? ' class="active"' : '') }}><a class="nav-link" href="{{ route('vertical.analysis') }}">
                    <i class="fa fa-building-o" aria-hidden="true"> </i> Industries</a></li>
                    @can('view_projects')
                    <li {{ (Request::is('admin/projects*') ? ' class="active"' : '') }}>
                    <a class="nav-link" href="{{ route('project.stats') }}">
                    
                    <i class="fa fa-flag" aria-hidden="true"> </i> Projects</a></li>
                    @endcan
                </ul>
			</li>
                       
          
    </ul>