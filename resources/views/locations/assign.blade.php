@extends('site/layouts/default')
@section('content')

<?php $headings = array('Business'=>'businessname','Address'=>'street','City'=>'city','State'=>'state');?>
<table class="table table-striped">
<thead>
<?php while(list($key,$value) =each($headings)) {
	echo "<th>" . $key ."</th>";
}
echo "</thead>";
echo "<tbody>";
reset ($headings);
foreach ($data as $location){
echo "<tr>";
while(list($key,$value) =each($headings)) {
	echo "<td>" . $location[$value] ."</td>";
}
echo "</tr>";
}
echo "</tbody></table>";?>
@stop
