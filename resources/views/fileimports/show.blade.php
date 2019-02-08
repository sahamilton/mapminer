@extends('admin.layouts.default')
@section('content')

<h4> Imported Data </h4>
<p><a href="{{route('fileimport.index')}}">Return to all imports</a></p>
      <p><strong>Created:</strong>{{$import->created_at->format('Y-m-d')}}</p>
      <p><strong>Reference:</strong>{{$import->ref}}</p>
      <p><strong>Type:</strong>{{$import->type}}</p>
      <p><strong>Imported By:</strong>{{$import->user->person->fullName()}}</p>
      <p><strong>Address Count:</strong>{{$import->addresses()->count()}}</p>
      <p><strong>Assigned Count:</strong>{{$import->addresses()->assignedToBranches()->count()}}
      <p><strong>Description:</strong>{{$import->description}}</p>

    <div id="assign" >
      <form name="assigntoBranch" method="post" action="{{route('fileimport.assign',$import->id)}}">
        <div class="form-group">
          <input type="submit" name="assign" class="btn btn-info" value="Assign to branches"> 
        </div>
        <div class="form-group" >
          <label for="distance">Distance</label>
          <select name="distance" >
            @php $distances = [10,25,50]; @endphp
            @foreach ($distances as $distance)
            <option value="{{$distance}}" @if($diatance ==25) selected @endif>{{$distance}} miles</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
                <label class="checkbox-inline " for="password_confirmation">Notify Managers</label>
               
                  <input 
                    class="checkbox-inline " 
                    type="checkbox" 
                    name="notify" 
                    id="notify" 
                    value="1"/>
              </div>
            
        @csrf
        
      </form>
    </div>

@include('partials._modal')
@include('partials._scripts')

@endsection