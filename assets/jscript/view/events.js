$(document).ready(function(){
	inv = new Inventory();

	$(".notification").click(function(){
		$(".body").hide();
		if ($(this).hasClass("active")){
			$(this).removeClass("active");
			$(".body:eq(0)").show();
		}
		else{
			$(".notification").removeClass("active");
			$(this).addClass("active");
			i = $(".notification").index(this) + 1;
			$(".body:eq(" + i + ")").show();
		}
	});

	$(document).on('click', '.body-item', function(event){
		if (inv.addItem(this)){

			var clone = $(this).clone();
			$(this).parent(".body").append(clone);
			var offset = $(this).offset();
			$(clone).css({top: offset.top - $(document).scrollTop(), left: offset.left - $(document).scrollLeft()});
			$(clone).addClass('animation');
			setTimeout(function(){
				$(clone).css({top: "0px", left: "100%"});
			}, 20);
		}
	});

	$(document).on('click', '#storage_change', function(event){
		$(this).hide();
	});

	//hide storage change
	setTimeout(function(){
		if ($("#storage_change")){
			$("#storage_change").addClass("hide");
		}
	}, 4000);
	//kill storage change
	setTimeout(function(){
		if ($("#storage_change")){
			$("#storage_change").hide();
		}
	}, 5000);
});

$(document).on('click', 'input', function(e){
	e.stopPropagation();
});

var lock = false;

function login(){
	if (!lock){
		lock = true;
		$("#login-response").html("<img src='/assets/images/loading.gif'>");
		var post = $.post("/inventory/view/login", {uid: $('#login-pop-up input[name="uid"]').val()});
		post.done(function(data, textStatus, jqXHR){
			if (jqXHR.responseText !== "false"){
				$("#login-response").html(jqXHR.responseText + " <img src='/assets/images/accept.png'>");
				$("#login-response-name").html("ukladám zmeny <img src='/assets/images/loading.gif'>");
				$("#items-form").submit();
				$('#login-pop-up input[type="submit"]').prop('disabled', true);
				lock = false;
			}
			else{
				$("#login-response").html("nesprávne prihlásenie <img src='/assets/images/delete.png'>");
				$("#login-response-name").html("");
				lock = false;
			}
		});
		post.fail(function(jqXHR, textStatus, errorThrown){
			$("#login-response").html("chyba komunikácie <img src='/assets/images/delete.png'>");
			$("#login-response-name").html("");
			lock = false;
		});
	}
}

function search(){
	$("#body-loading").show();

	$(".body").hide();
	$(".notification").removeClass("active");
	$(".body:eq(0)").show();

	var category = [];
	$('input[name="category[]"]:checked').each(function(){
		category.push($(this).val());
	});

	var post = $.post("/inventory/view/search", {search: $('input[name="search"]').val(), category: category});
	post.done(function(data, textStatus, jqXHR){
		$("#body").html(jqXHR.responseText);
		$("#body-loading").hide();
		inv.hideAll();
	});
	post.fail(function(jqXHR, textStatus, errorThrown){
		$("#body").html("Ups, niečo nie je v poriadku.");
		$("#body-loading").hide();
	});
}
