<div>

    <h1>Notes</h1>
    
    @if($owned)
        <div class="float-right mb-4">
            <button class="btn btn-info" href="#" wire:click.prevent="addNote({{ $address->id }})">
                <i class="fa-regular fa-notes"></i>
                Record Note
            </button>   
        </div>
    @endif
    <div>
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
    

    <div class="row" style="margin-bottom:10px">
        <div class="col form-inline">
            @include('livewire.partials._perpage') 
            @include('livewire.partials._search', ['placeholder'=>"Search notes"])

        </div>
    </div>
    
    <div class="row" style="margin-bottom:10px">
      
        
       <div wire:loading>
            <div class="spinner-border text-danger"></div>
        </div>
        
        
    </div>
     @include('notes.partials._modal')
    <table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>

        <th>Date</th>
        <th>Note</th>
        <th>Created By</th>
        

        </thead>
        <tbody>
             @foreach($notes as $note)
            
                <tr>
                    <td>
                       {{ $note->created_at->format('Y-m-d')}}
                    </td>

                    <td>{{$note->note}}</td>
                    <td>{{$note->writtenBy ? $note->writtenBy->fullName() : ''}}</td>
                    <td>
                        @if($owned) 
                            <a href="#" wire:click="editNote({{$note->id}})" title="Edit note"><i class="text-info fa-regular fa-pen-to-square"></i></a>
                            <a href="#" wire:click="deleteNote({{$note->id}})" title="Delete note"><i class="text-danger fa-solid fa-trash-can"></i></a>
                        @endif
                    </td>
                </tr>
               @endforeach

        </tbody>
    </table>
    <div class="row">
        <div class="col">
            {{ $notes->links() }}
        </div>

        <div class="col text-right text-muted">
            Showing {{ $notes->firstItem() }} to {{ $notes->lastItem() }} out of {{ $notes->total() }} results
        </div>
    </div>
    @if ($notes->count() > 0)
        @include('livewire.notes._confirmmodal')
    @endif
</div>
