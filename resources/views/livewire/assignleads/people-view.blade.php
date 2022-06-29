
    
<table 
    class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

        <th>
            <a wire:click.prevent="sortBy('lastname')" role="button" href="#">
               Person
                @include('includes._sort-icon', ['field' => 'lastname'])
            </a>
            
        </th>

       
      
        <th>Address</th>
        <th>Email</th>

        <th>Role</th>
        <th>Branches</th>
        <th>Manager</th>
        
        <th>
            <a wire:click.prevent="sortBy('distance')" role="button" href="#">
                Distance
             @include('includes._sort-icon', ['field' => 'distance'])
            </a>
        </th>
    

       
    </thead>
    <tbody>
        @foreach($people as $person)
            <tr>  
                <td>{{$person->fullName()}}</td>
                
                

                

                <td>{{$person->fullAddress()}}</td>


                <td> <a href="mailto:{{$person->userdetails->email}}">{{$person->userdetails->email}}</a>  </td>
                <td>
                        @foreach ($person->userdetails->roles as $role)
                           
                            {{$role->display_name}}

                        @endforeach

                </td>
                <td>
                        <ul style='list:none'>
                        @foreach ($person->branchesServiced as $serviced)
                           
                            <li>{{$serviced->branchname}}</li>

                        @endforeach
                    </ul>
                </td>
                <td>{{$person->reportsTo->fullName()}}</td>
                <td>
                    
                        {{$person->distance ? number_format($person->distance,1). ' miles' :''}}
                </td>
                

            </tr>
        @endforeach
    </tbody>
</table>


