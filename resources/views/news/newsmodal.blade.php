    <div class="modal fade " 
        id="flashNews" 
        tabindex="-1" 
        role="dialog" 
        aria-labelledby="basicModal" 
        aria-hidden="true" 
        style = "margin:auto;
          max-width:100%;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-lg bg-">
            <div class="modal-header">
            
            <h4 class="modal-title" 
            id="myModalLabel">TrueBlue MapMiner Updates</h4>
            <button type="button" 
                class="close" 
                data-dismiss="modal" 
                aria-hidden="true">x
            </button>
            </div>
            <div class="modal-body" >
              
               @foreach ($news as $new)
                
                    <h4>{{$new->title}}</h4>
                    <p>{!!$new->news!!}</p>
                    
                @endforeach
            </div>
            <div class="modal-footer">
            <div class='pull-left'>
            <input 
            type='checkbox' 
            id='nonews' 
            name='noNews' />
            Check if you don't want to see any more old news!</div>
            <button type="button" 
                class="btn btn-success" 
                data-dismiss="modal">
                Close
            </button>
        </div>
    </div>
  </div>
</div>