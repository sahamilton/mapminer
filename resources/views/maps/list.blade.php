@extends('site.layouts.default')


@if ($data['type'] == 'branch')
	<?php $fields = array('Branch Name'=>'branchname','Service Line'=>'servicelines','Address'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Miles'=>'distance_in_mi'); 
?>
@else
<?php $fields = array('Business Name'=>'businessname','National Acct'=>'companyname','Address'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Miles'=>'distance_in_mi','Watch'=>'watch_list'); 
?>

@endif
@section('content')


 

<h1>{{$data['title']}}</h1>

{!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
<p><a href="/watch" title="Review my watch list"><i class="glyphicon glyphicon-th-list"></i> View My Watch List</a></p>
<p><a href="/watchexport" title="Download my watch list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Download My Watch List</a> </p>

@include('maps/partials/_form')
@include('partials.advancedsearch')

@if($data['type']=='branch')
	@include('maps.branchlist')
@else
    @include('maps.accountlist')
@endif    
@include('partials/_scripts')

@stop
