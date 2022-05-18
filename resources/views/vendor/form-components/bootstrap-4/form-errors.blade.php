@error($name, $bag)
    <div {!! $attributes->merge(['class' => 'text-danger']) !!}>
        {{ $message }}
    </div>
@enderror