
    
<table 
    class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

        <th>
            <a wire:click.prevent="sortBy('branchname')" role="button" href="#">
                Branch
                @include('includes._sort-icon', ['field' => 'branchname'])
            </a>
            
        </th>

       
      
        <th>Branch Address</th>
        
       
        <th>Manager</th>
        <th>Email</th>

        <th>
            <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                Distance
             @include('includes._sort-icon', ['field' => 'distance'])
            </a>
        </th>
    

       
    </thead>
    <tbody>
        @foreach($branches as $branch)
            <tr>  
                <td>
                    <a href="{{route('branches.show',$branch->id)}}" 
                     title="See details of branch {{$branch->branchname}}">
                        {{$branch->branchname}}
                    </a>
                </td>
                
                

                

                <td>{{$branch->fullAddress()}}</td>


                <td>            
                        
                            
                        @foreach ($branch->manager as $manager)
                        <a href="{{route('managed.branch',$manager->id)}}"
                        title="See all branches managed by {{$manager->fullName()}}">
                        {{$manager->fullName()}}</a>

                        @endforeach
                       
                </td>
                <td>
                        @foreach ($branch->manager as $manager)
                           
                            <a href="mailto:{{$manager->userdetails->email}}">{{$manager->userdetails->email}}</a>

                        @endforeach

                </td>
                <td>
                    
                        {{$branch->distance ? number_format($branch->distance,1). ' miles' :''}}
                </td>
                

            </tr>
        @endforeach
    </tbody>
</table>


