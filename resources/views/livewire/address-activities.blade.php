<div>
        @if($owned)
            <div class="float-right mb-4">
            <button class="btn btn-info" href="#" wire:click.prevent="addActivity({{ $address->id }})">
                <i class="fa-solid fa-calendar-lines-pen"></i>
                Record Activity
            </button>   
            </div>
        @endif
        <div class="col form-inline mx-4">
           @include('livewire.partials._search', ['placeholder'=>"Search Activities"])

            <div class="col form-inline">
                <x-form-select name="activitytype_id"
                    wire:model="activitytype_id"
                    label="Type:"
                    :options="$activityTypes" 
                    />
            </div>
            <div class="col form-inline">
                <label for="completed">Completed?:</label>
                <select wire:model="completed" 
                class="form-control">
                    <option value="all">All</option>
                    <option value="complete">Completed</option>
                    <option value="todo">To Do</option>
                    
                </select>
            </div> 
        </div>
        <table  class='mt-4 table table-striped table-bordered table-condensed table-hover'>
            <thead>

                <th>
                    <a wire:click.prevent="sortBy('activity_date')" 
                            role="button" 
                            href="#" 
                           >
                                Date
                        @include('includes._sort-icon', ['field' => 'activity_date'])
                    </a>

                </th>
                <th>Created by</th>
                <th>Activity</th>
                <th>Contact</th>
                <th>Notes</th>
                <th>Completed</th>
            </thead>
            <tbody>
                 @foreach($activities as $acts)
                    
                    <tr>
                        <td>{{$acts->activity_date ? $acts->activity_date->format('Y-m-d'):''}}</td>
                        <td>
                            @if($acts->user)
                                {{$acts->user->person->fullName()}}
                            @else
                            No Longer with Company
                            @endif
                           
                        </td>
                        <td>
                            @if($acts->type)
                                {{$acts->type->activity}}
                            @endif
                        </td>
                        <td>
                            @foreach($acts->relatedContact as $contact)
                            {{$contact->complete_name}}
                            {{! $loop->last ? "," : ''}}
                            @endforeach
                            
                        </td>
                        <td>
                            @if($search !='')
                                {!! $acts->highlightWords($search) !!}
                            @else

                                {!! $acts->note !!}
                            @endif
                        </td>
                        <td>
                            @if($acts->completed)
                             Completed

                             @else
                             @if($owned)
                             <a wire:click="editActivity({{$acts->id}})" 
                                title="Edit activity"
                                class="fa-light fa-calendar-lines-pen text-info"></a>
                             @endif
                             @endif

                         </td>
                    </tr>
                   @endforeach

            </tbody>
        </table>
        <div class="row">
            <div class="col">
                {{ $activities->links() }}
            </div>

            <div class="col text-right text-muted">
                Showing {{ $activities->firstItem() }} to {{ $activities->lastItem() }} out of {{ $activities->total() }} results
            </div>
        </div>
        @include('activities.partials._modal')
        @include('activities.partials._editmodal')

</div>