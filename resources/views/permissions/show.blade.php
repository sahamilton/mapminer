@extends ('admin.layouts.default')

@section('content')
<div class="container">
<h2>{{$permission->display_name}} Permission</h2>
<h4>Roles that have this permission:</h4>
@foreach ($permission->roles()->get() as $role)
<li><a href="{{route('roles.show',$role->id)}}">{{$role->display_name}}</a></li>

@endforeach
</div>


@endsection
