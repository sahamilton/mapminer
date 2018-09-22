@component('mail::message')
## Welcome

{{$person->firstname}},

Welcome! You have been granted you access to the {{env('APP_NAME')}} system. 

This system provides information on sales and support oppotunties and will allow you to 
* Search for locations of national accounts
* Locate opportunities at local construction projects
* Search branches and their team members

Please note that information in the {{env('APP_NAME')}} is strictly confidential
and offered only for company business.

You can access the system at {{env('APP_URL')}}. You should login with your email
{{$person->userdetails->email}}. You will have to set your password intially by using the ['Forgot password' link]({{route('password.request')}}). Enter your email and you will receive instructions to create your own, personalized, password.

If you have any questions about the system or any difficulties accessing or using it 
please contact Sales Operations.


Sincerely

{{env('APP_NAME')}} 
@endcomponent