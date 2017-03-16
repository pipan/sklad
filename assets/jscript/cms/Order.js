Item = function(id){
	this.id = id;
	this.formId = 'form-' + this.id;
	this.n = id.substring(4);
	this.obj = $("#" + id);
	this.name = $(this.obj).find('.name').html();
	this.amount = 0;
	
	this.hideById = function(){
		if ($("#" + this.id) != undefined){
			$("#" + this.id).addClass("added");
		}
	};
	
	this.setAmount = function(a){
		if (a > 0){
			this.amount = a;
		}
		else{
			this.amount = 0;
		}
		$('#' + this.formId).find('input[name="amount[]"]').val(this.amount);
	};
	
	this.createForm = function(parent){
		ret = '<div id="' + this.formId + '" class="form-item">' +
			'<label>' + this.name + '</label>' +
				'<div class="form-item-actions">' +
			
					'<div class="cancel" onClick="order.removeItem(\'' + this.id + '\')"></div>' +
			 		'<div class="form-item-actions-counter">' +
			 		 '<div class="form-item-actions-counter-item">' +
			 		 	'<input type="hidden" name="item_id[]" value="' + this.n + '">' +
			 		 	'<input type="text" name="amount[]" value="0ks">' +
			 		 	'<input style="width: 160px;" type="text" name="amount_info[]" value="" placeholder="info o ks">' +
			 		 '</div>' +
				 	 '</div>' +
				 '</div>' +
			'</div>';
		/*
		ret = '<div id="' + this.formId + '" class="form-item">' +
					'<div class="cancel" onClick="order.removeItem(\'' + this.id + '\')"></div>' +
					'<div>' + this.name + '</div>' +
					'<input type="hidden" name="order_item_' + this.n + '" value="' + this.n + '">' +
				'</div>';
				*/
		if (parent == null){
			return ret;
		}
		else{
			$(parent).prepend(ret);
		}
	};
};

Order = function(){
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
			this.items[this.items.length - 1].createForm($("#form-body"));
			$("#" + id).addClass("added");
			if ($("#warning-" + id) != null){
				$("#warning-" + id).addClass("added");
			}
		}
		else{
			this.removeItem(id);
		}
	};
	this.removeItem = function(id){
		var i = this.getIndexById(id);
		if (i > -1){
			var obj = this.items[i];
			$("#" + obj.formId).remove();
			$("#" + obj.id).removeClass("added");
			if ($("#warning-" + obj.id)){
				$("#warning-" + obj.id).removeClass("added");
			}
			this.items.splice(i, 1);
		}
	};
	
	this.setAmount = function(id){
		var obj = this.getById(id);
		if (obj != null){
			var a = $("#" + obj.formId).find('input[name="amount[]"]').val();
			a = parseInt(a);
			if (isNaN(a) == true){
				a = 0;
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

order = new Order();