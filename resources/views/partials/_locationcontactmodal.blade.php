<div class="modal fade" id="add-locationcontact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Add Contact</h4>
                </div>
            
                <div class="modal-body">
                 
                    <form id="add-form" 
                            role="form"
                            action="{{route('location.addcontact')}}" 
                            method="post">
                            <!-- firstname -->
                                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">First Name:</label>
                                        
                                            <input type="text" class="form-control" name='firstname' description="contact" placeholder="first name">
                                            <span class="help-block">
                                                <strong>{{ $errors->has('firstname') ? $errors->first('firstname') : ''}}</strong>
                                                </span>
                                       
                                </div>
                            <!-- lastname -->
                                <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Last Name:</label>
                                        
                                            <input type="text" class="form-control" name='lastname' description="contact" placeholder="last name">
                                            <span class="help-block">
                                                <strong>{{ $errors->has('lastname') ? $errors->first('lastname') : ''}}</strong>
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
                               <!-- email -->
                                           <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                               <label class="col-md-4 control-label">Email:</label>
                                                 
                                                       <input type="text" class="form-control" name='email' description="email" 
                                                       placeholder="email">
                                                       <span class="help-block">
                                                           <strong>{{ $errors->has('email') ? $errors->first('email') : ''}}</strong>
                                                           </span>
                                                   
                                           </div>
                                <!-- phone -->
                                           <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                               <label class="col-md-4 control-label">Phone:</label>
                                                 
                                                       <input type="text" class="form-control" name='phone' description="phone" 
                                                       placeholder="phone">
                                                       <span class="help-block">
                                                           <strong>{{ $errors->has('phone') ? $errors->first('phone') : ''}}</strong>
                                                           </span>
                                                   
                                           </div>                    
                         
                            {{ csrf_field() }}
                            <input type="hidden" name="location_id" id="location_id" value="" />
                             
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