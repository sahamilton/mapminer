<h2>Nearby Sales Resources</h2>
<table class="table" id = "sorttable">
            <thead>

                <th>Person</th>
                <th>Role</th>
                <th>Manager</th>
                <th>Distance</th>

            </thead>
            <tbody>
                @foreach ($people as $person)
                 
                <tr> 
                    <td>{{$person->postName()}}</td>
                    <td>
                        <ul style="list-style-type:none">
                        @foreach ($person->userdetails->roles()->get() as $role)

                        <li>{{$role->display_name}}</li>
                        @endforeach
                    </ul>
                    </td>
                    <td>
                        @if($person->reportsTo)
                            {{$person->reportsTo()->first()->postName()}}
                        @endif
                    </td>
                    <td>{{number_format($person->distance,1)}} miles</td>
                </tr>  

                @endforeach
            </tbody>



        </table>