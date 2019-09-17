@if(config('app.env')!='production')
<div class="alert alert-danger">You are working in the {{config('app.env')}} area not the <a href="https://tbmapminer.com">production environment</a>.  This area is strictly for development and no data will be saved!</div>
@endif