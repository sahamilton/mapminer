@if(isset($myBranches) && count($myBranches)>1)
<form method='post' action="{{route($route)}}">
	@csrf
<div class="form-group">

<select name="branch"  onchange="this.form.submit()">
@foreach ($myBranches as $id=>$branchname)
<option @if(isset($branch) && $branch->id == $id) selected @endif value="{{$id}}">{{$branchname}}</option>
@endforeach
</select>
</div>
<form>
	@endif