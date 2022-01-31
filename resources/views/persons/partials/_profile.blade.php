<div class="list-group">
    <div class="list-group-item">
        <p class="list-group-item-text"><strong>Role Details</strong></p>
        <ul style="list-style-type: none;">
        @foreach ($user->roles as $role)
            <li>{{$role->display_name}}</li>
        @endforeach
        </ul>
    </div>

    <div class="list-group-item">
        <p class="list-group-item-text"><strong>User Details</strong></p>
        <ul style="list-style-type: none;">
            <li>User id: {{$person->userdetails->id}}</li>
            <li>Person id: {{$person->id}}</li>
            <li>Employee id: {{$person->userdetails->employee_id}}</li>
            <li><strong>Servicelines:</strong>
                <ul>
                    @foreach ($user->serviceline as $serviceline)
                        <li>{{$serviceline->ServiceLine}}</li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>

    <div class="list-group-item">
        <div class="list-group-item-text col-sm-4">
            <p><strong>Contact Details</strong></p>
                <ul style="list-style-type: none;">
                <li>Address:{{$person->fullAddress()}}
                <li>Phone: {{$person->phone}}</li>
                <li>Email: 
                    <a href="mailto:{{$person->userdetails->email}}">{{$person->userdetails->email}}</a>
                </li>
                <li>
                    
                </li>
            </ul>
        </div>
        <div class="col-sm-8">
            @if(! empty($person->lat))
                @php
                   $latLng= "@". $person->lat.",".$person->lng .",14z";
                @endphp
        
                 @include('persons.partials._map')
                        
            @else
            <p class="text-danger"><strong>No address or unable to geocode this address</strong></p>        
            @endif
        </div>
        <div style="clear:both"></div> 
        @if(isset($branches))

            <div class="list-group-item">
                <p><strong>Closest Branches to your location</strong></p>
                <div class="row">
                    <div class="list-group-item-text col-sm-12">
                        @include('branches.partials._nearby')
                    </div>
                </div>
            </div>

            @endif
        @can('manage_people')
            <div class="list-group-item-text col-sm-4">
                <p><strong>Reporting Structure</strong></p>
                <ul style="list-style-type: none;">
                    <li>Reports To:
                        @if($person->reportsTo->id)
                            

                            <a href="{{route('person.details',$person->reportsTo->id)}}">{{$person->reportsTo->fullName()}}</a>
                        @else
                            {{$person->reportsTo->fullName()}}
                        @endif
                    </li>
                
                    @if($person->directReports->count()>0)
                        <li>Team:</li>
                        @foreach ($person->directReports as $reports)
                    
                            <li><a href="{{route('person.details',$reports->id)}}">{{$reports->fullName()}}</a></li>
                        
                        @endforeach

                    @endif

                </ul>
            </div>
            <div class="col-sm-8">
                @if($person->directReports->count()>0)
                    @include('persons.partials._teammap')
                    @endif
                </div>
                <div style="clear:both"></div> 
            </div>
        @endcan
                
        

        <div class="list-group-item">
            <div class="row">
                <div class="list-group-item-text col-sm-4">
                    <p><strong>Branches Serviced</strong></p>
                    @if(! count($myBranches))
                        <div class="alert alert-warning">
                            <p>{{$user->person->firstname}} is not assigned to any branches</p>
                        </div>
                        @else

                        <ul style="list-style-type: none;">
                            @foreach ($myBranches as $branch)
                                <li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a> </li>
                            @endforeach
                        </ul>

                    @endif
                    @if($user->id == auth()->user()->id)
                        <div class="alert alert-warning">
                        <p class="">If your branch associations are incorrect or incomplete you should contact <a href="mailto: {{config('mapminer.system_contact')}}">
                                <i class="far fa-envelope" aria-hidden="true"> </i>
                                 {{config('mapminer.system_contact')}}
                            </a>.</p> 
                        </div>
                    @endif
                </div>
                <div class="col-sm-8">
                    @include('site.user._branchmap')
                </div>
                <div style="clear:both"></div>  
            </div>
     
        @if($user->scheduledReports()->exists())
            <div class="list-group-item"><p class="list-group-item-text"><strong>Scheduled Reports</strong>
                <ul style="list-style-type: none;">
                    @foreach($user->scheduledReports as $report)
                        <li>
                            <a href="{{route('reports.show', $report->id)}}"
                                title="Review the {{$report->report}} report">
                                {{$report->report}}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        
        @endif
        @can('manage_accounts')
            <div class="list-group-item"><p class="list-group-item-text">
                <strong>Accounts Managed</strong></p>
                <ul style="list-style-type: none;">
                    @foreach($person->managesAccount as $account)
                        <li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
                    @endforeach
                </ul>
            </div>
        @endcan
            <div class="list-group-item"><p class="list-group-item-text"><strong>Activity</strong></p>
                
                <ul style="list-style-type: none;">
                    @if($person->directReports->count()>0)
                    <div class="float-right">
                    <a href="{{route('team.show',$person->id)}}" class="btn btn-info">  See Teams Mapminer Usage</a>
                    </div>
                    @endif
                    <li>Mapminer User since: {{$user->created_at ? $user->created_at->format('Y-m-d') : ''}}</li>
                    <li>Total Logins: {{$user->usage_count}}</li>
                    <li>Last Login:
                        
                        @if($user->lastLogin)
                        {{$user->lastLogin->lastactivity->format('Y-m-d')}}
                    @endif
                </li>
                        
                </ul>
            </div>
        </div>
    </div>
</div>