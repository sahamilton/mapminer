<h2>Closest Sales Reps </h2>

@if($people)

{{csrf_field()}}
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>

            <th>Name</th>
            
            <th>Industry Focus</th>
            <th>Branches</th>

            <th>Distance</th>

        </thead>
        <tbody>
        @foreach($people  as $person)
            <tr> 

                <td><a href="{{route('salesorg',$person->id)}}"  
                    title="See {{$person->postName()}}'s details">{{$person->postName()}}</a>
<<<<<<< HEAD
                    <span type="button" class="fa fa-copy btn-copy js-tooltip js-copy" 
=======
                    <span type="button" class="far fa-copy btn-copy js-tooltip js-copy" 
>>>>>>> development
                    data-toggle="tooltip" 
                    data-placement="bottom" 
                    data-copy="{{$person->postName()}}" 
                    title="Copy to clipboard"></span>
<<<<<<< HEAD
                    <!-- <a class="fa fa-copy"  onclick="myFunction('#{{$person->id}}')" title="Copy {{$person->postName()}}"></a></td>-->
=======
                    <!-- <a class="far fa-copy"  onclick="myFunction('#{{$person->id}}')" title="Copy {{$person->postName()}}"></a></td>-->
>>>>>>> development

                   
                    </p>
                <td>
                    <ul style="list-style-type: none;padding-left:0" >
                    @foreach($person->industryfocus as $industry)
                        <li>{{$industry->filter}}</li>
                    @endforeach
                    </ul>
                </td> 
                <td>
                    <ul style="list-style-type: none;padding-left:0" >
                    @foreach($person->branchesServiced as $branch)
                        <li>{{$branch->branchname}}</li>
                    @endforeach
                    </ul>
                </td> 
                <td>{{number_format($person->distance,2)}}</td> 
               
            </tr>
        @endforeach
        </tbody>
    </table>


@endif
