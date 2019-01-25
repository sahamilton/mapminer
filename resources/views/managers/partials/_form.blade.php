<form method="post" action="{{route('managers.changeview')}}" class="form" id="selectAccount">
<!-- {{Form::open(array('route'=>'managers.view','class'=>'form', 'id'=>'selectAccount'))}}-->
{{csrf_field()}}

@if (auth()->user()->hasRole('admin')) 


<div class="row">

    <div class="form-group{{ $errors->has('manager)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Managers:</label>
        <div class="col-md-10">
            <select multiple class="form-control" name='manager[]' id='selectManager' onchange="this.form.submit()">

            @foreach ($data['managerList'] as $key=>$manager))
              <option @if(isset($data['manager']['user_id']) && $data['manager']['user_id'] ==$key) selected @endif value="{{$key}}">{{$manager}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('manager') ? $errors->first('manager') : ''}}</strong>
                </span>
        </div>
    </div>

</div>

@endif
<div class="row">

</div>
<div class="row">
    <div class="form-group{{ $errors->has('accounts)') ? ' has-error' : '' }}">
    
        <label class="col-md-2 control-label">Accounts:<br />
        Check all:{{Form::checkbox('checkAll', 'yes', true,array('id'=>'checkAllAccounts'))}}</label>
        <div class="col-md-10">
            <select multiple class="form-control" name='accounts[]' id='selectAccounts' onchange="this.form.submit()">

            @foreach ($data['accounts'] as $key=>$account))
              <option @if(isset($data['selectedAccounts']) && in_array($key,$data['selectedAccounts'])) selected @endif value="{{$key}}">{{$account}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('accounts') ? $errors->first('accounts') : ''}}</strong>
                </span>
        </div>
    </div>

</div>


<input type="submit" class="btn btn-success" name="btnsubmit" value="Select" />
</form>