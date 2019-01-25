@extends('site/layouts/default')
@section('content')

<h2> {{$people->firstname}} {{$people->lastname}}</h2>
<h4> {{$people->userdetails->roles[0]->name}}</h4>

@if ($people->reportsTo)
<p>Reports to: <a href = "{{route('person.show',$people->reportsTo->id)}}" > {{$people->reportsTo->firstname}} {{$people->reportsTo->lastname}} - {{$people->reportsTo->userdetails->roles[0]->name}}</a>
@endif

<p><a href="mailto:{{$people->email}}" title="Email {{$people->firstname}} {{$people->lastname}}">{{$people->email}}</a> </p>
<h4>{{$people->firstname}} {{$people->lastname}}'s Sales Team</h4>


  <p><a href="{{route('showmap.person',$people->id)}}"><i class="far fa-flag" aria-hidden="true"></i> Map View</a></p>    


<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
   <th>Name</th>
   <th>Role</th>
   <th>Branches Serviced</th>
  
       
    </thead>
    <tbody>
   
   @foreach($people->directReports as $reports)
    <tr>  

    <td>

         <a href="{{route('person.show',$reports->id)}}">
        {{$reports->fullName()}}
        </a>

        <span type="button" class="far fa-copy btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" data-copy="{{$reports->postName()}}" title="Copy to clipboard"></span>

   

   </td>
   <td>
        @foreach($reports->userdetails->roles as $role)
            <li>{{$role->display_name}}</li>
        @endforeach
        
    </td>
    <td>
    @if($reports->isLeaf())
       {{count($reports->branchesServiced)}}
    
    @else

        <?php 
        $branchCount =array();
        $branches = $reports->descendants()->with('branchesServiced')->get();
        foreach ($branches as $branch)
        {
            foreach ($branch->branchesServiced as $branchServiced)
            {
                if(! in_array($branchServiced->id,$branchCount))
                        {
                            $branchCount[] =$branchServiced->id;
                        }
            }
             
        }

        ?>
        {{count($branchCount)}}
    @endif
    </td>
   
    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')
@endsection
