<div class="">
    <table {{ $attributes->merge(['class' => 'table table-striped table-bordered table-condensed table-hover']) }}>
        <thead {{$attributes->merge(['class' =>''])}}>
            <tr>
                {{ $head }}
            </tr>
        </thead>

        <tbody {{$attributes->merge(['class' =>''])}}>
            {{ $body }}
        </tbody>
        @if(isset($footer))
        <tfoot {{$attributes->merge(['class' =>''])}}>
            {{ $footer }}
        </tfoot>

        @endif
    </table>
</div>
