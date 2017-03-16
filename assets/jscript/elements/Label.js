labels = new Base();

Label = function(value, update, attr){
	this.value = value;
	this.id = "oib-label-id" + labels.getId();
	this.forwardUpdate = update;
	this.forwardUpdateAttr = attr;
	
	labels.list.push(this);
	
	this.setValue = function(value){
		this.value = value;
		$("#" + this.id).val(this.value);
	};
	this.update = function(attr){
		this.value = $("#" + this.id).val();
		this.forwardUpdate.update(this.forwardUpdateAttr);
	};
	this.toHTML = function(){
		var ret = '<input id="' + this.id + '" type="text" class="decimal oib-label" value="' + this.value + '">';
		return ret;
	};
};

$(document).ready(function(){
	
	$("input").keyup(function(){
		labels.update($(this).attr('id'), null);
	});
	$("input").change(function(){
		labels.update($(this).attr('id'), null);
	});
});