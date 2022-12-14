@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Flush Stale Leads</h2>
    <div class="alert alert-warning">
        <p>There are {{$leads}} leads created before {{$before->format('Y-m-d')}} that have no associated activities and no associated opportunities in the selected leadsources assigned to {{isset($manager) ?  $manager->fullName() . "'s" : 'All Managers'}} branches.</p>
    </div>
    <form action="{{route('leadsource.finalflush')}}"
        method="post"
        name="confirmflushleads"
        >
        @csrf
        <div class="form-check-inline">
          <label class="form-check-label">Export before flushing. 
            <input type="checkbox" 
                class="form-check-input" 
                name= 'export' 
                value="1" 
                checked />
          </label>
        </div>
        <div class="form-check-inline">
          <label class="form-check-label">Delete addresses from system. 
            <input type="checkbox" 
                class="form-check-input" 
                name= 'delete' 
                value="1" 
                 />
          </label>
        </div>
        <div class="form-group">
            <input type="submit"
             class="btn btn-info"
             name="submit"
             value="Confirm Flush leads"/>
         </div>
         <input type="hidden"
         name="manager"
         value="{{isset($manager) ? $manager->id : 'all'}}" />

         <input type="hidden"
         name="before"
         value="{{$before->format('Y-m-d')}}" />

         <input type="hidden"
         name="leadsource"
         value="'{{implode("','", $leadsource)}}'" />
    </form>
</div>
@endsection
