<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

        <th>Branch</th>
        <th>Number</th>
        
        <th>Branch Address</th>
        <th>City</th>
        <th>State</th>
        <th>Manager</th>
        <th>Remove</th>
     
    </thead>
    <tbody>
   @foreach($activity->campaignBranches as $branch)
    <tr>  
 
    
    <td>
        <a href="{{route('branches.show',$branch->id)}}" 
         title="See details of branch {{$branch->branchname}}">
        {{$branch->branchname}}
        </a>
    </td>
    
    <td>{{$branch->id}}</td>

    <td>{{$branch->street}} {{$branch->suite}}</td>

    <td>{{$branch->city}}</td>

    <td>{{$branch->state}}</td>

    <td>            
            @if($branch->manager->count()>0)
                
                @foreach ($branch->manager as $manager)
                <a href="{{route('managed.branch',$manager->id)}}"
                title="See all branchesmanaged by {{$manager->fullName()}}">
                {{$manager->fullName()}}</a>

                @endforeach
            @endif
    </td>
    
    <td><a href=""><i class="fas fa-trash-alt text-danger"></i></a></td>
    
    </tr>
   @endforeach
    
    </tbody>
    </table>