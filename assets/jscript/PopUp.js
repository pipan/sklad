$(document).ready(function(){
	
	$(".pop-up").on('click', '.pop-up-body', function(event){
		event.stopPropagation();
	});
	$(".pop-up").click(function(){
		$(this).hide();
	});
});