<div class="row" 
    style="margin-top:20px;margin-bottom:20px">
        <button 
        type="button" 
        class="btn btn-info btn-block col-sm4" 
        data-toggle="collapse" 
        data-target="#summary">
            Summary
        </button>
    </div>
    <div class="collapse col-sm-8" 
        id="summary">
   
    <p><strong>Description:</strong> {{ucwords($campaign->description)}}</p>
    <p><strong>Created By:</strong>{{$campaign->author ? $campaign->author->fullName() :''}}</p>
    <p><strong>Created:</strong>{{$campaign->created_at->format('l jS M Y')}}</p>
    <p><strong>Manager:</strong>
        @if ($campaign->manager)
            {{$campaign->manager->fullName()}}
        @else
            All Managers
        @endif
    </p>
    
    <p><strong>Active From:</strong>{{$campaign->datefrom ? $campaign->datefrom->format('l jS M Y') : ''}}</p>
    <p><strong>Expires:</strong>{{$campaign->dateto ? $campaign->dateto->format('l jS M Y') : ''}}</p>
    <p><strong>Branches:</strong> <em>(that can service)</em>
        
            {{$campaign->branches->count()}}
        
    </p>
    
        @if(isset($data))
       <p> <strong>Total Assignable Locations:</strong>{{$data['locations']['unassigned']->count()}}</p>
       <p><strong>Unable to Assign:</strong>{{count($data['assignments']['unassigned'])}}</p>
       <p> <strong>Total Assigned Locations:</strong>{{count($data['assignments']['location'])}}</p>
       <p> <strong>Total Previously Assigned Locations:</strong>{{$data['locations']['assigned']->count()}}</p>
    @endif

    
    <p>
        @if($campaign->verticals)
            <strong>Verticals:</strong>
            @foreach ($campaign->verticals as $vertical)
                <li>{{$vertical->filter}}</li>
            @endforeach
        @else

            <strong>Companies:</strong>
            <div class="row">
                <table id="sorttable2" class="table table->striped col-sm-6">
                    <thead>
                        <th>Company</th>
                        <th>Assignable Locations</th>
                        <th>Assigned Locations</th>
                    </thead>
                    <tbody>
                    @foreach ($data['companies'] as $company)
                    <tr>
                        <td>{{$company->companyname}}</td>
                        <td>
                            {{$company->unassigned->count()}}
                            @php $totals['unassigned'] = isset($totals['unassigned']) ? $totals['unassigned'] + $company->unassigned->count() : $company->unassigned->count()  @endphp
                        </td>
                        <td>
                            {{$company->assigned->count()}}
                            @php $totals['assigned'] = isset($totals['assigned']) ? $totals['assigned'] + $company->assigned->count() : $company->assigned->count()  @endphp
                        </td>
                        
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th>Totals</th>
                    <td>{{$totals['unassigned']}}</td>
                    <td>{{$totals['assigned']}}</td>

                </tfoot>
            </table>
        </div>
          
       @endif
    </p>
</div>