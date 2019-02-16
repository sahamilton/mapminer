@extends ('admin.layouts.default')
@section('content')
<div class="container">

<h2>New <a href="{{route('leadsource.show',$leadsource->id)}}">{{$leadsource->source}}</a> Leads Notified</h2>


<p>The following message has been sent to the managers of {{$data['count']}} branches:</p>

<div class="card card-default">
<div class="card-body"><p>{!! $data['message'] !!}</p>

<button type="button" a href="{{route('salesleads.index')}}" class="btn btn-primary">
        Check out the {{$leadsource->source}} leads and resources.
</button>

<p><em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('branchleads.index')}}]({{ route('branchleads.index')}}) </em></p>
</div>
Sincerely
        
{{env('APP_NAME')}}
</div>
</div>
@endsection