          <ul class="navbar-nav" style="margin-right:60px">
                
                @php $news = new \App\News;@endphp
                @if($news->currentNews()->count()>0)

                <li class="nav-item">
                  <a  class="dropdown-item" href="{{route('currentnews')}}">
                  <i class="far fa-bell-o" aria-hidden="true"> </i> News</a>
                </li>
                @endif
                
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-user" aria-hidden="true"></i> {{ucfirst(strtolower( auth()->user()->person->firstname ))}}<span class="caret"></span></a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

              <a class="dropdown-item" href="{{{ route('user.show',auth()->user()->id) }}}">
              <i class="far fa-user" aria-hidden="true"> </i> 
            Your Profile</a>
              <a class="dropdown-item" href="{{route('about')}}">
              <i class="far fa-info-circle" aria-hidden="true"> </i>  
            About Mapminer</a>
              <a class="dropdown-item" href="{{ route('logout') }}" 
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
              <i class="far fa-sign-out" aria-hidden="true"> </i>
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