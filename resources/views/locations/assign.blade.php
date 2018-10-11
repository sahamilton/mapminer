@extends('site/layouts/default')
@section('content')

<?php $headings = array('Business'=>'businessname','Address'=>'street','City'=>'city','State'=>'state');?>
<table class="table table-striped">
<thead>
<?php foreach($headings as $key=>$value)
{
	echo "<th>" . $key ."</th>";
}

echo "</thead>";
echo "<tbody>";
reset ($headings);
foreach ($data as $location){
echo "<tr>";
foreach($headings as $key=>$value)
{
	echo "<td>" . $location[$value] ."</td>";
}
echo "</tr>";
}
echo "</tbody></table>";?>
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
