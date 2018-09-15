<p><strong>Address:</strong>
<blockquote>{{$project['siteaddress']}}
<br /><em>(Map accuracy: {{$project['geo_precision']}})</em>
</blockquote>
<p><strong>Source: </strong> Construction Monitor </p>
<p><strong>Publication Date: </strong> {{$project['publication']['publication_date']}}</p>
<p><strong>Project Description: </strong>{{$project["description"]}}</p>
<p><strong>Permit Date:</strong>  {{$project["createdate"]}}</p>
<p><strong>Permit Status</strong>  {{$project["permitstatus"]}}</p>
<p><strong>Project Last Updated:</strong>  {{$project["lastupdated"]}}</p>
<p><strong>Project Value:</strong>  ${{number_format($project["valuation"],0)}}</p>
<p><strong>Site Address:</strong>  {{$project["siteaddress"]}}</p>
<p><strong>Construction Type:</strong>  {{$project["construction"]["construction_type"]}}</p>
<p><strong>Construction Class: </strong>  {{$project["construction"]["construction_class"]}}</p>

