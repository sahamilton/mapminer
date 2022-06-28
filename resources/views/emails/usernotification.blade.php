@component('mail::message')
## Welcome to Mapminer

{{$user->person->firstname}},

Welcome! You have been granted access to the {{env('APP_NAME')}} system. 

This system provides information on branch sales and support opportunties and will allow you to 
* Search for leads.
* Add and track you own leads.
* Record sales activities.
* Create and track sales opportunities.
* Manage your branch(es) team.

Please note that information in the {{env('APP_NAME')}} is strictly confidential
and offered only for company business.

You can access the system at [{{env('APP_URL')}}]({{env('APP_URL')}}). You should login with your email
{{$user->person->email}}. You will have to set your password intially by using the ['Forgot password' link]({{route('password.request')}}). Enter your email and you will receive instructions to create your own, personalized, password.

If you have any questions about the system or any difficulties accessing or using it 
please contact Sales Operations.


Sincerely

{{env('APP_NAME')}} 
@endcomponent