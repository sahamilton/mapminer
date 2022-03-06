@extends('site/layouts/default')

@section('content')
@livewire('branch-team', ['branch_id'=>$branch->id])



@endsection
