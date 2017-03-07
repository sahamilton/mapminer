<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
    
		<h2>New feedback has been added</h2>
		<div>

        <p>Support</p>
       
		<p>{{$user->firstname}} {{$user->lastname}} has added some feedback.</p>
        <h4>{{$comments->subject}} / {{$comments->title}}</h4>
        <p><em>{{$comments->comment}}</em></p>
        <hr />
        
         You can edit the feedback at <a href="{{route('comment.edit',$comments->id)}}" >this link</a></p>
       <p> Sincerely</p>
        
        <p>PeopleReady National Account Mapping Support</p>
		</div>
	</body>
</html>