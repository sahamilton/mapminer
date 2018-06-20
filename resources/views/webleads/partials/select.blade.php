<form id="myForm">
    <div class="form-group col-lg-2">
        <label>Branch</label>
        <select id="branch" name="branch" class="form-control">
            <option selected value="">Select a Branch</option>
            @foreach ($branches as $branch)
            <option value="{{$branch->id}}">{{$branch->branchname}}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label>Sales Rep</label>
        <select id ="salesrep" name="salesrep" class="form-control" disabled>
            <option value="1"><-- Choose Sales Rep --></option>
        </select>
    </div>

   
</form>

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
                    var options ='';
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



//once all field are set, submit
$('#myForm').submit(function () { 
    $.ajax({
        type: "POST",
        url: "{{route('test.send')}}",
        data: $('#myForm').serialize(),
        success: function (data) {
                //success
        }
      });
    });

</script>