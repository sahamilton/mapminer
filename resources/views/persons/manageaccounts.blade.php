@extends('site/layouts/default')
@section('content')

<h1>{{$data['title']}}</h1>
@if (Auth::user()->hasRole('National Account Manager'))
<h3>Your Accounts</h3>
@endif
{{Form::open(array('route'=>'managers.view','class'=>'form', 'id'=>'selectAccount'))}}

@if (Auth::user()->hasRole('Admin')) 
<div class="form-group">
{{Form::label('','Manager:',array('class'=>'control-label col-sm-2'))}}
<div class="input-group date col-sm-4">

{{Form::select("manager[]",$data['managerList'],isset($data['manager']['id']) ? $data['manager']['id'] : '',array('id'=>"selectManager",'onchange' => 'this.form.submit()'))}}
</div></div>
@endif

<div class="form-group">
Check all:{{Form::checkbox('checkAll', 'yes', true,array('id'=>'checkAllAccounts'))}}
{{Form::label('Accounts:','',array('class'=>'control-label col-sm-2'))}}
<div class="input-group date col-sm-4">
{{Form::select("accounts[]",$data['accounts'],$data['selectedAccounts'],array('multiple' => true, 'id'=>'selectAccounts','onchange' => 'this.form.submit()'))}}
<div></div>
{{Form::submit()}}
</div>
{{Form::close()}}

<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
<h4>Active Watchers</h4>
<a href="{{route('company.watchexport')}}?id={{strip_tags($data['accountstring'])}}" title="Download {{$data['title']}}watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download Watch List</a>

<table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Users
    </th>
   <th>
   Watching Locations
   </th>
       
    </thead>
    <tbody>
   @foreach($data['watching'] as $watchers)
<tr>
<td>
<a href="/watcher/{{$watchers['user_id']}}" >{{$watchers['name']}}</a>
</td>
<td>
{{$watchers['watching']}}
</td>

 </tr>
 
    @endforeach
    </tbody>
    </table>


</div>



<div style="border:1px solid #000;width:300px;margin:20px;padding:20px;float:left">
<h4>Companies with Location Notes</h4>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
  Notes
   </th>
       
    </thead>
    <tbody>
@foreach ($data['notes'] as $notes)

<tr>
<td>
<a href="{{route('locationnotes.show',$notes['companyid'])}}">{{$notes['companyname']}}</a>
</td>
<td>
{{$notes['notes']}}</td>
</tr>


@endforeach
    </tbody>
    </table>





</div><div style="clear:both"></div>
<div style="border:1px solid #000;width:500px;margin:20px;padding:20px;float:left">
<h4>Locations without Contact Phone</h4>
<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
   Locations
   </th>
    <th>
   Locations w/o Contact
   </th>
       <th>
   Percent of Total
   </th>
       
    </thead>
    <tbody>
   @foreach($data['nocontact'] as $nocontact)
   @if($nocontact['percent'] > '25')
   	<tr class="danger">
    @elseif ($nocontact['percent'] > '10')
    <tr class ="warning">
    @else
<tr class ='success'>
@endif
<td>
<a href="/company/{{$nocontact['company_id']}}" >{{$nocontact['companyname']}}</a>
</td>
<td>
{{$nocontact['locations']}}
</td>
<td>
{{$nocontact['nocontacts']}}
</td>
<td>
{{number_format($nocontact['percent']).'%'}}
</td>
 </tr>
 
    @endforeach
    </tbody>
    </table>


</div>
<div style="clear:both;border:1px solid #000;width:340px;padding:20px;margin:20px;float:left">
<h4>Company Sales Notes</h4>
 <table id ='sorttable3' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
   Notes
    </th>
       
    </thead>
    <tbody>
   @foreach($data['nosalesnotes'] as $company)
    @if (isset($company['notes']))
    <tr class="success"> 
    <td><a href="/salesnotes/{{$company['id']}}">{{$company['companyname']}}</a>  </td>
    <td><span style="color:green" class="glyphicon glyphicon-ok"> </span></td>
	@else
    <tr class='danger'>
    <td>{{$company['companyname']}}</td>
    <td><span style="color:red" class="glyphicon glyphicon-remove"> </span>No 'How to Sell' Notes</td>
    @endif
    
    
		
	
   
    
    
    
    
    </tr>
 
    @endforeach
    </tbody>
    </table>

</div>
</div>
@if(isset($data['segments']))
<div style="clear:both;border:1px solid #000;width:340px;padding:20px;margin:20px;float:left">
<h4>Company Locations By Segment</h4>
 <table id ='sorttable4' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    
    <th>
   Company
    </th>
   <th>
   Segment
    </th>
    <th>
   Location Count
    </th>
       
    </thead>
    <tbody>
    
   @foreach($data['segments'] as $segment)
   <tr>
  
   <td>{{$segment['companyname']}}</td>
   
   <td>{{$segment['filter'] ? $segment['filter'] : 'Not Assigned'}}</td>
   <td>{{$segment['count']}}</td></tr>
    
    
		
	
   
    
    
    
    
   
 
    @endforeach
    </tbody>
    </table>

</div>
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