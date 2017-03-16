sliders = new Base();

Slider = function(value, range, obj){
	this.range = range;
	this.id = 'slider-id' + sliders.getId();
	this.label = new Label(value, this, {type:'l'});
        this.pinText = obj.pinText;
	this.forwardUpdate = obj.update;
	this.forwardUpdateAttr = obj.attr;
	this.forwardUpdateAttr.type = 'p';
	sliders.list.push(this);
	
	this.setSlider = function(value){
		this.label.setValue(this.range.verify(value));
		width = $("#" + this.id).css('width').replace(/[^-\d\.]/g, '') - $("#" + this.id + " > .slider-pin").css('width').replace(/[^-\d\.]/g, '');
		l = this.range.getLinear(this.label.value) * width;
		$("#" + this.id + " > .slider-pin").css('left', l + 'px');
	};
	this.setLabel = function(value){
		this.label.setValue(value);
	};
	this.update = function(attr){
		switch (attr.type){
		case 's':
			width = $("#" + this.id).css('width').replace(/[^-\d\.]/g, '') - $("#" + this.id + " > .slider-pin").css('width').replace(/[^-\d\.]/g, '');
			l = $("#" + this.id + " > .slider-pin").offset().left - $("#" + this.id).offset().left;
			this.setLabel(Math.round(this.range.min + l / width * this.range.getRange()));
			break;
		case 'l':
			this.setSlider(this.label.value);
			break;
		}
		this.forwardUpdateAttr.colorValue = this.label.value;
		this.forwardUpdate.update(this.forwardUpdateAttr);
	};
	this.toHTML = function(){
		var ret = '<div class="oib-slider-pane"><div id="' + this.id + '" class="slider" style="width: ' + obj.width + ';height: ' + obj.height + ';"><div class="slider-line"></div><div class="slider-pin" style="width: ' + obj.height + ';height: ' + obj.height + ';">' + this.pinText + '</div></div><div class="oib-slider-label">' + this.label.toHTML() + '</div></div>'; 
		return ret;
	};
};


$(document).ready(function(){
	var sliderDrag = false;
	var prevX = 0;
	
	$(".slider-body").on('click', function(){
		//jump tu click position
	});
	
	$(".slider-pin").on('mousedown', function(event) {
		m = this;
		prevX = event.pageX;
	    $(window).mousemove([m, prevX], function(e) {
	    	sliderDrag = true;
	        parentWidth = $(m).closest(".slider").css('width').replace(/[^-\d\.]/g, '');
			pinWidth = $(m).css('width').replace(/[^-\d\.]/g, '');
			parentL = $(m).closest(".slider").offset().left;
			l = $(m).offset().left - parentL + e.pageX - prevX;
			if (l < 0){
				l = 0;
			}
			if (l > parentWidth - pinWidth){
				l = parentWidth - pinWidth;
			}
			$(m).css('left', l + 'px');
			prevX = e.pageX;
			sliders.update($(m).closest(".slider").attr('id'), {type:'s'});
	    });
	});
	$(document).mouseup(function() {
	    var sliderDragPrev = sliderDrag;
	    sliderDrag = false;
	    $(window).unbind("mousemove");
	    if (!sliderDragPrev) {
	    	//was clicking
	    }
    });
});