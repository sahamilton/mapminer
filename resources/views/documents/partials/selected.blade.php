{{dd($request)}}
@if (isset($request['vertical']))

@foreach ($request['vertical']) as $vertical)
	{{dd($vertical)}}  |
@endforeach

@endif