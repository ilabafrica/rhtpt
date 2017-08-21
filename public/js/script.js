
    
    $(document).ready(function(){
        $('#search_text').autocomplete({
            source:'search_facility',
            minLength:3,
            select:function(event, ui){
                console.log(ui);
                $('#search_item_id').val(ui.item_id);
                $('#search_text').val(ui.item);
            }
        });

        $(function() {
             $( ".datepicker" ).datepicker(); 
        });
    });

