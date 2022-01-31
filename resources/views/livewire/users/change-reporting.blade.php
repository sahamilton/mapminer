<div> 
    <h2>Deleting {{$person->fullName()}}</h2>
    @if($this->person->directReports->count())
        <div class="alert-danger">
            Before you can delete {{$person->fullName()}} you must reassign their direct reports.
        </div>
    
        <div class="row mb4" style="padding-bottom: 10px">
            <div class="col form-inline">
                 @include('livewire.partials._perpage')
                <div class="col mb8">
                    <div class="input-group-prepend">
                        <span 
                            class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
            
                        <input 
                            wire:model="search" 
                            class="form-control" 
                            type="text" 
                            placeholder="Search people...">
                    </div>
                </div>
            </div>
        </div>
        <h4>{{$person->fullName()}}'s Direct Reports</h4>
        <div class="col form-inline">
            <label>Re-assign all to:</label>
            <select wire:model="allChangeTo"
            wire:change="allChange">
                class="form-control"  
                name="assignAll">
                <option ></option>
                @foreach($possibles as $possible)
                    <option value="{{$possible->id}}">
                        {{$possible->fullName()}}
                    </option> 
                @endforeach
            </select>
        </div>  
        <table>
            <thead>
                <tr>
                    <th>Direct Report</th>
                    <th>Reassign to</th>
                </tr>

            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td><a href="{{route('users.edit',$report->user_id)}}">{{$report->fullName()}}</a></td>
                    <td>
                        <select name="changeto{{$report->id}}">
                            @foreach ($possibles as $possible)
                                <option value="{{$possible->id}}">{{$possible->fullName()}}

                            @endforeach
                        </option>
                </tr>
                @endforeach
            </tbody>
        </table>   
    @else
    <form action="{{route('users.destroy', $person->user_id)}}"
    method='post'>
        @csrf
        @method('delete')
    
        <input type="submit" 
            class="btn btn-danger" 
        value="Confirm Delete of {{$person->fullName()}}" />
        
    </form>
    @endif
</div>
