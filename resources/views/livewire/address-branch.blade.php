<div>
    
    @if($rank)
        Location rated:   
    @else
        Rate location:
    @endif
    @foreach ($ranks as $int)
        <span wire:click="ranking({{$int}})">
        @if($int <= $rank)
            <i class="text-success fa-solid fa-star"></i>
        @else 
            <i class="fa-regular fa-star"></i>
        @endif
    </span>
        
    @endforeach
</div>


