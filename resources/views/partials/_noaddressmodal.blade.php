<div class="modal fade" id="noaddress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">No Address Specified</h4>
                </div>
			<div class="modal-body">
				<p><strong>Please enter an address to search from.</strong> You can enter an address, zip code or state code.</p>
        <form method="post" action="{{$action}}" >
          {{csrf_field()}}
          <input type="text" id="address" name="address" />
          <input type="submit" name="submit" value="Use this address" />

          <input type="hidden" name="type" value="map" />
        </form>
			</div>
			<div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
            </div>
		</div>
	</div>
</div>