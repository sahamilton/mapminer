@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Flush Leads</h2>
    <p><a href="{{route('leadsource.index')}}">See all lead sources</a></p>
    <form action="{{route('leadsource.mgrflush')}}"
    method="post"
    name="flushleads"
    >
        @csrf
        <!-- leadsources -->
        <table id= "sorttable" 
        class= "table table-striped table-bordered"
        data-page-length='100'

        >

          <thead>
            <th><input type="checkbox" id="checkAll">Check All</th>
            <th>id</th>
            <th>LeadSource</th>
            <th>Stale Leads</th>
            <th>Date Created</th>
          </thead>
          <tbody>
            @foreach ($leadsources as $leadsource)
            <tr>
              <td><input type="checkbox" 
                name="leadsource[]" 
                value="{{$leadsource->id}}" />
              </td>
              <td>{{$leadsource->id}}</td>
              <td>
                  <a href="{{route('leadsource.show',$leadsource->id)}}">
                   {{$leadsource->source}}
                 </a>
              </td>
              <td class="text-right">
                {{number_format($leadsource->branchleads_count,0)}}
              </td>
              <td>{{$leadsource->created_at->format('Y-m-d')}}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

        
        <!--- Manager -->
        <div class="form-group form-group-lg">
            <label for='manager'>Manager:</label>
            <select class="form-control" 
              name="manager" 
              required
              id="manager" 
              value="{{  old('manager')}}">
              <option value="all">
                All Managers
              </option>
              @foreach ($managers as $manager)
              <option value="{{$manager->id}}">
                {{$manager->fullName()}}
              </option>
              @endforeach
            </select>
            <span class="help-block">
              <strong>{{$errors->has('manager') ? $errors->first('manager')  : ''}}</strong>
            </span>
        </div>
        <div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label" for="datefrom">Created Before</label>
          <div class="input-group input-group-lg">
          <input class="form-control" 
              type="text" 
              name="before"  
              id="fromdatepicker" 
              value="{{  old('before',  \Carbon\Carbon::now()->subMonth(6)->format('m/d/Y')) }}"/>
          <span class="help-block">
              <strong>{{$errors->has('datefrom') ? $errors->first('datefrom')  : ''}}</strong>
          </span>
          </div>
</div>
        <input type="submit"
         class="btn btn-info"
         name="submit"
         value="Flush leads"/>
    </form>
</div>
@include('partials._scripts')
@endsection
