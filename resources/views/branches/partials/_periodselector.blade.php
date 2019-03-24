<form method="post" action ="{{route('dashboard.setperiod')}}">
	@csrf
	<div class="form-group col-sm-4">
		<label>Period</label>
		<select class="form-control" name="period"  onchange="this.form.submit()">
		@foreach (config('mapminer.timeframes') as $key=>$period)
			<option {{$key == $data['period']['period'] ?  'selected' : ''}} 
			value="{{$key}}">{{$period}}</option>
		@endforeach
		</select>
	</div>
</form>