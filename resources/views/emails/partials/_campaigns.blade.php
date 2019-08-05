<fieldset>
    <legend>Current Campaigns</legend>
    <ul style="list-style-type: none"> 
        <li>
            <input type="checkbox" name="parent[]" id="checkAll" value="">  
            Check All    
        </li> 

            
        @foreach($campaigns as $campaign)

       
        <li>
     
            <input type="checkbox"  name="campaign[]" value="{{$campaign->id}}}"/>

            {{$campaign->title}}
        </li>


     


    @endforeach
</ul>
</fieldset>
        
