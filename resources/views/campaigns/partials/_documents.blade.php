<div class="row" 
    style="margin-top:20px;margin-bottom:20px">
        <button 
        type="button" 
        class="btn btn-info btn-block col-sm4" 
        data-toggle="collapse" 
        data-target="#documents">
            Documents
        </button>
    </div>
    <div class="collapse col-sm-8" 
        id="documents">     
        <table
            class="table table-striper"
            id="sorttable3"

        >
        <thead>
            <th>Title</th>
            <th>Description</th>
            <th>Type</th>
            <th>Link</th>
        </thead>
        <tbody>
        @foreach ($campaign->documents as $document)
        <tr>
            <td>{{$document->title}}</td>
            <td>{{$document->description}}</td>
            <td>{{$document->type}}</td>
            <td><a href="{{$document->link}}">{{$document->link}}</a></td>
        </tr>
        @endforeach
        </tbody>

            </table>
    </div>