<table>
    <tbody>    
        <tr>
            <td>Parent</td>
            <td>Vertical</td>
            <td>People</td>
            <td>Current Leads</td>
            <td>Companies</td>
            <td>Current Campaigns</td>
        </tr>
        @foreach($verticals as $vertical)
            <tr>
                <td>{{$vertical->getAncestors()->last()->filter }}</td>
                <td>{{$vertical->filter}}</td>
                <td>{{$vertical->people_count}}</td>
                <td>{{$vertical->leads_count}}</td>
                <td>{{$vertical->companies_count}}</td>
                <td>{{$vertical->campaigns_count}}</td>
            </tr>
        @endforeach
    </tbody>
</table>