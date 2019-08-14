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

<div class="form-group form-group-lg">
    <label for='manager'>Manager:</label>
    <select class="form-control" 
       
        name="manager" 
        
        id="manager" 
        value="{{  old('manager')}}">
        <option value="">All Managers
        </option>
        @foreach ($managers as $manager)
        <option value="{{$manager->id}}">{{$manager->fullName()}}
        </option>
        @endforeach
    </select>
    <span class="help-block">
        <strong>{{$errors->has('manager') ? $errors->first('manager')  : ''}}</strong>
    </span>
</div>
@if(auth()->user()->hasRole('admin'))
    @if( $report->object == 'Company')
        @include('reports.partials._companyselector')
    @elseif ($report->object == 'Role')
        @include('reports.partials._roleselector')
    @endif
@endif