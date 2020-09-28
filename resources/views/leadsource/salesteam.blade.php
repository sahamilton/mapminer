@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Notify Branches</h2>
<h3>for the {{$source->title}} leads</h3>
<h4>from {{$source->datefrom->format('M j, Y')}} to {{$source->dateto->format('M j, Y')}}</h4>

<!---- Tab message -->
<ul class="nav nav-tabs">
  <li class="nav-item ">
    <a class="nav-link active" 
      data-toggle="tab" 
      href="#home">Message
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  data-toggle="tab" href="#menu1">
      Branches ({{$data['branches']->count()}})
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  data-toggle="tab" href="#menu2">
      Sales Teams 
    </a>
  </li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade show active">
    <div style="border:solid 1px red">

    {!! $message !!}



    </div>
    <form id="campaignmessage" action="{{route('sendleadsource.message',$source->id)}}" method="post">
        {{csrf_field()}}
        <div class="form-group form-inline">
          
          <label for="Test">Test</label>
          <input 
            type="checkbox" 
            checked 
            name="test" 
            value="1" 
            class="form-control" />
        
          
          <label for="Notify Manager">Notify Managers</label>
          <input 
            type="checkbox" 
            checked 
            name="managers" 
            value="1" 
            class="form-control" />
        </div>
         <div class="form-group">
        <button class='disabled'>Edit Text</button>
        <div id='message' style="display:none" class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
        <label for="description" class="form-control">Campaign Message</label>

        <textarea id ="summernote" required class='summernote form-control' data-error="Please provide some description of this campaign" name="message">{!! old('message') ? old('message') :  $message !!}</textarea>
        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
        </div>
        <input type="submit" value="Send message to team" />
      </div>
    </form>
  </div>
  <div id="menu1" class="tab-pane fade">
  <!---- Tab team -->
    <table id="sorttable" class="table table-striped">
        <thead>
        <tr>
        <th>Branch</th>
        <th>Lead Count</th>
        <th>Branch Manager</th>
        <th>Reports To</th>

        </tr>
        </thead>


        @foreach ($data['branches'] as $branch)
        <tr>
        <td>{{$branch->branchname}}</td>
        <td>{{$data[$branch->id]}}</td>
        <td>
        @foreach ($branch->manager as $manager)

            {{$manager->fullName()}}<br />
        @endforeach
        </td>
        <td>
          @foreach ($branch->manager as $manager)
            @if(isset($manager))
             {{ $manager->reportsTo->fullName()}};
            @endif
          @endforeach
        </td>

        </tr>
        @endforeach
        </tbody>
    </table>
  </div>

  <div id="menu2" class="tab-pane fade">
  
  </div>


</div>
</div>
@include('partials._scripts')
<script>
$('#summernote').summernote({
	 airMode: true                // set focus to editable area after initializing summernote

});
</script>
@endsection
