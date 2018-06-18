@extends ('site.layouts.default')
@section('content')
<div class="container">
    <h2>Leads By Branches</h2>
   

    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>
                <th>Branch</th>
                <th>Branch Manager</th>
                <th>Reports To</th>
                <th>Open Leads</th>
                <th>Closed Leads</th>

                <th>Rating</th>

            </thead>
            <tbody>
                @foreach ($branches as $branch)
               
                <tr> 
                    <td><a href="{{route('templeads.branch',$branch->id)}}">{{$branch->branchname}}, {{$branch->city}} {{$branch->state}}</a></td>
                   @if(count($branch->manager)>0)

                        <td>{{$branch->manager->first()->postName()}}</td>
                       
                        <td> @if(count($branch->manager->first()->reportsTo)>0)
                            {{$branch->manager->first()->reportsTo->postName()}}
                            @endif
                        </td>
                    @else
                        <td></td><td></td>
                    @endif
                    <td>{{count($branch->templeads)}}
                    <td></td>
                    <td></td>

                </tr>  

                @endforeach
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
