<div>
    <h2>Audit</h2>

    <p class="bg-warning">For the period from {{$period['from']->format('Y-m-d')}} to  {{$period['to']->format('Y-m-d')}}</p>
    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            @include('livewire.partials._perpage')
           

        </div>
    
        <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
        <div class="col form-inline">
            <label for="status">Model:</label>
            <select wire:model="model" 
            class="form-control">
                <option value="All">All</options>
                <option value='App\Models\Person'>Person</options>
                <option value='App\Models\User'>User</options>
                
            </select>
        </div>
        @include('livewire.partials._periodselector')
        <div class="col form-inline">
            <label for="activitytype">Type:</label>
            <select wire:model="activitytype" 
            class="form-control">
                <option value="All">All</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
                <option value="deleted">Restored</option>
            </select>
        </div>
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Model</th>
            <th>Event</th>
            <th>
                <a wire:click.prevent="sortBy('created_at')" 
                role="button" href="#">
                    Date
                    @include('includes._sort-icon', ['field' => 'created_at'])
                </a>
            </th>
            <th>By</th>
            
            <th>Old Value</th>
            <th>New Value</th>
        </thead>
        <tbody>
            @foreach ($audits as $audit)
              
                <tr>
                   <td>
                    <a href="{{route('audits.show', $audit->id)}}">
                       {{ $audit->auditable_type }} (id: {{ $audit->auditable_id }})
                   </a>
                    </td>
                   <td> {{$audit->event }}</td>
                   <td>{{$audit->created_at->format('Y-m-d h:i a')}}</td> 
                   <td>{{isset($audit->user) ? $audit->user->person->fullName() : ''}}</td> 
                   
                   <td>
                      <table class="table">
                        @foreach($audit->old_values as $attribute => $value)
                          <tr>
                            <td><b>{{ $attribute }}</b></td>
                            <td>{{ $value }}</td>
                          </tr>
                        @endforeach
                      </table>
                    </td>
                    <td>
                      <table class="table">
                        @foreach($audit->new_values as $attribute => $value)
                          <tr>
                            <td><b>{{ $attribute }}</b></td>
                            <td>{{ $value }}</td>
                          </tr>
                        @endforeach
                      </table>
                    </td> 
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $audits->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $audits->firstItem() }} to {{ $audits->lastItem() }} out of {{ $audits->total() }} results
        </div>
    </div>
</div>
