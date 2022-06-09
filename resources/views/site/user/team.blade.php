<div class="list-group-item">
	<div class="row">

	<div class="list-group-item-text col-sm-4">
		<p><strong>Reporting Structure</strong></p>
		<ul style="list-style-type: none;">
			@if($user->person->reportsTo)
				<li>Reports To:
				@if($user->person->reportsTo()->count() >0)
				
				<a href="{{route('salesorg.show',$user->person->reportsTo->id)}}">
					{{$user->person->reportsTo->fullName()}}
				</a>
				@else
					No Manager
				@endif
			@endif
			@if($user->oracleMatch 
				&& $user->oracleMatch->oracleManager
				&& $user->oracleMatch->oracleManager->mapminerUser
				&& $user->oracleMatch->oracleManager->mapminerUser->person->id != $user->person->reports_to)
			<p>
				<i class="fa-solid fa-user-plus txt-danger"></i>
				<a href="{{route('oracle.reassign',[$user->person->id, $user->oracleMatch->oracleManager->id])}}"
					title="Change {{$user->fullName()}}'s manager to {{$user->oracleMatch->oracleManager->mapminerUser->fullName()}}">
				{{$user->oracleMatch->manager_name}}</a>
			</p>
			
		@endif
		
		</ul>
		<livewire:manage-team :user='$user' />
	
</div>