@component('mail::message')
# Oracle Data Imported

{{$source->user->fullName()}} has succesfully {{$type}} Oracle data on {{$source->created_at->format('F jS Y')}} at {{$source->created_at->format('g:i a')}} from {{$source->originalfilename}}.

@component('mail::button', ['url' => route('oracle.list')])
Review Oracle Data
@endcomponent

{{ config('app.name') }}
@endcomponent
