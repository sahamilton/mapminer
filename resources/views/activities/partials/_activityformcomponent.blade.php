<x-form-select name="activitytype_id" required :options="$activityTypes" label="Activity: "  />

@if($contacts && count($contacts) > 0)
  <x-form-select name="contact" :options="$contacts" label="Contact: "  /> 
@endif
<x-form-textarea label="Comments" required name="note" placeholder="Comments" />
<x-form-input name="activity_date" default="{{now()->format('Y-m-d')}}" required label="Activity Date" type='date' />
<x-form-checkbox checked name="completed" label="Completed" />
<x-form-input name="followup_date" label="Followup Date" type='date' />
<x-form-select name="followup_activity" :options="$activities" label="Follow Up Activity"  />