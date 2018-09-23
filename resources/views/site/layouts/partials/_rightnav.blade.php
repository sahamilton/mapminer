          <ul class="navbar-nav" style="margin-right:60px">
                
                @php $news = new \App\News;@endphp
                @if($news->currentNews()->count()>0)

                <li class="nav-item">
                  <a  class="dropdown-item" href="{{route('currentnews')}}">
                  <i class="fa fa-bell-o" aria-hidden="true"> </i> News</a>
                </li>
                @endif
                
                @if (Auth::user()->hasRole('Admin'))

                <li class="nav-item">
                  <a  class="nav-link" href="{{{ route('dashboard') }}}">
                  <i class="fa fa-tachometer" aria-hidden="true"> </i> Admin </a>
                </li>
                @endif

                @if (Auth::user()->hasRole('Sales Operations'))

              <li class="nav-item">
                <a  class="nav-link" href="{{{ route('dashboard') }}}">
                <i class="fa fa-tachometer" aria-hidden="true"> </i> Ops </a>
              </li>
                @endif
           
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user" aria-hidden="true"></i> {{ucfirst(strtolower( Auth::user()->person->firstname ))}}<span class="caret"></span></a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

              <a class="dropdown-item" href="{{{ route('user.show',auth()->user()->id) }}}">
              <i class="fa fa-user" aria-hidden="true"> </i> 
            Your Profile</a>
              <a class="dropdown-item" href="{{route('about')}}">
              <i class="fa fa-info-circle" aria-hidden="true"> </i>  
            About Mapminer</a>
              <a class="dropdown-item" href="{{ route('logout') }}" 
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
              <i class="fa fa-sign-out" aria-hidden="true"> </i>
              Logout</a>
               <form id="logout-form" 
                     action="{{ route('logout') }}" 
                    method="POST" 
                    style="display: none;">
                    {{ csrf_field() }}
                </form>
              </div>      
              </li>
            </ul>