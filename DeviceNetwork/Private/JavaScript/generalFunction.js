
var Utility = {

	fn: {
		adjustMarkers: function(){
			let natW = $('img.map').prop('naturalWidth');	
			$(window).resize(function(){
				let newW = $('.map').width();
				let ratio = newW/natW;
				
				let markerHalfWidth = $("span.marker").width() / 2;
				let markerOrigHalfWidth = 10;
				$("span.marker").each(function(index, element) {
					$(element).css('top', (($(element).data("top") + markerOrigHalfWidth) * ratio) - markerHalfWidth);
					$(element).css('left', (($(element).data("left") + markerOrigHalfWidth) * ratio) - markerHalfWidth);
				});
									
				if($("#updateForms:visible")) {					
					$("#updateForms").css("left", newW + 20);
				};
			});

			$(window).resize();
		},
		
		changeFloor: function(){
			$('#floorSelect').change( function(){
				let url = $(this).val();
				console.log(url);
				window.location.assign(url);
			});
		},
			
		changeLibrary: function(){
			$('#changeLibrary').click(function(){
				let url = $(this).attr('data-url');
				console.log(url);
				window.location.assign(url);
			});
		},
		
		changeView: function(){
			let currentView = $('#changeView').data('view');
			//if(currentView === "port"){
				
			//}
		}
	},
	
	init: function() {
		this.fn.adjustMarkers();
		this.fn.changeFloor();
		this.fn.changeLibrary();
		this.fn.changeView();
	}
	
};		


window.onload = function() {
    Utility.init();

};