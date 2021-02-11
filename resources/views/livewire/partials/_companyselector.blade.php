
<div class="input-group-prepend">
    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
</div>
<select wire:model="company_id" class="form-control">
   
    @foreach ($companies as $key=>$value)
        <option
        value="{{$key}}">{{$value}}</option>
    @endforeach
</select>   
