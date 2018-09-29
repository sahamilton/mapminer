<!-- Modal -->
<div id="projectTransfer" class="modal fade" role="dialog">
  <div class="modal-dialog">
<?php $rank = ($project->owner[0]->pivot->ranking ? $project->owner[0]->pivot->ranking: 3);?>
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Transfer {!!$project->project_title!!} Project  </h4>
      </div>
      <div class="modal-body">
        <p>Please complete this form to transfer project</p>
        
        <form method="post" action="{{route('projects.transfer',$project->id)}}">
        {{csrf_field()}}
        <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
          <label class="col-md-4 control-label">Project Recipient</label>
           <div class="input-group input-group-lg ">
            <input  type="text" required id="search" name="username" placeholder="Type to search users" autocomplete="off" ><i class="far fa-search"></i>
           

           </div>
         </div>

         <div class="form-group{{ $errors->has('comments') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Reason for Transfer</label>
                <div class="input-group input-group-lg ">
                    <textarea required class="form-control" name='comments' title="comments" value="{{ old('comments') }}"></textarea>
                  
                        <span class="help-block">
                        <strong>{{$errors->has('comments') ? $errors->first('comments')  : ''}}</strong>
                        </span>
        
                </div>
            </div>
            <div class="pull-right">
           <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> <input type="submit" value="Transfer Project" class="btn btn-danger" />
            </div>
            <input type="hidden" name="project_id" value="{{$project->id}}" />
        </form><div class="modal-footer">
        
        
      </div>
      </div>

      
    </div>

  </div>
</div>
    <!-- Initialize typeahead.js on the input -->
    <script>
        $(document).ready(function() {
            var bloodhound = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/salesteam/find?q=%QUERY%',
                    wildcard: '%QUERY%'
                },
            });
            
            $('#search').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'users',
                source: bloodhound,
                display: function(data) {
                    return data.username  //Input value to be set when you select a suggestion. 
                },
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                    ],
                    header: [
                        '<div class="list-group search-results-dropdown">'
                    ],
                    suggestion: function(data) {
                        
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item"><a href=#>'
                         + data.person.firstname + ' ' + data.person.lastname + '</a></div></div>'
                    }
                }
            });
        });
    </script>
<script>
  $('#rank').on('starrr:change', function(e, value){
    
    $("#ranking").val(value),
    $('#ranklist').val(value);
  });
$('#ranklist').change (function(){

  $("#rank").val(this.value),
  $('#ranking').val(this.value);
});

</script>
