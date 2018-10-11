<h4>Company Sales Notes</h4>
<table id ='sorttable3' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Company</th>
        <th>Notes</th>
    </thead>
    <tbody>
        @foreach($data['nosalesnotes'] as $company)
            @if (isset($company->notes))
            <tr class="success"> 
                <td><a href="route('salesnotes',$company->id)}}">{{$company->companyname}}</a>  </td>
<<<<<<< HEAD
                <td><i class="fa fa-check-circle text-success" aria-hidden="true"></i>
=======
                <td><i class="far fa-check-circle text-success" aria-hidden="true"></i>
>>>>>>> development
</td></tr>
            @else
            <tr class='danger'>
                <td>{{$company->companyname}}</td>
<<<<<<< HEAD
                <td><i class="fa fa-minus-circle text-danger" aria-hidden="true"></i>No 'How to Sell' Notes</td>
=======
                <td><i class="far fa-minus-circle text-danger" aria-hidden="true"></i>No 'How to Sell' Notes</td>
>>>>>>> development
            </tr>
            @endif
        @endforeach
    </tbody>
</table>

