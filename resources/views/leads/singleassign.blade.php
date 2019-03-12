@extends ('admin.layouts.default')
@section('content')
<div class="container">
  <h2>Assign Leads</h2>
  @if(isset($lead->id)) 
  <div class="col-sm-3 float-left">
    <div class="card">
      <div class="card-header">
        {{$lead->businessname}}
      </div>
      <div class="card-body">
        {{$lead->fulladdress()}}
      </div>
      <div class="card-footer">
       <p> <a 
          data-href="{{route('address.destroy',$lead->id)}}" 

          data-toggle="modal" 
          data-target="#confirm-delete" 
          data-title = "this lead" 
          href="#">

          <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
          Delete Lead
        </a></p>
        <p>
        <a href="{{route('leads.search')}}"><i class="fa fa-plus  text-info" aria-hidden="true"></i> Enter new leads</a>
      </p>
      </div>
    </div>
  </div>


  @endif

  <div class="row">
    <div class="col-sm-6">
      <div class="panel panel-default">
        <form action="{{route('leads.postassign',$lead->id)}}" method="post">
          {{csrf_field()}}

          @include('leads.partials._branchlist')  
          <div class="form-group {!! $errors->has('confirmed') ? 'has-error' : '' !!}">
            <label class="col-md-2 control-label" for="password_confirmation">Notify Managers</label>
            <div class="col-md-10">
              <input 
                class="form-control" 
                type="checkbox" 
                name="notify" 
                id="notify" 
                value="1"/>
          
            </div>
            <input type="submit" name="submit" class="btn btn-info" value="Distribute Lead" />
          </div>
        </form>  
      </div>
    </div>
    <div class="container">
      <div class="col-sm-8"> 
        <div id="map"  style="width:100%;border:solid 1px red">
          
        </div>
        	
      </div>
    </div>
  </div>

<!-- <script>
function myFunction(element) {

  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();

  document.execCommand("copy");
  $temp.remove();
  
}

</script>-->	
@include('leads.partials.map')
@include('partials._modal')
@include('partials._scripts')
@endsection

