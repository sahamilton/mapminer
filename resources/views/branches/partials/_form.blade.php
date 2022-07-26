@php
$state = new App\State;
$states = $state->getStates();

$regions = [ '1'=>'Western' ,'2'=>'CLP','3'=>'Eastern','4'=>'Mid-America & Canada
'];

@endphp
<div class="container" style="margin-top:40px">
    @if(isset($branch))
        @bind($branch)
    @endif
        <!-- id -->
        <div class="form-group {{ $errors->has('id') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
                name='id'
                required
                placeholder="# branch id" 
                label="Branch Number:" 
                />
            
        </div>


        <!-- branchname -->
        <div class="form-group{{ $errors->has('branchname') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
                required
                name='branchname'
                placeholder="Branch Name" 
                label="Branch Name:" 
                />
            
        </div>

        <!-- street -->
        <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
            
            <x-form-input 
                type="text" 
                required
                name='street'
                placeholder="Street" 
                label="Street:" 
                />
        </div>

        <!-- address2 -->
        <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
                
                name='address2'
                placeholder="Suite / Unit" 
                label="Suite / Unit:" 
                />
        </div>



        <!-- city -->
        <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
            <div class="form-group{{ $errors->has('address2') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
                required
                name='city'
                placeholder="City" 
                label="City:" 
                />
        </div>
        </div>
        <!----- state --------->

        <div class="form-group{{ $errors->has('state)') ? ' has-error' : '' }}">
            <x-form-select
                required
                name="state"
                label="State:"
                :options='$states'
                 />
        </div>

        <!-- zip -->
        <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
                required
                name='zip'
                placeholder="Zip / Postal Code" 
                label="Zip / Postal Code:" 
                />
        </div>
        <!-- phone -->
        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
               
                name='phone'
                placeholder="Branch Phone#" 
                label="Branch Phone#:" 
                />
        </div>
        <!-- email -->
        <div class="form-group{{ $errors->has('branchemail') ? ' has-error' : '' }}">
            <x-form-input 
                type="text" 
               
                name='branchemail'
                placeholder="branchID-br@peopleready.com" 
                label="Branch Email:" 
                />
        </div>

        <!-- radius -->
        <div class="form-group{{ $errors->has('radius') ? ' has-error' : '' }}">
            <x-form-input 
                type="number" 
                step=1
                min=0
                default=25
                required
                name='radius'
                label="Service radius in Miles"
                oninput="validity.valid||(value='');" 
                />
        </div>
    @if(isset($branch))
        @endbind
    @endif
    <!---------- Servicelines   ---------------->
    <div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
        @if(isset($branch))
            <x-form-select
                required
                name="serviceline[]"
                label="Servicelines:"
                :options='$servicelines'
                
                :bind="$branch->servicelines"
                />
        @else
            <x-form-select
                required
                name="serviceline[]"
                label="Servicelines:"
                :options='$servicelines'
                
                />

        @endif 
    </div>


    

</div>