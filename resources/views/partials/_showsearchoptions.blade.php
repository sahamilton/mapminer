<?php if(null!== Session::get('businesstype'))
{
	echo "<h4>Filtered by</h4>";
	echo "<ul>";
	echo "<li>" .Session::get('businesstype')."</li>";
	echo "</ul>";
}