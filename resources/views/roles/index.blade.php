@extends('admin.layouts.default')

@section('content')
<div class="container">
<h2>Roles</h2>
<div class="container">

<div class="pull-right">
        <a href ="{{route('roles.create')}}"><button class="btn btn-success" >Add role
        </button></a>
    </div>    
   

        
<div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                <th>Name</th>
                
                <th>Permissions</th>
                <th>Count</th>
                <th>Actions</th>
                
            </thead>
            <tbody>
            @foreach ($roles as $role)
        
                <tr> 
                
                <td>{!! $role->name !!}</td>
                
                <td>
                <ul>
                    @foreach ($role->permissions as $permission)
                        <li>{{$permission->name}}</li>
                    @endforeach
                </ul>
                </td>
                
                
                 <td class="col-md-2">
                @include('partials/modal')

                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('roles.edit',$role->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit role</a></li>

                    <li><a data-href="{{route('roles.purge',$role->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="glyphicon glyphicon-trash"></i> Delete role</a>
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
</div>
@endsection
