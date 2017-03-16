$(document).ready(function(){
    var colorPicker = null;
    if ($("#color-picker") && typeof ColorPicker != "undefined"){
        colorPicker = new ColorPicker("color-picker");
    }
    
    $("a.delete").click(function(){
        return confirm("Chcete naozaj zmaza�?");
    });
    
    $("input.color-picker").focus(function(){
        if (colorPicker != null){
            $("#color-picker").closest(".pop-up").show();
            var color = $(this).val();
            console.log(color);
            if (color){
                colorPicker.fill({
                    r: parseInt(color.substring(0,2), 16),
                    g: parseInt(color.substring(2,4), 16),
                    b: parseInt(color.substring(4), 16)
                });
            }
            colorPicker.setHandOverObj({accept: function(color){
                $(this).val(color.toHexa());
                $(this).css({color: "#" + color.toHexa()});
            }, binding: this});
        }
    });
    
    $("input.color-picker").each(function(){
        var val = $(this).val();
        $(this).css({color: "#" + val});
    });
});