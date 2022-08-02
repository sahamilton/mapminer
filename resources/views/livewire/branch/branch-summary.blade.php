<div>
    <table class='table table-striped table-bordered table-condensed table-hover'>

        <thead>
            <th colspan='{{count($fields) + 1}}' class="text-center">Summary for  the period {{$period['from']->format('Y-m-d')}} to {{$period['to']->format('Y-m-d')}}
            </th>
            <tr align="center">
                <th></th>
                <th colspan="2"><a href="{{route('branch.leads', $branch_id)}}">Leads</a></th>
                <th><a href="{{route('branch.activity', $branch_id)}}">Activities</a></th>
                <th colspan="4"><a href="{{route('opportunities.branch', $branch_id)}}">Opportunities</a></th>
            </tr>
            <tr>
                <th>Branch</th>
                @foreach ($fields as $field)
                    <th class="text-center">{{$field}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($branches as $branch)
                <tr>
                    <td>
                        @if($branch_id =='all')
                            <a href="#" wire:click='selectBranch({{$branch->id}})' > 
                            {{$branch->branchname}}</a>
                        @else
                            {{$branch->branchname}}
                        @endif
                    </td>
                    @foreach ($fields as $field)
                            <td class="text-center">{{$branch->$field}}</td>
                    @endforeach
                </tr>
            @endforeach
 
        </tbody>
    </table>
</div>
