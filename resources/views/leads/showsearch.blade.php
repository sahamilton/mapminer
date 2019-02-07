@extends ('admin.layouts.default')
@section('content')
<div class="container">
  <h2>Assign Leads</h2>
  <p><a href="{{route('leads.search')}}">Enter new leads</a></p>
  <div class="row">

    @if(isset($lead->id))                          
   <a 
   data-href="{{route('address.destroy',$lead->id)}}" 

        data-toggle="modal" 
        data-target="#confirm-delete" 
        data-title = "this lead" 
        href="#">

        <i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
        Delete Lead
    </a>


  </div>
  @endif
  <div class="row">
    <div class="col-sm-6">
      @include('leads.partials._search')
      <div class="panel panel-default">
        <form action="{{route('myleads.store')}}" method="post">
          {{csrf_field()}}

          @include('leads.partials._form')
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
            <input type="hidden" name="addressable_type" value="weblead" />
            <input type="submit" name="submit" class="btn btn-info" value="Distribute Lead" />
          </div>
        </form>  
      </div>
    </div>
    <div class="col-sm-6"> 
      <div id="map"  style="width:100%;border:solid 1px red">
        
      </div>
      		@include('leads.partials._branchlist')	
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
@include('partials/_scripts')
@endsection

