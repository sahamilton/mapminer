          <ul class="navbar-nav" style="margin-right:60px">
                 <li class="nav-item">
                <a  class="nav-link" href="{{route('feedback.index')}}" >
                    <i class="fas fa-bullhorn"> </i> Feedback</a>
                @php $news = new \App\News; @endphp
                @if($news->currentNews()->count()>0)

                <li class="nav-item">
                  <a  class="dropdown-item" 
                    href="{{route('currentnews')}}">
                    <i class="fas fa-bell" 
                      aria-hidden="true"> </i> 
                    News
                  </a>
                </li>
                @endif
                
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle"
                href="#"
                id="navbarDropdownMenuLink"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
              
               <img src="{{asset('/storage/avatars/'. auth()->user()->person->avatar)}}" 
                  id="profile-avatar"
                  width='30px'
                  height='30px'  
                  style="border-radius:50%;"/>
                {{ucfirst(strtolower( auth()->user()->person->firstname ))}}
                <span class="caret"></span>
            </a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

              <a class="dropdown-item" 
                href="{{{ route('user.show',auth()->user()->id) }}}">
              <i class="far fa-user" 
                aria-hidden="true"> </i> 
              Your Profile</a>
              <a class="dropdown-item" href="{{route('about')}}">
                <i class="fas fa-question"></i>  
                About Mapminer
              </a>
              <a class="dropdown-item" 
              href="{{ route('logout') }}" 
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
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