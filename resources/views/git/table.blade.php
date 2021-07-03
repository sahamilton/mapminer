<tbody>
    @foreach($versions as $version)
        <tr> 
            <td>{{$version->commitdate->format('Y-m-d H:i')}}</td>
            <td>{{$version->message}}</td>
            <td>{{$version->author}}
        </tr>
    @endforeach
    
</tbody>
