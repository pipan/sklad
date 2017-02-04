Base = function(){
	this.list = [];
	this.ids = 0;
	
	this.getId = function(){
		this.ids++;
		return this.ids;
	};
	this.add = function(item){
		this.list.push(item);
		return this.getId();
	};
	this.getById = function(id){
		for (var i = 0; i < this.list.length; i++){
			if (this.list[i].id == id){
				return this.list[i];
			}
		}
		return null;
	};
	this.update = function(id, attr){
		var tmp = this.getById(id);
		if (tmp != null){
			tmp.update(attr);
		}
	};
};