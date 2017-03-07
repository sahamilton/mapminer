<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>A new location note has been added</h2>
		<div>

        <p>{{$company[0]->company['managedBy']->firstname}};</p>
        
		<p>{{$user->firstname}} {{$user->lastname}} has added a note to {{$company[0]->businessname}}.  You can review the note at this link <a href="{{route('location.show',$company[0]->id)}}" >this link</a></p>
        Sincerely
        
        <p>PeopleReady National Account Mapping Support</p>
		</div>
	</body>
</html>