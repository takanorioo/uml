$(window).load(function() {
	//Ajaxの設定

    $("#set").click(function(){

        var selector = $("text");

        var x = [];
        var y = [];
        var id = [];
        var count = 0;


        selector.each(function(){

            if(count % 3 == 0) {
                x[count/3] = $(this).attr("x") - 40;
                y[count/3] = $(this).attr("y");
            }
            count ++;
        });

        var selector = $("text");
        var id = [];
        var count = 0;

        selector.each(function(){
            if(count % 3 == 0) {
                id[count/3] = $(this).attr("font-weight");
            }
            count ++;
        });

        

        jQuery.ajax(
            '/uml/api/setlayout/setlayout',
                {
                    dataType: 'json',
                    data: {
                        "x": x,
                        "y": y,
                        "id": id
                    },
                    success: function( data ) {
                        alert("Set Layout");
                    },
                    error: function( data ) {
                        alert("Set Layout");
                    }
                }
            );
    });
});