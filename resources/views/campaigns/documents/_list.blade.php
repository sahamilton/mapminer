<div class="row" >
    
      
    <table class="table table-striper">
        <thead>
            <th>Created</th>
            <th>Title</th>
            <th>Description</th>
            <th>Type</th>
            <th>Link</th>
            <th>Campaign</th>
        </thead>
        <tbody>
        @foreach ($documents as $document)
        <tr>
            <td>{{$document->created_at->format('Y-m-d')}}</td>
            <td>{{$document->title}}</td>
            <td>{{$document->description}}</td>
            <td>{{$document->type}}</td>
            <td><a href="{{$document->link}}">{{$document->link}}</a></td>
            <td>{{$document->campaign->title}}</td>
        </tr>
        @endforeach
        </tbody>

    </table>
</div>