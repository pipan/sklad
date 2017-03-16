Range = function(min, max){
	this. min = min;
	this.max = max;

	this.verify = function(value){
		if (value < this.min){
			return this.min;
		}
		else if (value > this.max){
			return this.max;
		}
		else{
			return value;
		}
	};
	this.getRange = function(){
		return this.max - this.min;
	};
	this.getLinear = function(value){
		return (value - this.min) / this.getRange();
	};
};