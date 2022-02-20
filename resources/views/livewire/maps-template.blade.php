<div>
    <h2>Search for {{$type}}</h2>
    {{$radius}} {{count(json_decode($data, true))}}

    <div class="row mb4" style="padding-bottom: 10px"> 
        <div class="col form-inline">
            <x-form-select 
            name="radius" :options="$range" 
            label="Distance: "
            wire:model='radius' />  
            <div  wire:loading>
                <div class="col spinner-border text-danger"></div>
            </div>
        </div>
    </div>
    @include('maps.newmap')


    <script>
        $(function() {
            $('#bh-sl-map-container').storeLocator({
                
                dataRaw: <?php echo json_encode($data); ?>,
                dataType: 'json',
                autoGeocode: true,
                slideMap : false,
                defaultLoc: true,
                defaultLat: {{$person->lat}},
                defaultLng : {{$person->lng}},
                pagination: true,
                nameSearch: true,
                

            });
        });
    </script>    
</div>
