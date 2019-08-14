@if (null!== Session::get('businesstype')) 
<h4>Filtered by</h4>
    <ul>
        <li>{{Session::get('businesstype')}}</li>
    </ul>
@endif