cPickers = new Base();

ColorPicker = function(id){
	this.handOverObj = null;
	this.color = new ColorValue(0, 0, 0);
	this.id = id;
	this.obj = $("#" + this.id);
	cPickers.list.push(this);
	
	this.sliderRed = new Slider(0, new Range(0, 255), {width:'200px', height:'20px', update:this, attr:{colorType: 'r'}, pinText: 'R'});
	this.sliderGreen = new Slider(0, new Range(0, 255), {width:'200px', height:'20px', update:this, attr:{colorType: 'g'}, pinText: 'G'});
	this.sliderBlue = new Slider(0, new Range(0, 255), {width:'200px', height:'20px', update:this, attr:{colorType: 'b'}, pinText: 'B'});
	
	this.setColor = function(type, value){
		if (this.color.isColorValue(value)){
			this.color.setColorValue(type, value);
			$("#" + this.id + "-preview").css('background-color', 'rgb(' + this.color.r + ', ' + this.color.g + ', ' + this.color.b + ')');
		}
	};
	
	this.fill = function(obj){
	    if (obj.r !== undefined){
                this.sliderRed.setSlider(obj.r);
                this.setColor('r', obj.r);
            }
            if (obj.g !== undefined){
                this.sliderGreen.setSlider(obj.g);
                this.setColor('g', obj.g);
            }
            if (obj.b !== undefined){
                this.sliderBlue.setSlider(obj.b);
                this.setColor('b', obj.b);
            }
	};
	
	this.update = function(attr){
		switch (attr.type){
			case 'h':
				this.handOver();
				break;
			case 'p':
				this.setColor(attr.colorType, attr.colorValue);
				break;
		}
	};
	this.setHandOver = function(id){
		var obj = colors.getById(id);
		if (obj != null){
			this.handOverObj = obj;
			return true;
		}
		return false;
	};
        this.setHandOverObj = function(obj){
            this.handOverObj = obj;
            return true;
	};
	this.handOver = function(){
		if (this.handOverObj !== null){
                    if (this.handOverObj.binding !== undefined){
                        this.handOverObj.accept.apply(this.handOverObj.binding, [this.color]);
                    }
                    else{
                        this.handOverObj.accept(this.color);
                    }
		}
		$(this.obj).hide();
	};
	
	this.toHTML = function(){
		var ret ='<div class="prepare-center"><div class="out-center"><div class="pop-up-body in-center">' +
			'<div>' + this.sliderRed.toHTML() + '</div>' +
			'<div>' + this.sliderGreen.toHTML() + '</div>' +
			'<div>' + this.sliderBlue.toHTML() + '</div>' +
			'<div id="' + this.id + '-preview" class="color-picker-preview" style="background-color: rgb(0, 0, 0);"></div>' +
			'<div class="form-line"><input type="button" value="set" onClick="cPickers.update(\'' + this.id + '\', {type:\'h\'});"></div>' +
		'</div></div></div>';
		return ret;
	};
	
	$(this.obj).append(this.toHTML());
};