@foreach($verticals as $vertical)
    <?php $parent = $vertical->getAncestors()->last()?>
   
    <tr>
    <td>
    @if(isset($parent))
    {{$parent->filter}}
    @endif
    </td>
    <td class="text-left">{{$vertical->filter}}</td>
   
    
    <td class="text-right">
        @if(count($vertical->people)>0)
        <a href="{{route('person.vertical',$vertical->id)}}"
        title= "See all people assigned to {{$vertical->filter}} industry">
            {{count($vertical->people)}}
            </a>
        @else
            0
        @endif
    </td>
    <td class="text-right">
        @if(count($vertical->leads)>0)
            <a href="{{route('lead.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} leads">
            {{count($vertical->leads)}}
            </a>

        @else
           0
        @endif
</td>
    <td class="text-right">
    @if(count($vertical->companies) > 0)
            <a href="{{route('company.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} companies">
            {{count($vertical->companies)}}
            </a>
    @else
        0
    @endif

   

    </td>
    <td class="text-right">{{$vertical->locations()}}</td>
    <td class="text-right">{{$vertical->segment()}}</td>
    <td class="text-right">
        
        @if(count($vertical->campaigns) > 0)
            <a href="{{route('salesactivity.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} campaigns">
            {{count($vertical->campaigns)}}
            </a>
        @else
            0
        @endif
    
    </td>

    </tr>
   @endforeach