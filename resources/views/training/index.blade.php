@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Trainings</h2>
    <div class="pull-right">
        <a href ="{{route('training.create')}}">
            <button class="btn btn-success" >
            Add Training Item
            </button>
        </a>
    </div>    


    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
 
            <thead>

            
                <th>Title</th>
                <th>Preview</th>
                <th>Date From</th>
                <th>Date To</th>
                <th>Roles</th>
                <th>Industries</th>
                <th>Servicelines</th>

                <th>Actions</th>

            </thead>
            <tbody>
                @foreach ($trainings as $training)

                <tr> 

                    <td>{{$training->title }}</td>
                    <td><a href="{{route('training.show',$training->id)}}">View</a></td>
                    <td>{{$training->datefrom}}</td>
                    <td>{{$training->dateto}}</td>
                    <td>
                        <ul style="list-style-type: none">
                        @foreach ($training->relatedRoles as $role)
                            <li>{{$role->name}}</li>
                        @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul style="list-style-type: none">
                        @foreach ($training->relatedIndustries as $industry)
                            <li>{{$industry->filter}}</li>
                        @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul style="list-style-type: none">
                        @foreach ($training->servicelines as $serviceline)
                            <li>{{$serviceline->ServiceLine}}</li>
                        @endforeach
                        </ul>
                    </td>

                    <td class="col-md-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">


                               <a class="dropdown-item"
                               href="{{route('training.edit',$training->id)}}">
                                <i class="far fa-edit text-info"" aria-hidden="true"> </i>
                                Edit Sales Step</a>
                               <a class="dropdown-item"
                               data-href="{{route('training.destroy',$training->id)}}" 

                                    data-toggle="modal" 
                                    data-target="#confirm-delete" 
                                    data-title = "location" 
                                    href="#">

                                    <i class="far fa-trash-o text-danger" aria-hidden="true"> </i> 
                                    Delete Training Item
                                </a>
                              

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
@include('partials._scripts')
@endsection
