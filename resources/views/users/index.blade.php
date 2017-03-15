@extends('layouts.app')

@section('content')
<div class="container">
<h2>Users</h2>
<div class="container">

<div class="pull-right">
        <a href ="{{route('users.create')}}"><button class="btn btn-success" >Add User
        </button></a>
    </div>    
   
<?php $fields = ['Quote','Attribution','Source','Actions'];?>
        
<div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
                
            </thead>
            <tbody>
            @foreach ($users as $user)
        
                <tr> 
                
                <td>{!! $user->name !!}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach ($user->roles as $role)
                        <li>{{$role->name}}</li>
                    @endforeach

                </td>
                
                
                 <td class="col-md-2">
                @include('partials/modal')

                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('users.edit',$user->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit user</a></li>

                    <li><a data-href="{{route('users.purge',$user->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="glyphicon glyphicon-trash"></i> Delete user</a>
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
