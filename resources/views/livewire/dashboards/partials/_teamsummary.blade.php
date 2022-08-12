    <table  class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>
                <a wire:click.prevent="sortBy('id')" 
                role="button" href="#">
                    Team Member
                    @include('includes._sort-icon', ['field' => 'id'])
                </a>
            </th>
       
            @foreach($displayFields as $key=>$value)
            <th>
                <a wire:click.prevent="sortBy('{{$value}}')" 
                role="button" href="#">
                    {{ucwords(str_replace('_', ' ', $value))}}
                    @include('includes._sort-icon', ['field' => '{{$value}}'])
                </a>
            </th>
            

            @endforeach
         
        </thead>
        <tbody>
            @foreach ($team as $person)

                <tr>
                   <td>
                        
                            {{$person->manager}}
                        </a>
                    </td>
                    
                    @foreach($displayFields as $value)
                        @if(isset($value) && strpos($value, 'value'))
                            <td align='right'>${{number_format($person->$value,0)}}</td>
                        @else
                            <td align='center'>{{$person->$value}}</td>
                        @endif
                    @endforeach

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th>Period Total</th>
            @foreach($displayFields as $value)
                <td align='center'>
                    {{$team->sum($value)}}
                </td>
            @endforeach

        </tfoot>

    </table>
    