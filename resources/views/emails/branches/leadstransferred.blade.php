@component('mail::message')
# Branch Leads Transferred

Sales Operations has transferred all open leads, activities and opportunities from {{$branchfrom->branchname}} to branch {{$branchto->branchname}}.

@component('mail::button', ['url' => {{route('branch.leads', $branchto->id)}}])
Check Your New Leads
@endcomponent

Please advise Sales Operations if you do not want these leads.

{{ config('app.name') }}
@endcomponent
