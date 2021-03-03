<div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
</div>
<select wire:model="person_id" class="form-control">
    @foreach ($managers as $manager)
    <option value="{{$manager->id}}">{{$manager->fullName()}}</option>
    @endforeach
</select>   
