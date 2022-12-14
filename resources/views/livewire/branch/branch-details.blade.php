<div>
    @if(! $noheading)

        <h2>Branch {{$branch->first()->branchname}}</h2>
        <p><a href="{{route('branches.index')}}">Return to All Branches</a></p>
        <p>{{$branch->first()->fullAddress()}}</p>
        <p>Branch Phone:{{$branch->first()->phone}}</p>
        
        <p>Branch Email:
            <a href="mailto:{{$branch->first()->branchemail}}"
                     title="Email {{$branch->first()->branchname}}">
                {{$branch->first()->branchemail}}
            </a>
        </p>
        
        @if(auth()->user()->hasRole(['admin', 'sales_ops']))
            <div class="row mb-4 ">
                <div class="col form-inline">
                    <x-form-select name="branch_id" wire:model="branch_id" :options="$branches" label="Select branch" />
                </div>
            </div>
        @endif
    @endif
    <table class='table table-striped table-bordered table-condensed table-hover'>
        <thead><th colspan=5>Per Mapminer Data</th></thead></tr>
        <thead>
            <th>Team Member</th>
            <th>Role(s)</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Reports To</th>
            <th>Manager Email</th>
        </thead>
        <tbody>
       
            @foreach($branch as $br)
            <tr>
                <td colspan='6'>{{$br->branchname}}</td>
            </tr>
                @foreach ($br->branchteam as $manager)

                <tr>
                    <td>
                        <a href="{{route('user.show', $manager->user_id)}}">
                            {{$manager->fullName()}}
                        </a>
                    </td>
                    <td>
                        
                            <ul style=" list-style-type: none;">
                            @foreach ( $manager->userdetails->roles as $role)
                                <li>{{$role->display_name}}</li>
                            @endforeach
                            </ul>

                        
                    </td>
                    <td>{{$manager->phone}}</td>
                    <td><a href="mailto:{{$manager->userdetails->email}}">{{$manager->userdetails->email}}</a></td>
                    <td>
                        @if($manager->reportsTo)
                            <a href="{{route('user.show', $manager->reportsTo->user_id)}}">
                                {{$manager->reportsTo->fullName()}}
                               
                            </a>
                        @endif
                    </td> 
                    <td>
                        <a href="mailto:{{$manager->reportsTo->userdetails->email}}">{{$manager->reportsTo->userdetails->email}}</a>
                    </td>
                </tr>
                @endforeach
            @endforeach
        <tr>
            @if(auth()->user()->hasRole(['admin', 'sales_ops']))
            <thead><th colspan=5>Per Oracle Data</th></thead></tr>
                @foreach ($br->oraclelocation as $location)
                    <tr>
                        <td>{{$location->fullName()}}</td>
                        <td>{{$location->job_profile}}</td>
                        <td>{{$location->phone}}</td>
                        <td><a href="mailto:{{$location->primary_email}}">{{$location->primary_email}}</a></td>
                        <td>{{$location->manager_name}}</td>
                        <td><a href="mailto:{{$location->manager_email_address}}">{{$location->manager_email_address}}</a></td>



                    </tr>
              

               @endforeach 
            @endif
        </tbody>
</tbody>
</table>
</div>
