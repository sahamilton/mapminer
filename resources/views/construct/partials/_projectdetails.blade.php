<p><strong>Source: </strong> Construction Monitor </p>
<p><strong>Publication Date: </strong> {{$project['publication']['publication_date']}}</p>
<p><strong>Project Description: </strong>{{$project["description"]}}</p>
<p><strong>Permit Date:</strong>  {{\Carbon\Carbon::parse($project["createdate"])->format('M d Y')}}</p>
<p><strong>Permit Status:</strong>  {{$project["permitstatus"]}}</p>
<p><strong>Project Last Updated:</strong>  {{\Carbon\Carbon::parse($project["lastupdated"])->format("M d Y")}}</p>
<p><strong>Project Value:</strong>  ${{number_format($project["valuation"],0)}}</p>
<p><strong>Construction Class: </strong>  {{$project["construction"]["construction_class"]}}</p>
<p><strong>Construction Type:</strong>  {{$project["construction"]["construction_type"]}}</p>
