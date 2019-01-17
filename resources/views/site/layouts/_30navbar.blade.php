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
                     @if (!auth()->check())
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ route('welcome') }}}">Welcome</a>
                       
                       </li>
                     @else
                        <li class="dropdown{{ (Request::is('company*','branch*' ,'person*','findme') ? ' active' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ route('findme') }}}">
    							<i class="fas fa-search" aria-hidden="true"></i> Search<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            <li {{ (Request::is('findme') ? ' class="active"' : '') }}><a href="{{{ route('findme') }}}">
                            <i class="far fa-map-o" aria-hidden="true"> </i> Maps</a></li>
                            <li {{ (Request::is('company*') ? ' class="active"' : '') }}><a href="{{{ route('company.index') }}}">
                            <i class="far fa-building-o" aria-hidden="true"> </i> Accounts</a></li>
                            <li {{ (Request::is('branch*') ? ' class="active"' : '') }}><a href="{{{ route('branches.map') }}}">
                            <i class="far fa-shopping-bag" aria-hidden="true"> </i> Branches</a></li>
                            <li {{ (Request::is('person*') ? ' class="active"' : '') }}><a href="{{{ route('person.index') }}}">
                            <i class="far fa-users" aria-hidden="true"> </i> People</a></li>
                            @can('view_projects')
                                <li {{ (Request::is('project*') ? ' class="active"' : '') }}><a href="{{{ route('projects.index') }}}">
                                <i class="far fa-flag" aria-hidden="true"> </i> Projects</a></li>
                            @endcan
                            </ul>
                            </li>
                        <li class="dropdown{{ (Request::is('watch*','mynote*') ? ' active' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" >
                         My Activity<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                        <li {{ (Request::is('watch') ? ' class="active"' : '') }}><a href="{{{ route('watch.index') }}}">
                        <i class ="far fa-eye"></i> My Watch List</a></li>
                         <li {{ (Request::is('mynotes') ? ' class="active"' : '') }}><a href="{{{ route('mynotes') }}}">
                        <i class="far fa-folder-open-o" aria-hidden="true"></i>
                         My Notes</a>
                         <li {{ (Request::is('mytraining') ? ' class="active"' : '') }}><a href="{{{ route('training.index') }}}">
                        <i class="far fa-graduation-cap" aria-hidden="true"></i>
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
                        <li><a href="{{route('salesorg.index')}}">
                        <i class="far fa-sitemap" aria-hidden="true"> </i>
                        Sales Organization</a></li>
                        @if(auth()->user()->hasRole('admin') or auth()->user()->hasRole('Sales') or  auth()->user()->hasRole('Sales Manager'))
                            <li class="divider"></li>
                            <li><a href="{{route('resources.view')}}">
                            <i class="far fa-book" aria-hidden="true"> </i>
                             Sales Library</a></li>
                            <li><a href="{{route('salescampaigns')}}">
                            <i class="far fa-calendar-check-o" aria-hidden="true"> </i> Sales Campaigns</a></li>

                        @endif 
                         
                        @if(auth()->user()->can('accept_leads') or auth()->user()->can('manage_leads'))
                            <li><a href="{{route('salesrep.newleads',auth()->user()->person->id)}}">
                            <i class="far fa-envelope" aria-hidden="true"> </i> Sales Prospects</a></li>
                        @endif
                        @if(auth()->user()->hasRole('Branch Manager'))
                            <li><a href="{{route('branchmanager.newleads')}}">
                            <i class="far fa-envelope" aria-hidden="true"> </i> Branch Prospects</a></li>
                        @endif
                        @if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('National Account Manager'))
                        <li class="divider"></li>
                         <li><a href="{{route('managers.view')}}">
                         <i class="far fa-eye" aria-hidden="true"> </i> Account Managers View</a></li>
                        
                        
                        @endif
                        @can('manage_projects')
                         <li class="divider"></li>
                         <li><a href="{{route('projects.myprojects')}}">
                         <i class="far fa-flag" aria-hidden="true"> </i> My Construction projects</a></li>

                        @endcan
                        @can('manage_prospects')
                            <li class="divider"></li>
                            
                        @endcan
                        </ul>

                         
                        
					</ul>
                
					<ul class="nav navbar-nav float-right">
 						<?php $news = new \App\News;?>
                        @if($news->currentNews()->count()>0)
                        <li>
                            <a href="{{route('currentnews')}}">
                            <i class="fas fa-bell" aria-hidden="true"> </i> News</a>


                        </li>
                        @endif
                        @if (auth()->user()->hasRole('admin'))
                         <li>
    						<a href="{{{ route('dashboard') }}}">
                           <i class="far fa-tachometer" aria-hidden="true"> </i> Admin </a>
    						
    					</li>
                        @endif

                         @if (auth()->user()->hasRole('sales_operations'))
                         <li>
                            <a href="{{{ route('dashboard') }}}">
                           <i class="far fa-tachometer" aria-hidden="true"> </i> Ops </a>
                            
                        </li>
                        @endif
    					<li class="divider-vertical"></li>
    					<li class="dropdown">
    							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
    								<i class="far fa-user" aria-hidden="true"></i> {{ucfirst(strtolower( auth()->user()->person->firstname ))}}	<span class="caret"></span>
    							</a>
    							<ul class="dropdown-menu">
    								<li>
                                        <a href="{{{ route('profile') }}}">
                                            <i class="far fa-user" aria-hidden="true"> </i> Your Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('about')}}">
                                            <i class="far fa-info-circle" aria-hidden="true"> </i>  About Mapminer
                                        </a>
                                    </li>
                                
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
                    @endif
					<!-- ./ nav-collapse -->
				</div>
                
			</div>
		</div>
        
		<!-- ./ navbar -->