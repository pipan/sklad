ColorValue = function(r, g, b){
	this.r = r;
	this.g = g;
	this.b = b;
	
	this.isColorValue = function(color){
		if (color >= 0 && color < 256){
			return true;
		}
		return false;
	};
	this.setColorValue = function(type, value){
		switch (type){
		case 'r': this.r = value;
			break;
		case 'g': this.g = value;
			break;
		case 'b': this.b = value;
		break;
		}
	};
	this.isColor = function(){
		if (this.isColorValue(this.r) && this.isColorValue(this.g) && this.isColorValue(this.b)){
			return true;
		}
		return false;
	};
	this.setColor = function(r, g, b){
		if (this.isColor(r, g, b)){
			this.r = r;
			this.g = g;
			this.b = b;
		}
	};
	this.equals = function(color){
		if (this.r == color.r && this.g == color.g && this.b == color.b){
			return true;
		}
		return false;
	};
	this.toString = function(){
		return 'rgb(' + this.r + ',' + this.g + ',' + this.b + ')';
	};
        this.toHexa = function(){
            var ret = "";
            //R
            if (this.r < 16){
                ret += "0" + this.r.toString(16);
            }
            else{
                ret += this.r.toString(16);
            }
            //G
            if (this.g < 16){
                ret += "0" + this.g.toString(16);
            }
            else{
                ret += this.g.toString(16);
            }
            //B
            if (this.b < 16){
                ret += "0" + this.b.toString(16);
            }
            else{
                ret += this.b.toString(16);
            }
            return ret;
	};
};