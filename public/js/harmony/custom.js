/*Function to toggle password fields*/
function toggle(className, obj){
    var $input = $(obj);
    if($input.prop('checked'))
        $(className).hide();
    else
        $(className).show();
}
/*Function to toggle matrix fields*/
function untoggle(className, obj){
    var $input = $(obj);
    if($input.prop('checked'))
        $(className).show();
    else
        $(className).hide();
}
/*Function to toggle options applicable to fields*/
function options(className, obj){
    var $input = $(obj);
    if($input.val() == 4)
        $(className).show();
    else
        $(className).hide();
}
/*End toggle function*/
/* Bootstrap 3 datepicker */
$(function () {
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
});
/* End datepicker */
//DataTables search functionality
$(document).ready( function () {
	var table = $('.search-table').DataTable({
    	'bStateSave': true,
        lengthChange: false,
        buttons: [ 'copy', 'excel', 'pdf', 'print', 'colvis' ],
    	'fnStateSave': function (oSettings, oData) {
        	localStorage.setItem('.search-table', JSON.stringify(oData));
    	},
    	'fnStateLoad': function (oSettings) {
        	return JSON.parse(localStorage.getItem('.search-table'));
    	}
	});
    table.buttons().container().appendTo( '#example_wrapper .col-md-6:eq(0)' );
});
