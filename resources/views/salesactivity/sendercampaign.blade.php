@extends ('admin.layouts.default')
@section('content')
<div class="container">

<h2>{{$data['activity']->title}}</h2>


<p>The following message has been sent to {{count($data['salesteam'])}} members of the sales organization and their managers:</p>

<div class="panel panel-default">
<div class="panel-body"><p>{!! $data['message'] !!}</p>

<button type="button" a href="{{route('salesactivity.show',$data['activity']->id)}}" class="btn btn-primary">
        Check out the {{$data['activity']->title}} campaign resources.
</button>

<p><em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('salesactivity.show',$data['activity']->id)}}]({{ route('salesactivity.show',$data['activity']->id)}}) </em></p>
</div>
Sincerely
        
{{env('APP_NAME')}}
</div>
</div>
@endsection