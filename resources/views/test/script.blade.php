<script>
//Select country first
$('select[name="country"]').on('change', function() {
    var countryId = $(this).val();

    $.ajax({
        type: "POST",
        url: "{{route('test.state')}}",
        data: {country : countryId, api_token:"{{auth()->user()->api_token}}"},
        success: function (data) {
                    //remove disabled from province and change the options
                    $('select[name="province"]').prop("disabled", false);
                    $('select[name="province"]').html(data.response);


           var options=$('<select/>');
            $.each(data, function(id, ob){
            options.append($('<option>',    
                      {value:ob.value,
                        text:ob.text}));
        });

     $('#province').html(options.html());
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