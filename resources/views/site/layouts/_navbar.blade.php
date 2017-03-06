<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top">
        <div class="logo" >
        
        <a href="{{ URL::to('/findme') }}"><img src="{{ asset('assets/img/PRlogo.png')}}"  width ='164'/></a>
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
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('/') }}}">Welcome</a>
                       
                       </li>
                        @else
                        <li class="dropdown{{ (Request::is('company*','branch*' ,'person*','findme') ? ' active' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('findme') }}}">
    							<span class="glyphicon glyphicon-search"></span> Search<span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
                            <li {{ (Request::is('findme') ? ' class="active"' : '') }}><a href="{{{ route('findme') }}}">Maps</a></li>
                            <li {{ (Request::is('company*') ? ' class="active"' : '') }}><a href="{{{ route('company.index') }}}">Accounts</a></li>
                            <li {{ (Request::is('branch*') ? ' class="active"' : '') }}><a href="{{{ route('branches.map') }}}">Branches</a></li>
                            <li {{ (Request::is('person*') ? ' class="active"' : '') }}><a href="{{{ route('person.index') }}}">People</a></li>
                            </ul>
                            </li>
                        <li {{ (Request::is('watch') ? ' class="active"' : '') }}><a href="{{{ route('watch') }}}">
                        <span class ="glyphicon glyphicon-eye-open"></span> Watch List</a></li>
                        
                        <li class="dropdown{{ (Request::is('salesorg*','resources*') ? ' active' : '') }}">
                        <a class="dropdown-toggle" data-toggle="dropdown" >
                         Sales Resources<span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                        <li><a href="{{route('salesorg')}}">Sales Organization</a></li>
                        <li><a href="{{route('resources.view')}}">Sales Library</a></li>
                        </ul>

                         @if (Auth::user()->hasRole('Admin') or Auth::user()->hasRole('National Account Manager'))
                         <li><a href="{{route('managers.view')}}">Managers View</a></li>
                        
                        
                        @endif
                        
					</ul>
                
					<ul class="nav navbar-nav pull-right">
 						
                        @if (Auth::user()->hasRole('Admin'))
                         <li>
    						<a href="{{{ URL::to('admin') }}}">
    							<span class="glyphicon glyphicon-wrench"></span> Admin </a>
    						
    					</li>
                        @endif
    					<li class="divider-vertical"></li>
    					<li class="dropdown">
    							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
    								<span class="glyphicon glyphicon-user"></span> {{{ Auth::user()->firstname }}}	<span class="caret"></span>
    							</a>
    							<ul class="dropdown-menu">
    								<li><a href="{{{ url::to('user/settings') }}}"><span class="glyphicon glyphicon-wrench"></span> Profile</a></li>
    								<li class="divider"></li>
    								<li>
                                        <a href="{{ url('logout') }}" 
                                             onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
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