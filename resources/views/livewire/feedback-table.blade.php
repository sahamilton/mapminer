<div>
    <h4>{{ucwords($status)}} Feedback</h4>
    <div class="row mb-4 ">
        @include('livewire.partials._search', ['placeholder'=>'Search Feedback'])
    </div>
    

    <button class="btn btn-success" 
    title="Export to Excel"
    wire:click='export'>Export <i class="far fa-file-excel"></i></button>
    <div class="row mb-4 ">
        @include('livewire.partials._perpage')
        <div class="col form-inline">
            <label for="accounttype">Status:</label>
            <select wire:model="status" 

            class="form-control">
               
                @foreach ($statuses as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
       <div class="col form-inline">
            <label for="type">Category:</label>
            <select wire:model="type" 

                class="form-control">
                <option value="All">All</option>
                @foreach ($categories as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>  
                <a wire:click.prevent="sortBy('created_at')" role="button" href="#">
                    Created
                    @include('includes._sort-icon', ['field' => 'created_at'])
                </a>
                   
            </th>
            <th>Type</th>
            <th>Submitted By</th>
            <th>Feedback</th>
            <th>Posted From</th>
            <th>Status</th>
            <th>
                <a wire:click.prevent="sortBy('biz_rating')" role="button" href="#">
                    Biz Rating
                @include('includes._sort-icon', ['field' => 'biz_rating'])
            </th>
            <th>
                <a wire:click.prevent="sortBy('tech_rating')" role="button" href="#">
                    Tech Rating
                @include('includes._sort-icon', ['field' => 'tech_rating'])
            </th>
            <th>Comments</th>
            
        </thead>
        <tbody>
            @foreach($feedback as $item)
                <tr>  
                    <td>
                        <a title="See details" href="{{route('feedback.show',$item->id)}}">{{$item->created_at->format('M j, Y')}}</a>
                    </td>
                    <td>{{$item->category->category}}</td>
                    <td>{{$item->providedBy->person->fullName()}}</td>
                    <td>
                        @if( strpos($item->feedback, '.')) 
                            {{substr($item->feedback, 0, strpos($item->feedback, '.'))}} 
                        @else 
                            {{$item->feedback}} 
                        @endif
                    </td>
                    <td>@if($item->url)<a href="{{$item->url}}" target="_blank" >{{$item->url}}</a>@endif</td>
                    <td>
                        {{$item->status}}
                        @if($item->status=='open')
                            <a title="close feedback" class="far fa-window-close text-danger" wire:click="closeFeedback({{ $item->id }})">
                                
                            </a>
                        @else
                            <a title="Reopen feedback" 
                            class="fas fa-door-open text-success" 
                            wire:click="openFeedback({{ $item->id }})">
                                
                            </a>
                        @endif
                    </td>
                    <td>{{$item->biz_rating}}</td>
                    <td>{{$item->tech_rating}}</td>
                    <td>{{$item->comments_count}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $feedback->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $feedback->firstItem() }} to {{ $feedback->lastItem() }} out of {{ $feedback->total() }} results
        </div>
    </div>

</div>
