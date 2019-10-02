<p><a href="{{route('salesnotes.company',$data['company']->id)}}" title="Read notes on selling to {{$data['company']->companyname}}">
<i class="fas fa-search" aria-hidden="true"></i>  
Read 'How to Sell to {{$data['company']->companyname}}'</a></p>


<p><a href="{{route('exportlocationnotes',$data['company']->id)}}" title="Download my {{$data['company']->companyname}} location notes as a CSV / Excel file">
<i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> 
Download my {{$data['company']->companyname}} location notes</a> </p>

