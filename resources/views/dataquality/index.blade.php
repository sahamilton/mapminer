@extends('site.layouts.default')
@section('content')
<div class="container">
    <h4>{{$branch->branchname}} Data Quality Metrics</h4>
    <form 
        action="{{route('dataquality.branch')}}"
        method="post"
        name="dataqualityBranch">
        @csrf
        <div class="form-group">

            <select name="branch"  onchange="this.form.submit()">
            @foreach ($branches as $id=>$branchname)
            <option @if(isset($branch) && $branch->id == $id) selected @endif value="{{$id}}">{{$branchname}}</option>
            @endforeach
            </select>
        </div>
    </form>
    @foreach ($metrics as $metric)
        <div class="card" style="width: 18rem;">
            <div class="card-header">{{parseCamelCase($metric)}}</div>
            <div class="card-body">
                
                <p class="card-text">{{$data[$metric]}}</p>
                <form 
                    name="dataQualityDetails{{$loop->index}}"
                    method="post"
                    action = "{{route('dataquality.details')}}">
                    @csrf
                    <input 
                        type="hidden" 
                        name="branch" 
                        value = "{{$branch->id}}" />
                    <input 
                        type="hidden" 
                        name="metric" 
                        value = "{{$metric}}" />

                    <input 
                        type="submit" 
                        class="btn btn-primary" 
                        value="Details" />
                </form>
            </div>
        </div>
    @endforeach

</div>
@endsection