@if (count($companies) >1)
<div class="input-group-prepend">
    <span class="input-group-text"><i class="far fa-building"></i></span>
</div>
<select title="select company" wire:model="company_id" class="form-control">
   
    @foreach ($companies as $key=>$value)
        <option
        value="{{$key}}">{{$value}}</option>
    @endforeach
</select> 
@endif  
