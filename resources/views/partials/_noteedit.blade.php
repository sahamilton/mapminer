<script>
$(function(){
    $('.note').editable({
        url: '/admin/notes/update/' + $(this).id,
        title: 'Enter note',
        
        rows: 10
    });
});
</script>