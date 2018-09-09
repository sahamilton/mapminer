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
        @if($vertical->people->count()>0)
        <a href="{{route('person.vertical',$vertical->id)}}"
        title= "See all people assigned to {{$vertical->filter}} industry">
            {{$vertical->people->count()}}
            </a>
        @else
            0
        @endif
    </td>
    <td class="text-right">
        @if($vertical->leads->count()>0)
            <a href="{{route('lead.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} leads">
            {{$vertical->leads->count()}}
            </a>

        @else
           0
        @endif
</td>
    <td class="text-right">
    @if($vertical->companies->count() > 0)
            <a href="{{route('company.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} companies">
            {{$vertical->companies->count()}}
            </a>
    @else
        0
    @endif

   

    </td>
    <td class="text-right">{{$vertical->locations()}}</td>
    <td class="text-right">{{$vertical->segment()}}</td>
    <td class="text-right">
        
        @if($vertical->campaigns->count()) > 0)
            <a href="{{route('salesactivity.vertical',$vertical->id)}}"
            title="See all {{$vertical->filter}} campaigns">
            {{$vertical->campaigns->count()}}
            </a>
        @else
            0
        @endif
    
    </td>

    </tr>
   @endforeach