@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Document Library</h2>

    @if(auth()->user()->hasRole('admin'))
    <div class="float-right">
        <a href ="{{route('documents.create')}}">
            <button class="btn btn-success" >
            Add Document
            </button>
        </a>
    </div>    

    @endif       
    @livewire('documents-table')

</div> 
@include('partials._modal')
@include('partials._search')
@include('partials._scripts')
@endsection
