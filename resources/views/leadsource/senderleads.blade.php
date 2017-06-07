@extends ('admin.layouts.default')
@section('content')
<div class="container">

<h2>New <a href="{{route('leadsource.show',$data['source']->id)}}">{{$data['source']->source}}</a> Leads Notified</h2>


<p>The following message has been sent to {{$data['count']}} members of the sales organization and their managers:</p>

<div class="panel panel-default">
<div class="panel-body"><p>{!! $data['message'] !!}</p>

<button type="button" a href="{{route('salesleads.index')}}" class="btn btn-primary">
        Check out the {{$data['source']->source}} leads and resources.
</button>

<p><em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('salesleads.index')}}]({{ route('salesleads.index')}}) </em></p>
</div>
Sincerely
        
{{env('APP_NAME')}}
</div>
</div>
@endsection