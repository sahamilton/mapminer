<table class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
             <th>Commit Date</th>
             <th>Message</th>
             <th>Author</th>     
        </thead>
        <tbody>
            @foreach($versions as $version)
                <tr> 
                    <td>{{$version->commitdate->format('Y-m-d H:i')}}</td>
                    <td>{{$version->message}}</td>
                    <td>{{$version->author}}
                </tr>
            @endforeach
            
        </tbody>
    </table>