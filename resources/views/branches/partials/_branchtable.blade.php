@foreach($branches as $branch)
    <tr>  
        <td>
            <a href="{{route('branches.show',$branch->id)}}" 
             title="See details of branch {{$branch->branchname}}">
                {{$branch->branchname}}
            </a>
        </td>
        
        

        <td>
        @if($branch->servicelines->count()>0)
            @foreach($branch->servicelines as $serviceline)
                
                <a href="{{route('serviceline.show',$serviceline->id)}}" 
                title="See all {{$serviceline->ServiceLine}} branches">
                    {{$serviceline->ServiceLine}}
                </a>
            @endforeach
        @endif
        </td>

        <td>{{$branch->street}} {{$branch->suite}}</td>

        <td>{{$branch->city}}</td>

        <td>
                <a href="{{route('branches.statelist',$branch->state)}}"
                 title="See all {{$branch->state}} state branches">
                    {{$branch->state}}
                </a>

        </td>

        <td>
                @if(!is_null($branch->region))
                    <a href="{{route('region.show',$branch->region->id)}}"
                    title="See all {{$branch->region->region}} region branches">
                    {{$branch->region->region}}
                    </a>
                @endif
                

        </td>
        <td>            
                @if($branch->manager->count()>0)
                    
                    @foreach ($branch->manager as $manager)
                    <a href="{{route('managed.branch',$manager->id)}}"
                    title="See all branchesmanaged by {{$manager->fullName()}}">
                    {{$manager->fullName()}}</a>

                    @endforeach
                @endif
        </td>
        @can('manage_branches')
        <td>
        
                
        
                <div class="btn-group">
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                
                    <a class="dropdown-item"
                        href="{{route('branches.edit',$branch->id)}}"><i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$branch->branchname}} Branch
                    </a>
                    <a class="dropdown-item"
                       data-href="{{route('branches.destroy',$branch->id)}}" data-toggle="modal" 
                       data-target="#confirm-delete" 
                       data-title = "{{$branch->branchname}} branch" 
                       href="#"><i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete {{$branch->branchname}} branch
                    </a>
                  </ul>
                </div>
            
            
        </td>
        @endcan
    </tr>
   @endforeach