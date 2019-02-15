<div id="editopportunity" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">

        <h4 class="modal-title">Edit {!! $location->businessname !!} Opportunity  </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Edit Opportunity</strong></p>
        <form method="post" action="" name="action-form" id="action-form">
          @method('put')
          @csrf
          @include('opportunities.partials._opportunityform')
          
            <div class="float-right">
            <input type="submit" value="Edit Opportunity" class="btn btn-success" />
          </div>
        </form>
      </div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
