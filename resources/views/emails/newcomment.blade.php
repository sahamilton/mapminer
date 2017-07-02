@component('mail::message')

## New Comment on News

{{$data['user']->person->postName()}} has added a comment to the <a href="{{route('news.show',$data['comments']->title)}}">{{$data['comments']->subject}}</a> news item.

{{$data['user']->person->firstname}} said:

@component('mail::panel')
{{$data['comments']->comment}}

@endcomponent

You can review and or edit {{$data['user']->person->firstname}}'s comment with this link:

@component('mail::button', ['url' => route('comment.edit',$data['comments']->id), 'color' => 'blue'])
        Review / Edit Comment.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('comment.edit',$data['comments']->id)}}]({{ route('comment.edit',$data['comments']->id)}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
