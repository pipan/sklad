colors = new Base();

Color = function(obj){
	this.property = new Property(obj.name, obj.value);
	this.name = obj.alias;
	this.value = new ColorValue(null, null, null);
	this.id = 'oib-color-id' + colors.add(this);
	this.editorId = obj.editorId;
	this.listCarier = obj.listCarier;
	this.forwardUpdate = obj.forward;
	if (this.listCarier != null){
		if (obj.list != null){
			this.listCarier.colorList = obj.list;
		}
		this.dynamic = 'list-body-dynamic-' + this.listCarier.dynamic();
	}
	
	this.getName = function(){
		return this.property.getName();
	};
	this.isSet = function(){
		return this.property.isSet();
	};
	this.isInList = function(color){
		for (var i = 0; i < this.listCarier.colorList.length; i++){
			if (this.listCarier.colorList[i].equals(color)){
				return true;
			}
		}
		return false;
	};
	this.forceValue = function(value){
		this.property.forceValue(value);
	};
	this.setValue = function(value){
		if (value != null){
			tmp = new ColorValue(value[0], value[1], value[2]);
			if (tmp.isColor()){
				this.value = tmp;
				this.property.setValue("rgb(" + tmp.r + ", " + tmp.g + ", " + tmp.b + ")");
			}
		}
		else{
			this.property.setValue(null);
		}
	};
	this.accept = function(value){
		if (value != null && value.isColor()){
			if (!this.isInList(value)){
				this.listCarier.colorList.push(new ColorValue(value.r, value.g, value.b));
				this.update({i:this.listCarier.colorList.length - 1});
				for (var i = 0; i < colors.list.length; i++){
					colors.list[i].reHTML();
				}
			}
		}
	};
	this.update = function(attr){
		if (attr.i >= 0){
			forwardUpdateAttr = {name:this.property.name, value:[this.listCarier.colorList[attr.i].r, this.listCarier.colorList[attr.i].g, this.listCarier.colorList[attr.i].b], id:this.editorId};
		}
		else{
			forwardUpdateAttr = {name:this.property.name, value:null, id:this.editorId};
		}
		this.forwardUpdate(forwardUpdateAttr);
	};
	this.reHTML = function(){
		ret = "";
		var end = see.colorList.length - see.maxColor;
		if (end < 0){
			end = 0;
		}
		for (var i = this.listCarier.colorList.length - 1; i >= end; i--){
			ret += '<div class="list-body-item-click">' +
						'<div class="color-sample" style="background-color: rgb(' + this.listCarier.colorList[i].r + ', ' + this.listCarier.colorList[i].g + ', ' + this.listCarier.colorList[i].b + ');" onClick="colors.update(\'' + this.id + '\',{i:' + i + '});"></div>' +
					'</div>';
		}
		ret += '<div class="list-body-item-click" onClick="colors.update(\'' + this.id + '\',{i:-1});">automatic</div>' +
				'<div class="list-body-item-click" onClick="see.showPopUp({popUpId:\'colorPicker\',id:\'' + this.id + '\'});">new</div>';
		$("#" + this.id).html(ret);
		//see.rule(null, null, this.editorId);
	};
	this.toHTML = function(editor, parent){
		var ret = '<div class="editor-subheader-item list-click">' +
						'<div class="list-name">' + this.name + '</div>' +
						'<div id="' + this.id + '" class="list-body">' +
							'<div class="list-body-item-click" onClick="colors.update(\'' + this.id + '\',{i:-1});">automatic</div>' +
							'<div class="list-body-item-click" onClick="see.showPopUp({popUpId:\'colorPicker\',id:\'' + this.id + '\'});">new</div>' +
						'</div>' +
				'</div>';
		if (parent != null){
			$("#" + parent).append(ret);
			return null;
		}
		else{
			return ret;
		}
	};
	this.toString = function(){
		return this.property.toString();
	};
};