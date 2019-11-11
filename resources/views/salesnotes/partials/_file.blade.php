
@php
    $company_name = str_replace(" ", "_", $company->companyname);
@endphp


@if (file_exists(public_path()."/documents/howtowork/".$company_name.".pdf"))
    <p> 
        <a 
            href ="{{asset('/documents/howtowork/'.$company_name.'.pdf')}}"
            title="View Sales Notes for ". $company->companyname." Notes">
                View SAles Notes for {{$company->companyname}}" Notes
        </a>
    </p>
@endif