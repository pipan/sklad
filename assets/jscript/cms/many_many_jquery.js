/*
 * OBJ
 * genPattern: geenrujuci html kod pre novu vec
 * parent: rodicovske id
 * structure: struktura formulara, jedneho zaznamu
 * 
 * %n - nahradza za cislo count
 */
function Many_many(obj){
	
	this.genPattern = obj.genPattern;
	this.parent = $("#" + obj.parent);
	this.structure = obj.structure;
	this.count = 0;
	
	this.replacePattern = function(){
		tmp = this.genPattern;
		tmp = replaceAll(tmp, "%n", this.count);
		return tmp;
	};
	
	this.addEmpty = function(){
		this.count++;
		$(this.parent).append(this.replacePattern());
	};
	this.add = function(value){
		this.addEmpty();
		for (var i = 0; i < this.structure.length; i++){
			tmp = this.structure[i];
			tmp = replaceAll(tmp, "%n", this.count);
			$(this.parent).find("[name='" + tmp + "']").val(value[i]);
		}
	};
	
	this.remove = function (n){
		var x = 0;
		var v = [];
		for (var i = 1; i <= this.count; i++){
			if (i != n){
				v[x] = [];
				for (var j = 0; j < this.structure.length; j++){
					tmp = this.structure[j];
					tmp = replaceAll(tmp, "%n", i);
					v[x][j] = $(this.parent).find("[name='" + tmp + "']").val();
				}
				x++;
			}
		}
		$(this.parent).html("");
		this.count = 0;
		for (i = 0; i < x; i++){
			this.add(v[i]);
		}
	};
}

//events
$(document).ready(function(){
	$("#categories").on("mouseover", ".many-many-body", function(event){
		$(this).addClass("over");
	});
	$("#categories").on("mouseout", ".many-many-body", function(event){
		$(this).removeClass("over");
	});
});