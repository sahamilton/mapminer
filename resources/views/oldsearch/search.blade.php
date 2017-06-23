@extends('site.layouts.search')
@section('content')




<div v-if="shows.length == 0" class="mdl-shadow--4dp intro">
 <h3>Sales Document Search</h3>
 <h4>Search is a fully featured full text search engine</h4>
 <p>It is designed to show the powerfull search capabilities of the engine.
 You can try to search for any sales document you can think of.... </p>

 
</div>

@include('search.results')
@endsection