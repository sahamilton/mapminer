<style>
    .ui-datepicker { width: 17em; padding: .2em .2em 0; display: none; z-index: 2000 !important;}

</style>
<div class="modal fade" 
    id="run-report" 
    tabindex="-1" 
    role="dialog" 
    aria-labelledby="myModalLabel" 
    aria-hidden="true">
    <div class="modal-dialog"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Select Period </h4>
                <button 
                type="button" 
                class="close" 
                data-dismiss="modal" 
                aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Select period for the <span id='title'></span> report.</p>
                 <form id="period-form" 
                        action="" 
                        method="post" 
                        >
                        @csrf
                <div class="form-group form-group-lg">
                    <label for='fromdate'>From:</label>
                    <input class="form-control" 
                        type="text" 
                        required 
                        name="fromdate"  
                        id="fromdatepicker" 
                        value="{{  old('fromdate', \Carbon\Carbon::now()->subMonths(1)->format('m/d/Y')) }}"/>
                    <span class="help-block">
                        <strong>{{$errors->has('fromdate') ? $errors->first('fromdate')  : ''}}</strong>
                    </span>
                </div>
                
                <div class="form-group form-group-lg">
                    <label for='todate'>To:</label>
                    <input class="form-control" 
                        type="text" 
                        name="todate" 
                        required 
                        id="todatepicker" 
                        value="{{  old('todate', \Carbon\Carbon::now()->format('m/d/Y')) }}"/>
                    <span class="help-block">
                        <strong>{{$errors->has('todate') ? $errors->first('todate')  : ''}}</strong>
                    </span>
                </div>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger danger"
                    onclick="event.preventDefault();
                    document.getElementById('action-form').submit();"
                    class="btn btn-danger danger">Run Report</a>           
                   
                </form>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>