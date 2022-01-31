@component('mail::message')
# Deleted Users

{{$manager->firstname}}

Your direct reports, listed below, have been deleted from Mapminer.  
@foreach ($manager->directReports as $report)
## {{$report->fullname()}}
deleted {{$report->deleted_at ? $report->deleted_at->format('Y-m-d') : now()->format('Y-m-d')}}  
@if($report->directReports->count())
### {{$report->firstname}}'s current direct reports in Mapminer:###
@foreach ($report->directReports as $remaining)
@if(! $remaining->deleted_at) 
- {{$remaining->fullName()}}
@endif 
@endforeach

Please reassign {{$report->firstname}}'s direct reports to a new manager as soon as possible.
@endif
@if($report->manages->count())
### {{$report->firstname}}'s branches ###
@foreach($report->manages as $branch)
- {{$branch->branchname}}
@endforeach

Please reassign {{$report->firstname}}'s branches to a new manager.
@endif
@endforeach


Please notify Sales Operations if there are any errors.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
