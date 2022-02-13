<div wire:ignore.self class="modal fade" 
  id="userCreateModal" tabindex="-1" role="dialog" 
  aria-labelledby="exampleModalLabel" 
  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient" class="form-control-label">Recipient:</label>
            <input type="text" class="form-control" id="recipient" wire:model="recipient">
          </div>
          <div class="form-group">
            <label for="message" class="form-control-label">Message:</label>
            <textarea class="form-control" id="message" wire:model="message"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
  </div>
</div>