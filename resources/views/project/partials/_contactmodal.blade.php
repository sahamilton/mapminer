<div class="modal fade" id="add-contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    
                    <h4 class="modal-title" id="myModalLabel">Confirm Add Contact</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
            
                <div class="modal-body">
                 
                    <form id="add-form" 
                            role="form"
                            action="{{route('projects.addcontact')}}" 
                            method="post">
                            <!-- contact -->
                                <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Name:</label>
                                        
                                            <input type="text" class="form-control" name='contact' description="contact" placeholder="name">
                                            <span class="help-block">
                                                <strong>{{ $errors->has('contact') ? $errors->first('name') : ''}}</strong>
                                                </span>
                                       
                                </div>
                             <!-- title -->
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        <label class="col-md-4 control-label">Title:</label>
                                          
                                                <input type="text" class="form-control" name='title' description="title"  placeholder="title">
                                                <span class="help-block">
                                                    <strong>{{ $errors->has('title') ? $errors->first('title') : ''}}</strong>
                                                    </span>
                                           
                                    </div>
                               <!-- contactemail
 -->
                                           <div class="form-group{{ $errors->has('contactemail') ? ' has-error' : '' }}">
                                               <label class="col-md-4 control-label">contactemail:</label>
                                                 
                                                       <input type="text" class="form-control" name='contactemail' description="contactemail" 
                                                       placeholder="contactemail">
                                                       <span class="help-block">
                                                           <strong>{{ $errors->has('contactemail') ? $errors->first('contactemail') : ''}}</strong>
                                                           </span>
                                                   
                                           </div>
                                <!-- phone -->
                                           <div class="form-group{{ $errors->has('contactphone') ? ' has-error' : '' }}">
                                               <label class="col-md-4 control-label">Phone:</label>
                                                 
                                                       <input type="text" class="form-control" name='contactphone' description="contactphone" 
                                                       placeholder="phone">
                                                       <span class="help-block">
                                                           <strong>{{ $errors->has('contactphone') ? $errors->first('contactphone') : ''}}</strong>
                                                           </span>
                                                   
                                           </div>                    
                         
                            {{ csrf_field() }}
                            <input type="hidden" name="id" id="id"n value='{{$location->project->id}}' />

                             <input type="hidden" name ="company_id" id = "company_id" value="" />
                    <p class="debug-url"></p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                   
                    <input type="submit" name="submit" value="Add" 
                        class="btn btn-success success">
                        
                </form>
                </div>
            </div>
        </div>
    </div>