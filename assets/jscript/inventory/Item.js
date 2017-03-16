Item = function(id){
	this.id = id;
	this.formId = 'form-' + this.id;
	this.n = id.substring(4);
	this.amount = 0;
	this.state = -1;
	this.obj = $("#" + id);
	this.title = $(this.obj).find('.body-item-title').html();
	this.location = $(this.obj).find('.body-item-location').html();
	this.description = $(this.obj).find('.body-item-description').html();
	
	this.setAmount = function(a){
		if (a != this.amount){
			if (a == ""){
				this.amount = 0;
			}
			else{
				if (a > 0){
					this.amount = a;
				}
				else{
					a = 0;
					this.amount = 0;
				}
			}
			$('#' + this.formId).find('input[name="amount_show[]"]').val(a);
			$('#' + this.formId).find('input[name="amount[]"]').val(this.amount * this.state);
		}
	};
	
	this.hideById = function(){
		if ($("#" + this.id) != undefined){
			$("#" + this.id).addClass("added");
		}
	};
	
	this.getStateLabel = function(){
		if (this.state == -1){
			return "beriem";
		}
		return "vraciam";
	};
	this.getStateClass = function(){
		if (this.state == -1){
			return "out";
		}
		return "in";
	};
	this.changeState = function(){
		this.state *= -1;
		$('#' + this.formId).find('.form-item-state').removeClass('in');
		$('#' + this.formId).find('.form-item-state').removeClass('out');
		$('#' + this.formId).find('.form-item-state').addClass(this.getStateClass());
		this.setAmount(this.amount);
	};
	
	this.createForm = function(parent){
		ret = '<div id="' + this.formId + '" class="form-item">' +
					'<div class="form-item-actions">' +
				 		'<div class="form-item-actions-counter">' +
				 			'<div class="form-item-actions-counter-item">' +
				 				'<input type="button" value="-" onClick="inv.decAmount(\'' + this.id + '\');">' +
					 		 '</div>' +
					 		 '<div class="form-item-actions-counter-item">' +
					 		 	'<input type="hidden" name="item_id[]" value="' + this.n + '">' +
					 		 	'<input type="hidden" name="amount[]" value="' + this.amount + '">' +
					 		 	'<input type="text" name="amount_show[]" value="' + this.amount + '" onKeyUp="inv.setAmount(\'' + this.id + '\');" onCut="inv.setAmount(\'' + this.id + '\');" onPaste="inv.setAmount(\'' + this.id + '\');">' +
					 		 '</div>' +
					 		 '<div class="form-item-actions-counter-item">' +
					 		 	'<input type="button" value="+" onClick="inv.incAmount(\'' + this.id + '\');">' +
					 		 '</div>' +
					 	 '</div>' +
					 '</div>' +
					 '<div class="form-item-body" onClick="inv.changeState(\'' + this.id + '\')">' +
						'<div class="form-item-title">' + this.title + '</div>' +
						'<div class="form-item-state ' + this.getStateClass() + '"><div class="form-item-state-type out">beriem</div><div class="form-item-state-type in">vraciam</div></div>' +
						'<div class="form-item-description">' + this.description + '</div>' +
					'</div>' + 
				'</div>';
		if (parent == null){
			return ret;
		}
		else{
			$(parent).prepend(ret);
		}
	};
};

Inventory = function(){
	this.items = [];
	
	this.getById = function(id){
		for (var i = 0; i < this.items.length; i++){
			if (this.items[i].id == id){
				return this.items[i];
			}
		}
		return null;
	};
	this.getIndexById = function(id){
		for (var i = 0; i < this.items.length; i++){
			if (this.items[i].id == id){
				return i;
			}
		}
		return -1;
	};
	
	this.addItem = function(obj){
		var id = $(obj).attr('id');
		if (id.indexOf("warning") > -1){
			id = id.substring(8);
		}
		if (this.getById(id) == null){
			this.items.push(new Item(id));
			this.items[this.items.length - 1].createForm($("#download-items"));
			$("#" + id).addClass("added");
			this.setNotification();
			return true;
		}
		else{
			this.removeItem(id);
			return false;
		}
	};
	this.removeItem = function(id){
		var i = this.getIndexById(id);
		if (i > -1){
			var obj = this.items[i];
			$("#" + obj.formId).remove();
			$("#" + obj.id).removeClass("added");
			this.items.splice(i, 1);
		}
		this.setNotification();
	};
	this.changeState = function(id){
		var obj = this.getById(id);
		if (obj != null){
			obj.changeState();
		}
	};
	
	this.setNotification = function(){
		if (this.items.length > 0){
			$("#download-counter").show();
			$("#notifications .notification:eq(0)").removeClass("empty");
		}
		else{
			$("#download-counter").hide();
			$("#notifications .notification:eq(0)").addClass("empty");
		}
		$("#download-counter").html(this.items.length);
	};
	
	this.setAmount = function(id){
		var obj = this.getById(id);
		if (obj != null){
			var a = $("#" + obj.formId).find('input[name="amount_show[]"]').val();
			if (a != ""){
				a = parseInt(a);
				if (isNaN(a) == true){
					a = "";
				}
			}
			obj.setAmount(a);
		}
	};
	this.incAmount = function(id){
		var obj = this.getById(id);
		if (obj != null){
			obj.setAmount(obj.amount + 1);
		}
	};
	this.decAmount = function(id){
		var obj = this.getById(id);
		if (obj != null){
			obj.setAmount(obj.amount - 1);
		}
	};
	
	this.hideAll = function(){
		for (var i = 0; i < this.items.length; i++){
			this.items[i].hideById();
		}
	};
};