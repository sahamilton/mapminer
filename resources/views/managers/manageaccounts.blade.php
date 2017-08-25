@extends('site/layouts/default')
@section('content')
<div class="container">
<h1>{{$data['title']}}</h1>
@if (Auth::user()->hasRole('National Account Manager'))
<h3>Your Accounts</h3>
@endif
<form method="post" action="{{route('managers.view')}}" class="form" id="selectAccount">
<!-- {{Form::open(array('route'=>'managers.view','class'=>'form', 'id'=>'selectAccount'))}}-->
{{csrf_field()}}
@if (Auth::user()->hasRole('Admin')) 

<div class="row">

    <div class="form-group{{ $errors->has('manager)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Managers:</label>
        <div class="col-md-6">
            <select multiple class="form-control" name='manager[]' id='selectManager' onchange="this.form.submit()">

            @foreach ($data['managerList'] as $key=>$manager))
              <option @if(isset($data['manager']['user_id']) && $data['manager']['user_id'] ==$key) selected @endif value="{{$key}}">{{$manager}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('manager') ? $errors->first('manager') : ''}}</strong>
                </span>
        </div>
    </div>

</div>

@endif
<div class="row">

</div>
<div class="row">
    <div class="form-group{{ $errors->has('accounts)') ? ' has-error' : '' }}">
    
        <label class="col-md-2 control-label">Accounts:<br />
        Check all:{{Form::checkbox('checkAll', 'yes', true,array('id'=>'checkAllAccounts'))}}</label>
        <div class="col-md-6">
            <select multiple class="form-control" name='accounts[]' id='selectAccounts' onchange="this.form.submit()">

            @foreach ($data['accounts'] as $key=>$account))
              <option @if(isset($data['selectedAccounts']) && in_array($key,$data['selectedAccounts'])) selected @endif value="{{$key}}">{{$account}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('accounts') ? $errors->first('accounts') : ''}}</strong>
                </span>
        </div>
    </div>

</div>

<!--{{Form::label('Accounts:','',array('class'=>'control-label col-sm-2'))}}
<div class="input-group date col-sm-4">
{{Form::select("accounts[]",$data['accounts'],$data['selectedAccounts'],array('multiple' => true, 'id'=>'selectAccounts','onchange' => 'this.form.submit()'))}}
<div></div>-->
<input type="submit" name="btnsubmit" value="Select" />
</form>
</div>
<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
@include('managers.partials._activewatchers')
</div>
<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
@include('managers.partials._locationnotes')
</div>
<div style="border:1px solid #000;width:440px;margin:20px;padding:20px;float:left">
@include('managers.partials._nocontactphone')
</div>
<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
@include('managers.partials._salesnotes')
</div>
@if(isset($data['segments']))
<div style="border:1px solid #000;width:350px;margin:20px;padding:20px;float:left">
  @include('managers.partials._segments')
</div>
@endif
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