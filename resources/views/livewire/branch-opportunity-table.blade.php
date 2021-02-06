<div>
    <div class="row" style="margin-top:5px">
        @include('livewire.partials._perpage')
        @include('livewire.partials._periodselector')

        <div class="col">
            <input wire:model="search" class="form-control" type="text" placeholder="Search branches...">
        </div>
    </div>

    <div class="row">
       <table class='table table-striped table-bordered table-condensed table-hover'>    
            <thead>
                <th>Branch</th>
                <th>Open</th>
                <th>Opened</th>
                <th>Open Value</th>
                <th>Closed Won</th>
                <th>Won Value</th>
            </thead>
            <tbody>
                @foreach ($branches as $branch)
                <tr>
                    <td>
                        <a href="{{route('opportunities.branch', $branch->id)}}">
                            {{$branch->branchname}}
                        </a>
                    </td>
                    <td align="center">
                        {{$branch->open_opportunities}}

                    </td>
                    <td align="center">{{$branch->new_opportunities}}</td>
                    <td align="right">{{$branch->open_value ? "$" . number_format($branch->open_value,0) : '0'}}</td>
                    <td align="center">{{$branch->won_opportunities}}</td>
                    <td align="right">{{$branch->won_value ? "$" . number_format($branch->won_value,0) : '0'}}</td>
                   
                </tr>
                @endforeach
            </tbody><td>Totals</td>
            <td align="center">{{$branches->sum('open_opportunities')}}</td>
            <td align="center">{{$branches->sum('new_opportunities')}}</td>
            <td align="right">${{number_format($branches->sum('open_value'),0)}}</td>
            <td align="center">{{$branches->sum('won_opportunities')}}</td>
            <td align="right">${{number_format($branches->sum('won_value'),0)}}</td>
            <tfoot>

            </tfoot>
        </table>
    </div>
        <div class="row">
            <div class="col">
                {{ $branches->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} out of {{ $branches->total() }} results
            </div>
        </div>


</div>
