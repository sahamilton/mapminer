<div class="modal fade" id="noaddress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
          
             <h4 class="modal-title" id="myModalLabel">No Address Specified</h4>
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
			<div class="modal-body">
				<p><strong>Please enter an address to search from.</strong> You can enter an address, zip code or state code.</p>
        <form method="post" action="{{$action}}" >
          {{csrf_field()}}
          <input type="text" id="noaddress" name="search" />
          <input type="submit" class="btn btn-info  btn-xs" name="submit" value="Use this address" />
          
          <input type="hidden" name="type" value="map" />
          <input type="hidden" name="distance" value="25" />
          <input type="hidden" name="number" value="5" />
        </form>
        
			</div>
			
		</div>
	</div>
</div>