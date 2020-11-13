<p>for the period from {{$data['period']['from']->format('Y-m-d')}} to {{$data['period']['to']->format('Y-m-d')}}</p>
<div class="form">
	<form method="post" 
	class="inline" 
	action ="{{route('period.setperiod')}}">
	@csrf
		<div class="form-group row col-sm-8 inline align-middle">
			<div class="input-group-prepend">
      			<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
    		</div>
			<select class="" 
				name="period"  
				onchange="this.form.submit()">
				@foreach (config('mapminer.timeframes') as $key=>$period)
					<option {{$key == $data['period']['period'] ?  'selected' : ''}} 
					value="{{$key}}">{{$period}}</option>
				@endforeach
			</select>
			
		</div>
	</form>
</div>
