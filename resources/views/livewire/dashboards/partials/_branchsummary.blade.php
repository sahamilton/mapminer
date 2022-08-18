    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('id')" 
                role="button" href="#">
                    Branch
                    @include('includes._sort-icon', ['field' => 'id'])
                </a>
            </th>
            <th>Manager</th>
            </th>
            @foreach($displayFields as $key=>$value)
            <th>
                <a wire:click.prevent="sortBy('{{$value}}')" 
                role="button" href="#">
                    {{ucwords(str_replace('_', ' ', $value))}}
                    @include('includes._sort-icon', ['field' => '{{$value}}'])
                </a>
            </th>
            

            @endforeach
         
        </thead>
        <tbody>
        @foreach ($branches as $branch)


            <tr>
               <td>
                    <a href="{{route(implode('',$route), $branch->id)}}">
                        {{$branch->branchname}}
                    </a>
                </td>
                <td>
                    @foreach ($branch->manager as $manager)
                    {{$manager->fullName}}{{! $loop->last ? ", " :''}}

                    @endforeach
               </td>
                @foreach($displayFields as $value)
                    @if(isset($value) && strpos($value, 'value'))
                        <td align='right'>${{number_format($branch->$value,0)}}</td>
                    @else
                        <td align='center'>{{$branch->$value}}</td>
                    @endif
                @endforeach

            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <th colspan=2>Period Total</th>
            @foreach($displayFields as $value)
                @if(isset($value) && strpos($value, 'value'))
                    <td align='right'>${{number_format($branches->sum($value),0)}}</td>
                @else
                    <td align='center'>{{$branches->sum($value)}}</td>
                @endif
            @endforeach


        </tfoot>
    </table>
    <div class="row">
        <div class="col">
            {{ $branches->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} out of {{ $branches->total() }} results
        </div>
    </div>