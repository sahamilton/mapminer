@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="page-header">
<h3>How to sell to {{$company->companyname}}</h3>

@if (auth()->user()->hasRole('admin'))
    <div class="float-right">
        <a href="{{route('salesnotes.cocreate',$company->id)}}" class="btn btn-small btn-info iframe">
    <i class="fas fa-plus-circle " aria-hidden="true"></i>

     Create / Edit</a>
    </div>

@endif
<div class='content'>
    <nav>
       <div class="nav nav-tabs" id="nav-tab" role="tablist">    
           @foreach ($fields->where('depth', 1) as $tab)
                
      
                  <a class="nav-link nav-item @if($loop->first) active @endif" 
                      id="{{$tab->fieldname}}-tab" 
                      data-toggle="tab" 
                      href="#{{$tab->fieldname}}" 
                      role="tab" 
                      aria-controls="{{$tab->fieldname}}" 
                      aria-selected="true">
                    <strong> {{$tab->fieldname}}</strong>
                  </a>
            @endforeach

        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">  
        @foreach ($fields->where('depth', 1) as $tab)
            <div id="{{$tab->fieldname}}" class="tab-pane show @if($loop->first) active @endif" >
                @foreach ($tab->getDescendants() as $field)

                @if($data->where('howtofield_id', $field->id)->first())
                    <p><strong>{{$field->fieldname}}</strong></p>
                        <p>{!! $data->where('howtofield_id', $field->id)->first()->fieldvalue!!} </p>
                 
                @endif
                @endforeach
            </div>
        @endforeach
    </div>
   </div>

@include('partials._scripts')
@endsection

