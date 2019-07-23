 
      <ul class="navbar-nav ml-auto justify-content-end" style="margin-right:60px"> 
           @if (auth()->user()->hasRole('admin'))
            <li class="nav-item"><input 
                id="search" 
                placeholder="Type to search users" 
                autocomplete="off"
                class="form-control mr-sm-2" 
                type="search" 
                aria-label="Search">
              </li>
              
            @endif
               <li class="nav-item">
                <a  class="nav-link" href="#"
                data-href="" 
                data-toggle="modal" 
                data-target="#add-feedback" >
                    <i class="fas fa-bullhorn" style="color:red"> </i> Feedback</a>
                </li>
                @php $news = new \App\News;@endphp
                <li class="nav-item">
                  
                  @if($news->currentNews()->count()>0)
                  <a  class="nav-link" href="{{route('currentnews')}}">
                    <i class="fas fa-bell" aria-hidden="true" style="color:red"> </i> News</a>
                  @else
                  <a  class="nav-link" href="{{route('news.index')}}">
                    <i class="far fa-bell" aria-hidden="true"> </i> News</a>
                  @endif
                   
                </li>
                
                @if (auth()->user()->hasRole('admin'))

                <li class="nav-item">
                  <a  class="nav-link" href="{{{ route('dashboard') }}}">
                  <i class="fas fa-tachometer-alt" aria-hidden="true"> </i> Admin </a>
                </li>
                @endif

                @if (auth()->user()->hasRole('sales_operations'))

              <li class="nav-item">
                <a  class="nav-link" href="{{{ route('dashboard') }}}">
                <i class="fas fa-tachometer-alt" aria-hidden="true"> </i> Ops </a>
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
            <div class="dropdown-menu ml-auto" aria-labelledby="navbarDropdownMenuLink">

              <a class="dropdown-item" href="{{{ route('user.show',auth()->user()->id) }}}">
              <i class="far fa-user" aria-hidden="true"> </i> 
            Your Profile</a>
              <a class="dropdown-item" href="{{route('about')}}">
              <i class="fas fa-info-circle" aria-hidden="true">
              </i>  
            About Mapminer</a>
            @can('service_branches')
                
                  <a class="dropdown-item" href="{{{ route('branchassignments.show',auth()->user()->id) }}}">
                  <i class="fas fa-search-location"></i> My Branch Assignments</a>
                
                @endcan
              <a class="dropdown-item" href="{{ route('logout') }}" 
                 onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                 
              <i class="fas fa-sign-out-alt" aria-hidden="true"> </i>
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
            @include('feedback.partials._feedback')