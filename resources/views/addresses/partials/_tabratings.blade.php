
 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Rater</th>
    <th>Rating</th>
    <th>Comments</th>
    <th>Date</th>

    </thead>
    <tbody>
      
         @foreach($location->ranking as $rating)
            <tr>
                <td>{{$rating->fullname()}}</td>
                <td>{{$rating->pivot->ranking}}</td>
                <td>{{$rating->pivot->comments}}</td>
                <td>{{$rating->pivot->created_at->format('Y-m-d')}}</td>
            </tr>
           @endforeach

    </tbody>
</table>
