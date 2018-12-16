<h2>Nearby Branches</h2>
<table class="table" id = "sorttable">
            <thead>

                <th>Branch</th>
                <th>Manager</th>
                <th>Market Manager</th>
                <th>Business Manager</th>

                <th>Distance</th>

            </thead>
            <tbody>
                @foreach ($branches as $branch)
                 
                <tr> 
                    <td>
                        <a href="{{route('branches.show',$branch->id)}}" 
                        title="Review {{$branch->branchname}} details">
                            {{$branch->branchname}}
                        </a>
                </td>
                    <td>
                        @foreach ($branch->manager as $manager)
                        {{$manager->postName()}}
                        @endforeach
                    </td>
                    <td>
                        @foreach ($branch->marketmanager as $manager)
                        {{$manager->postName()}}
                        @endforeach
                    </td>
                    <td>
                        @foreach ($branch->businessmanager as $manager)
                        {{$manager->postName()}}
                        @endforeach
                    </td>
                    
                    <td>{{number_format($branch->distance,1)}} miles</td>
                </tr>  

                @endforeach
            </tbody>



        </table>