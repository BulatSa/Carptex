$(document).ready(function(){

modalresize = function() {

var modal_height = $('#cboxLoadedContent').height();
var modal_width = $('#cboxLoadedContent').width();

var modalheight_modal = $('#cboxLoadedContent').height();
var modalwidth_modal = $('#cboxLoadedContent').width();

 if (modal_height != modalheight_modal  || modal_width != modalwidth_modal) {
    if (modalheight_modal !=0 && modal_height!= null) {
    	if (modalwidth_modal !=0 && modal_width!= null) {
   			$.colorbox.resize();
   	}
   }
 }
}

modal_colorbox = function() {
	var modal_colorboxInterval;
	var modal_colorboxtimeout;

    $('.imagebox').colorbox({
	 width: "auto",
	 height: "auto",
	 scrolling: true,
	 returnFocusOther: true,
	 reposition: false,
	 fixed: false,
	 maxHeight: "90%",
	 maxWidth: "90%",
	 innerHeight: "90%",
	 innerWidth: "90%",
	 opacity: 0.5,
	 overlayClose: true,
	 onOpen: function(){

	 },
	 onLoad: function(){
	 },
     onComplete: function () {
	    $('#colorbox').css('z-index','800');
	    $('#cboxOverlay').css('z-index','800');
	    $('#cboxOverlay').css('opacity','0.4');
	    $('#cboxWrapper').css('z-index','800');

        $.colorbox.resize();
		modal_colorboxInterval = setInterval( function() {
               //modalresize()
			 }, 2000 );
        },

	 onClosed: function(){
			 clearInterval(modal_colorboxInterval);
	 },

    });

return false;
}

modal_colorbox();

});