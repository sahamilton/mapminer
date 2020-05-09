@extends('admin.layouts.default')
@section('content')
<div class="container" name="selectManagerDashboard" >
    <h2>Select Managers Dashboard</h2>
    <form id="selectManager"
        method="post"
        action = "{{route('dashboard.select')}}">
        @csrf
          <div class="form-group"> 
            <label for="manager">Manager:</label>
            <select
                onchange="this.form.submit()"
                name="manager"
                >
                @foreach ($managers as $manager)
                    <option value= "{{$manager->id}}">{{$manager->fullName()}}</option>
                @endforeach
            </select>


        </form>
@endsection
