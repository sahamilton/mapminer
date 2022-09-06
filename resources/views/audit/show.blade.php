@extends('admin/layouts.default')
@section('content')
    <div class="container">
        @switch(get_class($model))
            @case('App\Models\User')
                <h2><a href="{{route('person.details', $model->person->id)}}">{{$model->person->fullName()}}</a></h2>
            @break

            @case ("App\Models\Person")
                <h2><a href="{{route('person.details', $model->id)}}">{{$model->fullName()}}</a></h2>
            @break
        @endswitch
        <p>{{$audit->event}} by {{$audit->user->person->fullName()}}</p>
        <p>{{$audit->created_at->format('Y-m-d h:i a e')}}
            <h4>Changes</h4>
        <table>
            <tr>
                <td>
                  <table class="table">
                    @foreach($audit->old_values as $attribute => $value)
                      <tr>
                        <td><b>{{ $attribute }}</b></td>
                        <td>{{ $value }}</td>
                      </tr>
                    @endforeach
                  </table>
                </td>
                <td>
                  <table class="table">
                    @foreach($audit->new_values as $attribute => $value)
                      <tr>
                        <td><b>{{ $attribute }}</b></td>
                        <td>{{ $value }}</td>
                      </tr>
                    @endforeach
                  </table>
                </td> 
            </tr>
        </table>
    </div>
@endsection