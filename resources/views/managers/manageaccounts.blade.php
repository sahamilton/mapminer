@extends('site/layouts/default')
@section('content')
<div class="container">

<h1>{{$data['title']}}</h1>

<div style="margin-bottom: 20px" >
@include('managers.partials._form')
</div>
<hr />
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#watchers"><strong>Activity</strong></a></li>
<li><a data-toggle="tab" href="#health"><strong>Data Quality</strong></a></li>
  <li><a data-toggle="tab" href="#summary"><strong>Accounts Summary </strong></a></li>
  

</ul>

<div class="tab-content">
  <div id="watchers" class="tab-pane fade in active">

    <div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
    @include('managers.partials._activewatchers')
    </div>
    
    <div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
    @include('managers.partials._locationnotes')
    </div>
  </div>
  <div id="health" class="tab-pane fade in">
    <div style="border:1px solid #000;width:440px;margin:20px;padding:20px;float:left">
    @include('managers.partials._nocontactphone')
    </div>
    <div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
    @include('managers.partials._salesnotes')
    </div>
  </div>
  <div id="summary" class="tab-pane fade in">
    @if(isset($data['segments']))

    <div style="border:1px solid #000;width:450px;margin:20px;padding:20px;float:left">
    @include('managers.partials._segments')

    </div>
    @endif
  </div>
</div>

</div>
@include('partials/_scripts')
<script language="javascript">
$(function () {
	
	$('#checkAllAccounts').change(function(){
		if(this.checked){
			$("#selectAccounts option").prop("selected", "selected");
			//$("#selectAccounts option").setAttr("selected");
		}else{
			
			$("#selectAccounts option:selected").removeAttr("selected");
		}
	});
});

</script>
<script>$(function(){
	$("#selectall").click(function(){
		$('.case').attr('checked',this.checked);});
		$(".case").click(function(){
			if($(".case").length==$(".case:checked").length){
				$("#selectall").attr("checked","checked");
			}else{
				$("#selectall").removeAttr("checked");
			}
	});
});
</script>



@stop