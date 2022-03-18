<div>
    @if($address->isCustomer)
                <div class="progress-bar bg-success text-dark">
                    Customer <i wire:click="convert()" class="fa-solid fa-arrow-rotate-right" title="Convert  {{$address->businessname}} back to lead"></i>
                </div>
    @else
        <div class="progress-bar bg-warning text-dark">
            Lead<i wire:click="convert()" class="fa-solid fa-arrow-rotate-left" title="Convert  {{$address->businessname}} to customer"> </i>
        </div>
    @endif
</div>
