@component('mail::message')
# Mapminer Summary Stats Report

For the period from {{$period['from']->format('jS M, Y')}} to {{$period['to']->format('jS M, Y')}} 
compared to {{$priorPeriod['from']->format('jS M, Y')}} to {{$priorPeriod['to']->format('jS M, Y')}} 

@component('mail::table')
| Statistic       | Current Period | Prior Period | Trend |
| ------------- | --------:| --------:| --------:|
@foreach ($data['current'] as $element=>$value)
| {{ucwords(str_replace("_"," ", $element))}} | {{number_format($value, 0)}} | {{number_format($data['prior'][$element],0)}} |<strong>{{$value > $data['prior'][$element] ? '↑' : '↓'}}</strong>
@endforeach
@endcomponent
If you no longer wish to receive this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
