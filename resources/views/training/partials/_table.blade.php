    <table  class='table table-striped table-bordered table-condensed table-hover'>
 
            <thead>

            
                <th>
                    <a wire:click.prevent="sortBy('title')" role="button" href="#">
                            Title
                            @include('includes._sort-icon', ['field' => 'title'])
                    </a>
                </th>
                <th>Preview</th>
                <th>
                    <a wire:click.prevent="sortBy('datefrom')" role="button" href="#">
                            Date From
                            @include('includes._sort-icon', ['field' => 'datefrom'])
                    </a>
                </th>
                <th>
                    <a wire:click.prevent="sortBy('dateto')" role="button" href="#">
                            Date To
                            @include('includes._sort-icon', ['field' => 'dateto'])
                    </a>
                </th>
                <th>Status</th>
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
                    <td>{{$training->datefrom_for_humans}}</td>
                    <td>{{$training->dateto_for_humans}}</td>
                    <td>{{$training->status()}}</td>
                    <td>
                        <ul style="list-style-type: none">
                        @foreach ($training->relatedRoles as $role)
                            <li>{{$role->display_name}}</li>
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
                                Edit Training</a>
                               <a class="dropdown-item"
                               data-href="{{route('training.destroy',$training->id)}}" 

                                    data-toggle="modal" 
                                    data-target="#confirm-delete" 
                                    data-title = "location" 
                                    href="#">

                                    <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
                                    Delete Training Item
                                </a>
                              

                            </ul>
                        </div>

                    </td>
                </tr>  

                @endforeach
            </tbody>

        </table>
  



