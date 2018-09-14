@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Prospect Source Sales Team</h2>
<h3>for the {{$source->title}} leads</h3>
<h4>from {{$source->datefrom->format('M j, Y')}} to {{$source->dateto->format('M j, Y')}}</h4>
<!---- Tab message -->
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Message</a></li>
  <li><a data-toggle="tab" href="#menu1">Sales Team ({{$salesteam->count()}}</a></li>
  

</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
  <div style="border:solid 1px red">

{!! $message !!}



  </div>
  <form id="campaignmessage" action="{{route('sendleadsource.message',$source->id)}}" method="post">
  {{csrf_field()}}
  <button class='disabled'>Edit Text</button>
	<div id='message' style="display:none" class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
	<label for="description">Campaign Message</label>

	<textarea required class='summernote' data-error="Please provide some description of this campaign" name="message">{!! old('message') ? old('message') :  $message !!}</textarea>
	{!! $errors->first('message', '<p class="help-block">:message</p>') !!}
	</div>
  <input type="submit" value="Send message to team" />
  </form>
</div>
  <div id="menu1" class="tab-pane fade">
<!---- Tab team -->
<table id="sorttable" class="table table-striped">
<thead>
<tr>
<th>Sales Rep</th>
<th>Manager</th>
<th>Location</th>
<th>Email</th>
</tr>
</thead>

<tbody>
@foreach ($salesteam as $team)
<tr>
<td>{{$team['details']->fullName()}}</td>
<td>
@if($team['details']->reportsTo)
	{{$team['details']->reportsTo->fullName()}}
@endif
</td>
<td>{{$team['details']->city}}, {{$team['details']->state}}</td>
<td>{{$team['details']->userdetails->email}}</td>

</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
@include('partials._scripts')
<script>
$('.summernote').summernote({
	  height: 300,                 // set editor height
		width: 500,
	  minHeight: null,             // set minimum height of editor
	  maxHeight: null,             // set maximum height of editor
	
	  focus: true,                 // set focus to editable area after initializing summernote
	  toolbar: [
    //[groupname, [button list]]
     
    ['style', ['bold', 'italic', 'underline', 'clear']],
	['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
    ['misc',['codeview']],
	
  ]
});
</script>
@endsection
