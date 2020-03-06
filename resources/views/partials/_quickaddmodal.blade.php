<div class="modal fade" id="quickAdd" 
tabindex="-1" role="dialog" 
aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <div class="modal-header">
                    <button type="button" 
                    class="close" data-dismiss="modal" 
                    aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Quick Add</h4>
                </div>
                @php
                $types = ['','activity', 'lead', 'opportunity'];
                @endphp
                <div class="modal-body">
				    <form 
                    method = 'post'
                    action="{{route('quickadd')}}"
                    >
                    @csrf
                    <div class= "form-group">
                        <label >Quick Add</label>
                        <select 
                        onchange="this.form.submit()"
                        id='type'
                        name="type"
                        >
                            @foreach ($types as $type)
                                <option value="{{$type}}">
                                    {{ucwords($type)}}
                                </option>
                            @endforeach
                    </select>
                </div>
                <div class="float-right">
                    <input type="submit" 
                    class="btn btn-success"
                    name="QuickAdd" 
                    value="Quick Add" />
                </div>
            </form>
                </div>
                
                
            </div>
        </div>
    </div>