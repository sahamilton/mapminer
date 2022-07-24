<div>
    <div class="page-header">
        <h3>Edit {{$branch->branchname}}</h3>
        <p>{{$branch->fullAddress()}}</p>
        @foreach($branch->manager as $manager)
            <p>Branch Manager: {{$manager->fullName()}}</p>
        @endforeach
        <table>
            <tbody>
                @foreach($branch->manager->directReports as $reports)

                    <td>{{$reports->fullName()}}</td>
                    <td>@foreach($reports->userdetails->roles as $role)</td> 

                @endforeach
            </tbody>
        </table>
               
    </div>

</div>
