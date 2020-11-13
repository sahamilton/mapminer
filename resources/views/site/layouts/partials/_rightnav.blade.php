
      <ul class="flex justify-between items-center h-12 w-88 mt-2 mr-16 p-3"> 
              
               <li class="nav-item">
                 <button type="button"
                    data-href="" 
                    data-toggle="modal" 
                    data-target="#add-feedback" 
                    class="nav-link text-gray-600 group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900"
                    >
                      <span class="text-sm">Feedback</span>
                 </button>
                <!-- <a  class="nav-link" href="#"
                data-href="" 
                data-toggle="modal" 
                data-target="#add-feedback" >
                    <i class="" style=""> </i> Feedback</a> -->
                </li>
                @php $news = new \App\News;@endphp
                <li class="nav-item">
                  
                  @if($news->currentNews()->count()>0)
                  <a href="{{route('currentnews')}}"
                      class="nav-link text-gray-600 group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900" 
                      aria-hidden="true">
                          <span class="text-sm">News</span>
                  </a>
                  @else
                  <a href="{{route('news.index')}}"
                      class="nav-link text-gray-600 group inline-flex items-center space-x-2 text-base leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900" 
                      aria-hidden="true">
                         <span class="text-sm">News</span>
                  </a>
                  <!-- <a  class="nav-link" href="{{route('news.index')}}">
                    <i class="" aria-hidden="true"> </i> News</a> -->
                  @endif
                   
                </li>
                
                @if (auth()->user()->hasRole('admin'))

                <li class="nav-item">
                  <a  class="nav-link text-gray-600 group inline-flex items-center space-x-2 text-sm leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900" href="{{{ route('dashboard') }}}">
                  <i class="" aria-hidden="true"> </i> Admin </a>
                </li>
                @endif

                @if (auth()->user()->hasRole('sales_operations'))

              <li class="nav-item">
                <a  class="nav-link text-gray-600 group inline-flex items-center space-x-2 text-sm leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900" href="{{{ route('dashboard') }}}">
                <i class="" aria-hidden="true"> </i> Ops </a>
              </li>
                @endif
           
            <li class="nav-item dropdown">

                <button 
                    type="button"
                    class="text-sm ml-2 text-gray-600 group inline-flex items-center space-x-2 leading-6 font-medium hover:text-gray-900 focus:outline-none focus:text-gray-900 transition ease-in-out duration-150" id="navbarDropdownMenuLink"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                    <!-- <img src="{{asset('/storage/avatars/'.auth()->user()->person->avatar) ? asset('/storage/avatars/'.auth()->user()->person->avatar) : ''}}" 
                    id="profile-avatar"
                    width='30px'
                    height='30px'  
                    style="border-radius:50%;"/>  -->
                        {{ucfirst(strtolower( auth()->user()->person->firstname ))}}
                      <svg 
                        class="text-gray-400 h-5 w-5 group-hover:text-gray-500 group-focus:text-gray-500  transition      ease-in-out duration-150"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"   
                        fill="currentColor">
                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                </button>
                
                  <!-- <a class="nav-link dropdown-toggle"
                    href="#"
                    id="navbarDropdownMenuLink"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false">
                  
                    <img src="{{asset('/storage/avatars/'.auth()->user()->person->avatar)}}" 
                      id="profile-avatar"
                      width='30px'
                      height='30px'  
                      style="border-radius:50%;"/> 
                    {{ucfirst(strtolower( auth()->user()->person->firstname ))}}
                    <span class="caret"></span>
                </a>  -->
                <div class="dropdown-menu whitespace-normal p-3 right-10" aria-labelledby="navbarDropdownMenuLink">

                  <a class="dropdown-item p-2" href="{{{ route('user.show',auth()->user()->id) }}}">
                    <i class="far fa-user"
                      aria-hidden="true">
                    </i> 
                        Your Profile
                  </a>
                  <a class="dropdown-item p-2" href="{{route('about')}}">
                    <i class="fas fa-info-circle"
                      aria-hidden="true">
                    </i>  
                      About Mapminer
                  </a>
                  @can('service_branches')
                    
                  <a class="dropdown-item p-2"
                      href="{{{ route('branchassignments.show',auth()->user()->id) }}}">
                      <i class="fas fa-search-location">
                      </i> My Branch Assignments
                  </a>
                    
                    @endcan
                  <a class="dropdown-item p-2"
                    href="{{ route('logout') }}" 
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                      <i class="fas fa-sign-out-alt" aria-hidden="true">
                      </i>
                        Logout
                  </a>
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
        @include('partials._quickaddmodal')