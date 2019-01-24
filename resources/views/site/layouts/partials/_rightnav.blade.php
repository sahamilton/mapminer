 
      <ul class="navbar-nav justify-content-end"> 

                
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
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-user" aria-hidden="true"></i> {{ucfirst(strtolower( auth()->user()->person->firstname ))}}<span class="caret"></span></a> 
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

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