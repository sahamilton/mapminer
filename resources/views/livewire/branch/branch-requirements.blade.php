<div>
    
    <h2>Estimated {{$periodViews[$periodView]}} Labor Requirements</h2>
    <h4>Based on {{$views[$view]}} </h4>
    <h4>For the period from {{$reportPeriod[0]['from']->format('Y-m-d')}} to {{$reportPeriod[$duration-1]['to']->format('Y-m-d')}}</h4>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
            <x-form-select 
                name="view"
                wire:model="view"
                label="Select:"
                :options='$views'
                />
            <x-form-select 
                name="periodView"
                wire:model="periodView"
                label="Select:"
                :options='$periodViews'
                />
            <div wire:loading class="spinner-border text-danger" role="status">
              <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <th>Branch</th>
            <th>Manager(s)</th>
            @foreach($reportPeriod as $showPeriod)
            <th>{{$showPeriod['from']->format('Y-m-d')}}</th>
            @endforeach
           
        </thead>
        <tbody>
            @php $totals=[]; @endphp
            @foreach($branches as $branch)
                <tr>
                    
                    <td>
                        <a href="{{route('opportunities.branch', $branch->id)}}">
                            {{$branch->branchname}}
                        </a>
                    </td>
                    <td>@foreach($branch->managers as $manager) 

                            {{$manager->completeName}}
                        @endforeach
                    </td> 
                    @php $a=0; @endphp
                    @foreach($reportPeriod as $showPeriod)
                        
                        <td>
                          @php $requirements = $branch->opportunities->filter(function ($opportunity) use($showPeriod) {
                            return $opportunity->est_start <= $showPeriod['to'] && $opportunity->est_end >= $showPeriod['from']
                                ;
                            }
                            )->sum('requirements');
                            isset($totals[$a]) ? $totals[$a] = $totals[$a] + $requirements : $totals[$a] = $requirements;
                        @endphp
                            {{$requirements}} 
                        </td>
                    @php $a++; @endphp
                    @endforeach
                   
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            
            <td colspan=2>Totals</td>
            @foreach($totals as $total)
                <th>{{$total}}</th>
            @endforeach
        </tfoot>
    </table>

</div>
