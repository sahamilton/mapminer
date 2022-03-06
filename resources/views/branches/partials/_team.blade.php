<div class="container" style="margin-top:40px">

    @foreach ($branchRoles as $key=>$role)
        $people = 
        <x-form-select 
            name='role[{{$key}}]' 
            label="{{$role}}:" 
            multiple
            :options="$branchRoles" 
        />


    @endforeach

</div>