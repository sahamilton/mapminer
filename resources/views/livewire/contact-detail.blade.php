<div>
    <h2>{{$contact->completeName}}</h2>

    <p>{{$contact->title}}</p>
    <p><a href="{{route('contacts.index')}}">Return to all branch contacts</a></p>
    <h4><a href="{{route('address.show', $contact->location->id)}}">{{$contact->location->businessname}}</a></h4>
    <p>{{$contact->location->fullAddress()}}</p>
    <p><i class="fa-regular fa-envelope"></i>{!! $contact->fullEmail !!}</p>
    <p><i class="fa-regular fa-phone"></i><a href="tel:{{$contact->phoneNumber}}">{{$contact->phoneNumber}}</a></p>
    @if($owned) 
        <div class="float-right mb-4">
            <button class="btn btn-info" href="#" wire:click.prevent="addActivity({{ $contact->location->id }})">
                <i class="fa-solid fa-calendar-lines-pen"></i>
                Record Activity
            </button>   
        </div>
    @endif
    <h4>Activities with {{$contact->completeName}}:</h4>
    <p><strong></strong></p>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')

            @include('livewire.partials._search', ['placeholder'=>'Search Activities'])
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
       <label><i class="fas fa-filter text-danger"></i>&nbsp;&nbsp;Filter&nbsp;&nbsp;</label>
        @include('livewire.partials._periodselector',['all'=>true])
        <div class="col form-inline">
          
            <label for="selectuser">Activity Type:</label>
            <select wire:model="activitytype_id" 
            class="form-control">
                @foreach ($activitytypes as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>

        </div>
        @if($branch->branchteam->count() >1)
            <div class="col form-inline">
                <label for="selectuser">Team:</label>
                <select wire:model="selectuser" 
                class="form-control">
                    <option value="All">All</option>
                    
                    @foreach ($branch->branchteam as $team)
                        <option value="{{$team->user_id}}">{{$team->CompleteName}}</option>
                    @endforeach
                </select>

                
            </div>
        @endif
    </div>
    <div class="row">
        <table class='table table-striped table-bordered table-condensed table-hover'>
            <thead>
                <th>
                    <a wire:click.prevent="sortBy('activity_date')" role="button" href="#">
                    Date
                    @include('includes._sort-icon', ['field' => 'activity_date'])
                    </a>
                        
                    </th>
                <th>Type</th>
                <th>Note</th>
                <th>By</th>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
          
                    <tr>

                        <td>{{$activity->activity_date->format('Y-m-d')}}</td>
                        <td>{{$activity->type->activity}}</td>
                        
                        <td>{{$activity->note}}</td>
                        <td>
                            {{$activity->user->fullName()}}
                            @if(isset($owned))
                                <a href="#" wire:click="editActivity({{$activity->id}})" title="Edit activity"><i class="text-info fa-regular fa-pen-to-square"></i></a>
                                <a href="#" wire:click="deleteActivity({{$activity->id}})" title="Delete activity"><i class="text-danger fa-solid fa-trash-can"></i></a>

                            @endif


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('contacts.partials._activitymodal')
    @include('livewire.activities._confirmmodal')
</div>
