<!-- Campaign Title -->
@bind($campaign)
<x-form-input 

    name="title"
    required
    label="Title"

/>

<x-form-textarea
 
    name="description"
    label="Description"
    />

<x-form-select 
    name="campaignmanager_id" 
    label="Campaign Manager:" 
    :options="$campaignmanagers" />





 <legend>Dates Available</legend>
<!--- Date From -->
    <x-form-input 
        type="date"
        name="datefrom"
        label='Date From:'

       
    />

<!--- Date To -->

<x-form-input 
        type="date"
        name="dateto"
        label='Date To:'
        
       
    />
<x-form-select 
    name="type" 
    label="Campaign Type:" 
    :options="['open'=>'Open', 'restricted'=>'Restricted']"
     />
 <legend>Focus on Companies or Industries</legend>
<x-form-select
    multiple 
    name="companies[]" 
    label="Companies:" 
    :options="$companies"
     />

<x-form-select
    multiple 
    name="industries[]" 
    label="Industries:" 
    :options="$industries"
     />



<!-- Organization Alignment -->

<legend>Organization</legend>

<x-form-select
    name='manager_id'
    :options='$managers'
    label="Organization"
    />

@if(is_array($servicelines))
<x-form-select
    multiple
    name='serviceline[]'
    :options='$servicelines'
    label="Service Line"
    />
@else
    <input type="hidden" name='serviceline[]' value="{{$servicelines}}" />
@endif
@endbind

