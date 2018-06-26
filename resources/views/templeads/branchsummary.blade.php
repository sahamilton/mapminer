@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Leads By Branches</h2>
   @if($leadsource)
   <h4>From {{$leadsource->source}} lead source</h4>
   @endif
<h4><a href="{{route('leadsource.show',$leadsource->id)}}">See {{$leadsource->source}} by Reps</a></h4>
    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>
                <th>Branch</th>
                <th>Branch Manager</th>
                <th>Reports To</th>
                <th>Leads</th>
                

                <th>Rating</th>

            </thead>
            <tbody>
                @foreach ($branches as $branch)
          
                <tr> 
                    <td><a href="{{route('templeads.branchid',$branch->id)}}">{{$branch->branchname}}, {{$branch->city}} {{$branch->state}}</a></td>
                   @if(count($branch->manager)>0)

                        <td><a href="{{route('branchmgr.newleads',$branch->manager->first()->id)}}">{{$branch->manager->first()->postName()}}</a></td>
                       
                        <td> @if(count($branch->manager->first()->reportsTo)>0)
                            {{$branch->manager->first()->reportsTo->postName()}}
                            @endif
                        </td>
                    @else
                        <td></td><td></td>
                    @endif
                    <td>{{$branch->leads_count}}</td>
                    
                    <td></td>

                </tr>  

                @endforeach
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
