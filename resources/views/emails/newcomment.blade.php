@component('mail::message')

## New Comment on News

{{$comment->postedBy->person->fullName()}} has added a comment to the <a href="{{route('comment.show',$comment->id)}}">{{$comment->subject}}</a> news item.

{{$comment->postedBy->person->firstname}} said:

@component('mail::panel')
{{$comment->comment}}

@endcomponent

You can review and or edit {{$comment->postedBy->person->firstname}}'s comment with this link:

@component('mail::button', ['url' => route('comment.edit',$comment->id), 'color' => 'blue'])
        Review / Edit Comment.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('comment.edit',$comment->id)}}]({{ route('comment.edit',$comment->id)}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
