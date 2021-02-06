@extends('site.layouts.default')

{{-- Content --}}
@section('content')
<div class="container">
@if($persons->count()==1)
<h2>Select TimeFrame to Track</h2>
@else
<h2>Select User and TimeFrame to Track</h2>
@endif

    <form action="{{route('usertracking.show')}}"
        method="post" >
        @csrf

        <!-- select time frame -->
        <div class="form-group row col-sm-8 inline align-middle">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
            </div>
            <select class="" name="setPeriod" >
            @foreach (config('mapminer.timeframes') as $key=>$date)
                <option 
                value="{{$key}}">{{$date}}</option>
            @endforeach
            </select>
            
        </div>
        @if($persons->count()==1)

        <input type="hidden" name="person" 
            value="{{$persons->first()->user_id}}" />
        @else
        <div class="form-group{{ $errors->has('person)') ? ' has-error' : '' }}">
            <label class="col-md-3 control-label">User</label>
            <div class="col-md-6">
                <select class="form-control" name='person' required>
                   
                @foreach ($persons as $person))
                <option
                    value="{{$person->user_id}}">
                        {{$person->postName()}}
                </option>
                    
                @endforeach


                </select>
                <span class="help-block{{ $errors->has('person)') ? ' has-error' : '' }}">
                    <strong>{{ $errors->has('person') ? $errors->first('person') : ''}}</strong>
                    </span>
            </div>
        </div>

        
        @endif
        <input type="submit" class="btn btn-info" value = "Track Users Actions" />

    </form>

</div>


@endsection
