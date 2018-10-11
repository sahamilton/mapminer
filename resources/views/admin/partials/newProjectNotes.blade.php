<div style="border:1px solid #000;width:600px;margin:20px;padding:20px;float:left">
  <h4>Project Notes in past month</h4>
  <!-- 'writtenBy','relatesTo','relatesTo.company','writtenBy.person' -->
  <table id ='sorttable6' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

      <th>Project</th>

      <th>Address</th>
      <th>Note</th>
      <th>Date</th>
      <th>By</th>

    </thead>
    <tbody>
      @foreach($data['recentProjectNotes'] as $newNote)
        <tr>
          <td>
            <a href = "{{route('projects.show',$newNote->relatesToProject->id)}}" 
            title="Review {{$newNote->relatesToProject->project_title}} project" >
            {{$newNote->relatesToProject->project_title}}</a>
          </td>
          <td>{{$newNote->relatesToProject->fullAddress()}}</td>
          <td>{{$newNote->note}}</td>
<<<<<<< HEAD
          <td>{{$newNote->created_at->format('jS M g:i A')}}</td>
=======
          <td>{{$newNote->created_at ? $newNote->created_at->format('jS M g:i A') :''}}</td>
>>>>>>> development
          <td>{{$newNote->writtenBy->person->postName()}}</td>
        </tr>
      @endforeach
    </tbody>
  </table>


</div>