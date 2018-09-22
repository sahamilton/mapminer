<div class="" style="clear:both"></div>
<div class="" style="margin-top:10px;border:solid 1px #888888;padding-left:10px;padding-bottom: 10px">

    <h4>Assign Lead to Branch / Sales Team</h4>


    <form id="assignForm" method="post" action="{{route('webleads.assign')}}" >
        {{csrf_field()}}
        <div class="form-group col-lg-4">
            <label>Branch</label>
            <select id="branch" name="branch" class="form-control">
                <option selected>Select a Branch</option>
                @foreach ($branches as $branch)
                 <option value="{{$branch->id}}">{{$branch->branchname}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-4">
            <label>Sales Rep</label>
            <select id ="salesrep" name="salesrep" class="form-control" disabled>
                <option><-- Choose Sales Rep --></option>
            </select>
        </div>
        <div class="row"></div>
        <div class="form-group col-lg-4">
            <label>Notify Branch Manager</label>
            <input type="checkbox" name="notifymgr" />
        </div>
        <div class="form-group col-lg-4">
            <label>Notify Sales Manager</label>
            <input type="checkbox" name="notifysales" />
        </div>
        <div class="row"></div>
        <input type="hidden" name="lead_id" value="{{$lead->id}}" />
        <input class="btn btn-warning" type="submit" name="assign" value="Assign Lead" />
    </form>
</div>
  


<script>
//Select country first
$('select[name="branch"]').on('change', function() {
    var branchId = $(this).val();

    $.ajax({
        type: "POST",
        url: "{{route('api.branch.people')}}",
        data: {branch : branchId, api_token:"{{auth()->user()->api_token}}"},
        success: function (data) {

                    //remove disabled from salesrep and change the options
                    $('select[name="salesrep"]').prop("disabled", false);
                    var selectbox = $('#salesrep');
                    selectbox.empty();
                    var options ="<option value='' >Assign to Branch</option>";
                    //$('select[name="salesrep"]').html(data.response);
                   
                    for (var j = 0; j < data.length; j++){
                        options += "<option value='" +data[j].id+ "'>"+ data[j].firstname + ' ' + data[j].lastname + "</option>";
                    }
                    /*var options=$('<select/>');
                    $.each(data, function(id, ob){
                    options.append($('<option>',    
                              {value:ob.id,
                                text:ob.firstname + ' ' + ob.lastname}));
                    });*/

            selectbox.html(options);
            }
        });

});


</script>