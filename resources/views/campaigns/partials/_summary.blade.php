    <p>Descripiton: {{ucwords($campaign->description)}}</p>
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
    <p>
        @if($campaign->verticals)
            <strong>Verticals:</strong>
            @foreach ($campaign->verticals as $vertical)
                <li>{{$vertical->filter}}</li>
            @endforeach
        @else

            <strong>Companies:</strong>
            @foreach ($campaign->companies as $company)
                
            
                <li>{{$company->companyname}} 
                    
                    
                </li>
            
            @endforeach
        @endif
    </p>