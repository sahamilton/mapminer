@extends('layouts.app')

@section('content')
<div class="container">
<h2>Quotes</h2>
<div class="container">

<div class="pull-right">
        <a href ="{{route('quotes.create')}}"><button class="btn btn-success" >Add Quote</button></a>
    </div>    
   
<?php $fields = ['Quote','Attribution','Source','Actions'];?>
        
<div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                @foreach ($fields as $field)
                    <th>{{ucwords($field)}}</th>

                @endforeach
                
            </thead>
            <tbody>
            @foreach ($quotes as $quote)
        
                <tr> 
                
                <td>{!!$quote->quote!!}</td>
                <td>{{$quote->attribution}}</td>
                <td>{{$quote->source1}}<br /> Act {{$quote->source2}} Sc{{$quote->source3}}</td>
                
                
                 <td class="col-md-2">
                @include('partials/modal')

                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu" role="menu">

                    <li><a href="{{route('quotes.edit',$quote->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit quote</a></li>

                    <li><a data-href="{{route('quotes.purge',$quote->id)}}" 
                    data-toggle="modal" 
                    data-target="#confirm-delete" 
                    data-title = "location" 
                    href="#"><i class="glyphicon glyphicon-trash"></i> Delete quote</a></li>



                    </ul>
                </div>
               
               </td> 


                            
                  

             
                
               
                </tr>  
            
            @endforeach
            </tbody>
            


        </table>
        </div>
    </div>
</div>
@endsection
