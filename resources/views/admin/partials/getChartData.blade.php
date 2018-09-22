@php
$labels =null;
$cumulative = array();
@endphp

@foreach ($data['logins'] as $element)
  @if($loop->first || $loop->index % 3 === 0)
	
 	@php	
    $labels.= "'" .$element->firstlogin. "',";
  @endphp

	@else
    @php   $labels.= "'',";@endphp
  @endif
	 @if(! $loop->first)
   		@php 
        $cumulative[]=$element->logins + $cumulative[$loop->index -1];
      @endphp
   
	 @else
		 @php
      $cumulative[]=$element->logins;
     @endphp
    @endif
@endforeach
@php
  $labels = substr($labels,0,-1);
  $total = implode(",",$cumulative);
  $datastring =implode(",",$data['status']->pluck('count')->toArray());
  $labelstring ="'".implode("','",$data['status']->pluck('status')->toArray())."'";
  $weekdata =implode(",",$data['weekcount']->pluck('login')->toArray());
  $weeklabels ="'".implode("','",$data['weekcount']->pluck('week')->toArray())."'";
  
@endphp