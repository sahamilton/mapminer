<div>
    <h2>Branch {{$branch->branchname}}</h2>
    <p><a href="{{route('branches.index')}}">Return to All Branches</a></p>
    <p>{{$branch->fullAddress()}}</p>
    <p>Branch Phone:{{$branch->phone}}</p>
    @if(auth()->user()->hasRole(['admin', 'sales_ops']))
        <div class="row mb-4 ">
            <div class="col form-inline">
                <x-form-select name="branch_id" wire:model="branch_id" :options="$branches" label="Select branch" />
            </div>
        </div>
    @endif
    <table class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Team Member</th>
        <th>Role(s)</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Reports To</th>
    </thead>
    <tbody>
        @foreach ($branch->branchteam as $manager)

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
            <td>{{$manager->userdetails->email}}</td>
            <td>
                @if($manager->reportsTo)
                    <a href="{{route('user.show', $manager->reportsTo->user_id)}}">
                        {{$manager->reportsTo->fullName()}}
                    </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
