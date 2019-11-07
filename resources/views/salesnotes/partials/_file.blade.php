
@php
    $company_name = str_replace(" ", "_", $company->companyname);
@endphp


@if (file_exists(public_path()."/documents/howtowork/".$company_name.".pdf"))
    <p> 
        <a 
            href ="{{asset('/documents/howtowork/'.$company_name.'.pdf')}}"
            title="View How To Sell to ". $company->companyname." Notes">
                View How To Sell to {{$company->companyname}}" Notes
        </a>
    </p>
@endif