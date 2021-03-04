@extends('admin.layouts.default')
@section('content')

<div class="container">
    <h2>Export Branch Managers Data</h2>
    <form class="form"
        method="post"
        action="{{route('export.store')}}"
    >
    @csrf
        <div class="form-group {{ $errors->has('people') ? ' has-error' : '' }}">
              <label class="col-md-4 control-label">Select Manager:</label>
              <div class="input-group input-group-lg">
                <select class="form-control" 
                      multiple
                      name="people[]"  
                      >
                      @foreach ($people as $person)

                        <option value="{{$person->id}}">{{$person->fullName()}}</option>

                      @endforeach

                </select>
                  <span class="help-block">
                      <strong>{{$errors->has('people') ? $errors->first('people')  : ''}}</strong>
                  </span>
              </div>          
        </div>
        <input type="submit" name="submit" class="btn btn-success" value="Export Data" />
    </form>
</div>


@endsection