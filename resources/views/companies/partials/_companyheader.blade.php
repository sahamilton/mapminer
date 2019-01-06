<p><a href="{{route('salesnotes',$data['company']->id)}}" title="Read notes on selling to {{$data['company']->companyname}}">
<i class="fas fa-search" aria-hidden="true"></i>  
Read 'How to Sell to {{$data['company']->companyname}}'</a>

<a href="{{route('watch.index')}}" title="Review my watch list">
<i class="fas fa-th-list" aria-hidden="true"></i> 
View My Watch List</a>

<a href="{{route('watch.export')}}" title="Download my watch list as a CSV / Excel file">
<i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> 
Download My Watch List</a>

<a href="{{route('exportlocationnotes',$data['company']->id)}}" title="Download my {{$data['company']->companyname}} location notes as a CSV / Excel file">
<i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> 
Download my {{$data['company']->companyname}} location notes</a> </p>

<p><a href="{{ route('company.index') }}" title='Show all accounts'><i class="fas fa-th-list" aria-hidden="true"></i>

All Accounts</a></p>