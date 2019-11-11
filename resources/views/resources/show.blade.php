@extends ('site.layouts.default')
@section('content')

<h1>Sales Resources</h1>
<?php $companies = array();     ?>
<h4>Sales Notes documents based on your watch list</h4>
@foreach ($watch as $location)
  
    @if($location->watching->company_id && ! in_array($location->watching->company_id, $companies))
            
                <?php $companies[] = $location->watching->company->id;;?>
                <p><a href = "{{route('salesnotes.company',$location->watching->company_id) }}">
                Read "Sales Notes for  {{$location->watching->company->companyname}}"</a></p>
            
    @endif
    
@endforeach
<h4>Sales Campaign documents based on your industry vertical focus</h4>
@include('search.search')
<div class="col-md-10 col-md-offset-1">
        <table class='table table-striped' id='sorttable'>
            <thead>

                <th>Title</th>
                <th>Description</th>
                
                <th>Sales Process</th>
             

                <td>Link</td>
                <td>Rank</td>
                <td>Ratings</td>
                
                
            </thead>
            <tbody >
            @foreach ($documents as $document)
              
                <tr> 
                
                <td><a href="{{route('documents.show',$document->id)}}">{{$document->title }}</a></td>
               <td>
                    <span class="teaser">{{substr($document->description,0,100)}}</span>

                    <span class="complete"> {{$document->description}}</span>

                    <span class="more">more...</span>
                </td>
                <td>
                <ul style="list-style: none;">
                @foreach ($document->process as $process)
                    <li>{{$process->step}}</li>
                    @endforeach
                    </ul>
                </td>
                
                
 
                <td><a href="{{$document->link}}" target="_new">{{$document->link}}</a></td>
                <td> 
                 @if($document->rank->count() > 0 
                   && $document->score->count()> 0 
                   && $document->rankings->count() >0)
                    
                    {{$document->rank[0]->rank}}

                    @endif
                  </td>
                  <td>
                 
                    
                        {{($document->rankings->count())}}
                
                  
                 

                    </td>          
                  

             
                
               
                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
</div>


{{-- Scripts --}}
@include('partials._scripts')
@include('partials._search')
@endsection
