@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Sales Process</h2>
    <div class="pull-right">
        <a href ="{{route('process.create')}}">
            <button class="btn btn-success" >
            Add Sales Step
            </button>
        </a>
    </div>    


    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                <th>Name</th>
                <th>Sequence</th>

                <th>Actions</th>

            </thead>
            <tbody>
                @foreach ($process as $step)

                <tr> 

                    <td>{{$step->step }}</td>

                    <td>{{$step->sequence}}</td>

                    <td class="col-md-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">

                                <li>
                                <a href="{{route('process.edit',$step->id)}}">
                                <i class="fa fa-pencil" aria-hidden="true"> </i>
                                Edit Sales Step</a>
                                </li>

                                <li>
                                <a data-href="{{route('process.destroy',$step->id)}}" 
                                    data-toggle="modal" 
                                    data-target="#confirm-delete" 
                                    data-title = "location" 
                                    href="#">
                                    <i class="fa fa-trash-o" aria-hidden="true"> </i> 
                                    Delete Sales Step
                                </a>
                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>  

                @endforeach
            </tbody>



        </table>
    </div>

</div>

@include('partials._modal')
@endsection
