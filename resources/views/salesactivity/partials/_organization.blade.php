<select name="org[svp]" id="SVP" class="form-control input-lg">
    <option value="">Select SVP</option>
   </select>
   <br />
   <select name="org[rvp]" id="RVP" class="form-control input-lg">
    <option value="">Select RVP</option>
   </select>
   <br />
   <select name="org[mm]" id="Market_Manager" class="form-control input-lg">
    <option value="">Select Market Manager</option>
   </select>

<script>
$(document).ready(function(){

 load_json_data('SVP');

 function load_json_data(id, parent_id)
 {
  var html_code = '';

  $.getJSON('{{asset('salesorg.json')}}', function(data){

   html_code += '<option value="">Select '+id+'</option>';
   $.each(data, function(key, value){
    if(id == 'SVP')
    {
     if(value.parent_id == '2980')
     {
      html_code += '<option value="'+value.id+'">'+value.fullname+'</option>';
     }
    }
    else
    {
     if(value.parent_id == parent_id)
     {
      html_code += '<option value="'+value.id+'">'+value.fullname+'</option>';
     }
    }
   });
   $('#'+id).html(html_code);
  });

 }

 $(document).on('change', '#SVP', function(){
  var SVP_id = $(this).val();
  if(SVP_id != '')
  {
   load_json_data('RVP', SVP_id);
  }
  else
  {
   $('#RVP').html('<option value="">Select RVP</option>');
   $('#Market_Manager').html('<option value="">Select Market Manager</option>');
  }
 });
 $(document).on('change', '#RVP', function(){
  var RVP_id = $(this).val();
  if(RVP_id != '')
  {
   load_json_data('Market_Manager', RVP_id);
  }
  else
  {
   $('#Market_Manager').html('<option value="">Select Market Manager</option>');
  }
 });
});
</script>