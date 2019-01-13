@component('mail::message')

## Project Transferred 

{{$person->firstname}}, 

The following project has been transferred to you by {{$transferor->fullName()}}:
@component('mail::panel')
Project Name: {{$project->project_title}}

Project Location: {{$project->fullAddress()}}


@component('mail::button', ['url' => route('projects.myprojects'), 'color' => 'blue'])
        Check out all your construction projects and resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('projects.myprojects')}}]({{ route('projects.myprojects')}}) </em>

@endcomponent
Sincerely
        
{{env('APP_NAME')}}
@endcomponent