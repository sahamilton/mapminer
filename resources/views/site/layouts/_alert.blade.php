@if(config('app.env') === 'staging')
<div class="alert alert-danger">You are working in the <strong>{{config('app.env')}} environment</strong> not the <strong><a href="https://tbmapminer.com">production environment</a></strong>.  This area is strictly for development and no data will be retained!</div>
@endif