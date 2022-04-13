const ICON = '<img class="icon" src="../Private/Styling/plugIcon.png" alt="" width="16" height="16">' ; 
var lookAtMe = 0 ;
var searching = 0 ;

var Markers = {
	fn: {
		addMarkers: function(){
			var target = $('#image-wrapper');
			var data = target.attr('data-captions-device');
            var captions = JSON.parse(data);
            var coords = captions.coords; // An array of port group objects 

            for (let i = 0; i < coords.length; i++) {
				var obj = coords[i];
				var text = obj.text ;
				
				if(text == 0){
					continue;	
				}else{
					
					var top = obj.top - 10;
					var left = obj.left - 10;
					var id = obj.ID; 
					var flow = obj.Orientation ;
					var devices = Object.values(obj.Devices) ;
					var set = "" ;
					
					for(let n=0; n < devices.length; n++){
						let device = devices[n];
						let deviceID = device.ID;
						let name = device.Computer_Name;
						let noncap = device.Noncap;
						let model = device.Model ;
						let order = device.Flex_Order
						// In this view, I'm using a series of nested flexboxes instead of tables.
						// This allows positional data about the devices to be separated from their ports.
						// I envision it like a tableau-builder, where you place different cards on your tableau,
						// and tokens on your cards which modify them. In this case, the tableau is the port group,
						// the cards are the devices, and the tokens are the accessories. 
						// It's more about stacking up than lateral connection, I think. I hope this makes sense
						let card = "<span class='card' data-id'" + deviceID + "' style='order: " + order + "'>" ;
							card += "<dl class='device '><dt>Name: </dt><dd>" + name + "</dd><dt>Model: </dt><dd>" + model + "</dd>" ;
							card += "<dt>Noncap: </dt><dd>" + noncap + "</dd></dl>"
							
						if(device.hasOwnProperty(accessories)){
							card += "<button>Show Accessories" + ICON + "</button>" ; 
							let accessories = Object.values(device.accessories);
							for(a = 0; a < accessories.length; a++ ){
								let accessoryID = accessory.ID;
								let type = accessory.Device_Type;
								let aModel = accessory.Model;
								let aOrder = accessory.Flex_Order;
								if(accessory.Serial_Number != '' && accessory.Serial_Number != null){
									let sn = accessory.Serial_Number;
								}else{
									let sn = "Not Found";
								};
								
								let token = "<span class='token' data-id='" + accessoryID + "' style='order: " + aOrder + "'>";
									token += "<dl class='accessory '><dt>Type: </dt><dd>" + type + "</dd><dt>Model: </dt><dd>" + aModel + "</dd>" ;
									token += "<dt>Serial Number: </dt><dd>" + sn + "</dd></dl></span>" ;
								card += token ;	
							}
						}else{							
							card += "</span>" ;
						}
						set += card ;
					};
					
				};
				
				$('<span class="marker"/>').css({
					"top": top,
					"left": left,
					"flex-flow": flow
				}).html( text + "<span class='tableau' hidden>" + set + "</span>")
				.data("top", top).data("left", left).data('id', id)
				.appendTo(target);
				
				
			};				
		},
		
		
		showContent: function() {
			$('span.marker').hover(function() {
				 if(lookAtMe === 0 && searching ===0){
						   let $marker = $(this);
						   $marker.css({"z-index": 2});
						   let $tableau = $('span.tableau', $marker);
						   let $cards = $tableau.children(".card")
						   let dynamicWidth = 0;
						   $tableau.css({
							   "z-index": 2,
							   "display": "flex",
						   });
						   for (i = 0; i < $cards.length; i++){ 
							   let $card = $($cards[i]);
							   let cardWidth = $card.outerWidth();
							   dynamicWidth += cardWidth;
						   }
						   //console.log(dynamicWidth);
						   //$tableau.css({"max-width" : (dynamicWidth + 50)});
						   let $others = $marker.siblings('span.marker') ;
						   $others.css({"z-index" : 0 });
					   }else{
						   $('span.marker').stop(); 

					   };
					  
				   },
				   //hover out
				   function() {
					   if(lookAtMe === 0){
							let $marker = $(this);
							let $tableau = $('span.tableau', $marker);
							setTimeout(function() {
								if ($marker.is(':hover') !== true) {
									$tableau.css("display", "none");
								}
							}, 200);
						//console.log('out');
					   };
				   });
		},
		
		// This functionality is missing the search component
		focusContent: function(){
			$('span.marker').on('click', function() {
				if(searching === 0){
						lookAtMe = 1 ;
						let $marker = $(this).closest('span.marker') ;
							$marker.css({"background": "#4f2683" ,"z-index" : 3 });
						let $tableau = $marker.children('span.tableau');
							$tableau.css({
								"z-index" : 3, 
								"display" : "flex"
							});
						let $others = $marker.siblings('span.marker');
							$others.css({"background" : "#c3c5f2", "z-index" : 0 });
							$others.children().hide();
							
					
				}// Below is the search integration code from the port-oriented view
				//  It will need to be adjusted for this view.
				/*else if(searching === 1){
							if($('table.details:visible')){
								$('table.details').hide();
							};
							
							if($('span.caption:visible')){
								$('span.caption').hide();
							};
						
							let $marker = $(this).closest('span.marker') ;
							$marker.css({"background": "#4f2683" ,"z-index" : 3 });
							let $others = $marker.siblings('span.marker');
							$others.css({"background" : "#c3c5f2", "z-index" : 0 });
							let $details = $marker.children('table.details');
								$details.show();
								
						}*/;
				return(lookAtMe);						
			});
		},
		
		blurContent: function(){
			$('img.map').click(function() {
				if(searching === 0){
					lookAtMe = 0;
					$('span.tableau:visible').hide();
					$('span.marker').children().hide();
					$('span.marker').css({"background": "#4f2683", "border": "none"});	
				};
			});
		},
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
		}
	},
	
	init: function(){
		this.fn.addMarkers();
		this.fn.showContent();
		this.fn.focusContent();
		this.fn.blurContent();
		this.fn.adjustMarkers();
	}
}

$('#image-wrapper').ready(function() {
    Markers.init();
});

var Utility = {

	fn: {
		changeFloor: function(){
			$('#floorSelect').change( function(){
				let url = $(this).val();
				console.log(url);
				window.location.assign(url);
			});
		},
		runReports: function(){
			$('#reports').click( function(){
				let url = $(this).attr("data-url");
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
		
		// THIS FUNCTION IS UNFINISHED
		changeView: function(){
			let currentView = $('#changeView').data('view');
			//if(currentView === "port"){
				
			//}
		}
	},
	
	init: function() {
		//this.fn.adjustMarkers();
		this.fn.changeFloor();
		this.fn.runReports();
		this.fn.changeLibrary();
		this.fn.changeView();
	}
	
};		


window.onload = function() {
    Utility.init();

};