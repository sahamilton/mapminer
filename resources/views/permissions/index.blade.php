@extends('layouts.app')

@section('content')
<div class="container">
<h2>Permissions</h2>
<div class="container">

<div class="pull-right">
        <a href ="{{route('permission.create')}}"><button class="btn btn-success" >Add permission
        </button></a>
    </div>    
   
        
<div class="col-md-10 col-md-offset-1">
        <div id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>Name</th>
                <th>Roles</th>
                
                <th>Actions</th>
                
            </thead>
            <tbody>
            @foreach ($permissions as $permission)
        
                <tr> 
                
                <td>{!! $permission->name !!}</td>
               
                <td>
                    <ul>
                    @foreach ($permission->roles as $role)
                        <li>{{$role->name}}</li>
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

                    <li><a href="{{route('permission.edit',$permission->id)}}"><i class="fa fa-pencil" aria-hidden="true"></i>Edit permission</a></li>

                    <li><a data-href="{{route('permissions.destroy',$permission->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete permission</a>
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
 @include('partials._modal')

@endsection
