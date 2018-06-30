@extends ('admin.layouts.default')
@section('content')
 <script type="text/javascript" src="{{asset('assets/js/starrr.js')}}"></script>
<div class="container">


			<h2>
				<strong>Prospect Source - {{$leadsource->source}}</strong></h2>
				<p>{{$leadsource->description}}</p>
				<p>
				@if($leadsource->dateto < Carbon\Carbon::now())
            Expired {{$leadsource->datefrom->format('M j,Y')}}
        @elseif ($leadsource->datefrom > Carbon\Carbon::now())
            Commences {{$leadsource->datefrom->format('M j,Y')}}
        @else
           Available from {{$leadsource->datefrom->format('M j,Y')}} to {{$leadsource->dateto->format('M j,Y')}}
        @endif
    </p>
				<p class="row"><a href="{{route('leadsource.export',$leadsource->id)}}"><i class="fa fa-cloud-download" aria-hidden="true"></i></i>  Export owned and closed {{$leadsource->source}} Leads</a></p>
				

				<p><a href="{{route('leadsource.index')}}">Return to all Prospect sources</a></p>

	 <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>
               
                <th>Person</th>
                <th>Assigned</th>
                <th>Closed</th>
                <th>Average Rating</th>
               

            </thead>
            <tbody>
         
               @foreach ($data as $rep)
               <tr>
	                  <td><a href="{{route('salesrep.newleads',$rep['id'])}}">{{$rep['name']}}</a></td> 
	                  <td class="text-right">{{$rep['Claimed']['count']}}</td>
	                  
	                   @if(isset($rep['Closed']))
	                   <td class="text-right">	{{$rep['Closed']['count']}}</td>
	                   <td class="text-right"><p data-rating="{{round($rep['Closed']['rating'])}}" class="starrr">{{number_format($rep['Closed']['rating'],2)}}</p></td>
	                    @else
	                    <td></td><td></td>
	                   @endif

            	</tr>
                @endforeach
             	</tr>
            </tbody>



        </table>
    </div>
</div>
@include('partials._scripts')
@endsection