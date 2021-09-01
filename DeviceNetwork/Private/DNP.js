const ICON = '<img class="icon" src="../Private/plugIcon.png" alt="" width="16" height="16">' ; 
var lookAtMe = 0 ;
var searching = 0 ;


var Markers = {
    fn: {
        addMarkers: function() {
            var target = $('#image-wrapper');
			var data = target.attr('data-captions');
            var captions = JSON.parse(data);
            var coords = captions.coords; // An array of port group objects 

            for (let i = 0; i < coords.length; i++) {
                var obj = coords[i];
				var top = obj.top - 10;
				var left = obj.left - 10;
				var id = obj.ID
				var text = obj.text ;
                var ports = text.ports; 
				var devices = text.devices;
				var portGroup = obj.Ports ; 
				var portArr = Object.values(portGroup); 
				
				if(ports == 0){continue;};
			
				const PORT_HEAD = '<table class="details"><th>ID</th><th>Port</th><th>Status</th><th>In Use</th><th></th>';
				const CLOSE_TABLE = '</table>' ;
				var portTable = '' ;
				var deviceTable = '' ;
				var appendDevices = '' ;
				
				for(let n = 0; n < portArr.length; n++) {
					let myPort = portArr[n];
					let portInfo = Object.values(myPort);
					let status = myPort.Port_Status;
					let portID = myPort.ID;
					let insert = '';
					if(myPort.Damaged === 1) {
							status += "*" ;
						};
					
					let tableContent = '<tr><td>'+ portID + '</td><td>' + myPort.Port_Name + '</td><td>' + status + '</td>' ;
					
					if(portInfo.length === 5){
						tableContent += '<td><button class="icon" type="button">' + ICON + "</button</td>" ;
						let myDevice = myPort.Device;
						let deviceInfo = Object.values(myDevice);
						if(myDevice.hasOwnProperty('Accessories')){
							let accessories = myDevice.Accessories;
							for(p = 0; p < accessories.length; p++){
								let acc = accessories[p];
								/*add empty table cells just to keep the display tidy.
								 I'm hiding the first column of data in all tables because that's
								 where I'm storing ID numbers for now.*/
								insert += '<tr><td>' + acc.ID + '</td><td>' + acc.Name + '</td><td></td><td></td></tr>';
							};
						};
						if(deviceInfo.length === 5){
							tableContent += '<td><table class="device"><th>ID</th><th>Name</th><th>Model</th><th>Noncap</th>' ;						
							tableContent += '<tr><td>' + myDevice.ID + '</td><td>' + myDevice.Computer_Name + "</td><td>" + myDevice.Model + "</td>" ;
							tableContent += "<td>" + myDevice.Noncap + "</td></tr>" ;
							
							if( insert !== 'undefined'){ tableContent += insert; };
							tableContent += "</table></td>";
							
						} else {
							tableContent += '<td><table class="device"><th>ID</th><th>Name</th><th>Model</th><th>Noncap</th>' ;
							tableContent += '<th>Vendor</th><th>Vendor ID</th><th></th><th></th>';
							tableContent += '<tr><td>' + myDevice.ID + '</td><td>' + myDevice.Device_Name + "</td><td>" + myDevice.Model + "</td>" ;
							tableContent += "<td>" + myDevice.Noncap + "</td><td>" + myDevice.Vendor +"</td><td>" + myDevice.Vendor_Name + "</td>" ;
							tableContent += "<td>" + myDevice.Vendor_Phone + "</td><td>" + myDevice.Vendor_Website +"</td></tr></table></td>";
						};
					} else {
						tableContent += "<td></td><td></td>" ;
					};
					
					tableContent += "</tr>" ;
					
					portTable += tableContent ;
				}
			

               $('<span class="marker"/>').css({
                    top: top,
                    left: left
                }).html('<span class="caption"> Ports: ' + ports +  '; Devices: ' + devices + 
					'</br><button class="smallButton" type="button" name="Details"><small>Details</small></button></span>'
					+ PORT_HEAD + portTable + CLOSE_TABLE).data("top", top).data("left", left).data('id', id).
                appendTo(target);
				
				
				$("table.details, table.device").find('th:first-child, td:first-child').hide();
				$("table.device").find('th:nth-child(6)').hide();
				$("table.device").find('th:nth-child(7)').hide();
				$("table.device").find('th:nth-child(8)').hide();
				$("table.device").find('td:first-child').hide();
				$("table.device").find('td:nth-child(6)').hide();
				$("table.device").find('td:nth-child(7)').hide();
				$("table.device").find('td:nth-child(8)').hide();
				$("#necromancer").prop("checked", false);
			

            }
        },
		
		 viewContent: function(){
			   $('span.marker').hover(function() {
				   if(lookAtMe === 0 && searching ===0){
					   let $marker = $(this);
					   $marker.css({"z-index": 2});
					   let $caption = $('span.caption', $marker);
						$caption.css({"z-index": 2}).slideToggle(200);
						let $others = $marker.siblings('span.marker') ;
						$others.css({"z-index" : 0 });
						
				   }else{
					   $('span.marker').stop(); 
				   };
				  
			   });
		},
		
		focusContent: function(){
			$('span.marker').click(function() {
				if(searching === 0){
						lookAtMe = 1 ;
						let $marker = $(this).closest('span.marker') ;
							$marker.css({"background": "#4f2683" ,"z-index" : 3 });
						let $caption = $marker.children('span.caption');
							$caption.css({"z-index" : 3 }).show();
						let $others = $marker.siblings('span.marker');
							$others.css({"background" : "#c3c5f2", "z-index" : 0 });
							$others.children().hide();
					
			}else if(searching === 1){
							if($('table.details:visible')){
								$('table.details').hide();
							};
							
							if($('span.caption:visible')){
								$('span.caption').hide();
							};
						
							let $marker = $(this).closest('span.marker') ;
							let $details = $marker.children('table.details');
								$details.show();
								
						};
				return(lookAtMe);						
			});
		},
		
		blurContent: function(){
			$('img.map').click(function() {
				if(searching === 0){
					lookAtMe = 0;
					$('span.caption:visible').hide();
					$('span.marker').children().hide();
					$('span.marker').css({"background": "#4f2683", "border": "none"});	
				};
			});
		},
		
		showDetails: function(){
		$('button.smallButton').click(function() {
				let $marker = $(this).closest('span.marker') ;
				let $caption = $marker.children('span.caption');
				let $details = $marker.children('table.details');
					$caption.hide(200);
					$details.css({"z-index": 5});
					$details.slideToggle(200) ;
			});
		},
		
		showDevice: function(){
			
			$('button.icon').click(function() {
					let $currentCell = $(this).parent("td");
					let $tableCell = $currentCell.next("td");
					let $table = $tableCell.children('table.device');
					$tableCell.toggle;
					$table.toggle();		
			});
			
		},
		
		performQuery: function(){
			$('#userInput').keyup(function(){
			$userInput = $('#userInput').val();	
				if($userInput !== ""){
					searching = 1 ;
					let raiseDead = $("#necromancer").prop("checked");
					let match = ":contains(" + $userInput + ")" ;
					$("span.marker").css({"border": "none","background" : " #d7adeb"});
					let $genResult = $("span.marker" + match);
					$genResult.css({"border": "2px solid LightCoral", "background": "#4f2683"});
					$("tr").css({"border-bottom": "2px dotted #3d1452"});
					let $result = $("td" + match);
					let $resultInside = $result.parents('table.device');
					
					if($resultInside.length > 0 ){
						let $parentCell = $resultInside.parents('td');
						let $buttonCell = $parentCell.prev('td');
						$buttonCell.children('button.icon').css({"border": "2px solid LightCoral"});
						$buttonCell.parent().css({"border": "2px solid LightCoral"});
					};
					
						if($result.length <= 2){
							$result.closest('table').show();
							$result.parents('tr').css({"border": "2px solid LightCoral"});
						};
						
					if(raiseDead === true){
						let grave = $result.parents("table.graveyard");
						if(grave.length === 1){
							$('.graveyard').show();
							let $zombie = $result.closest('tr');
								$zombie.show();
							let $corpses = $zombie.nextAll('tr');
								$corpses.hide();
							let $bodies = $zombie.prevUntil('tr:has(th)');
								$bodies.hide();
							
						};
					};
						
				} else {
					searching = 0 ;	
					$("span.marker").css({"border": "none", "background": "#4f2683"});
					
				};
				return(searching) ;
			});
		},
		
		reset: function() {
			$('#reset').click(function() {
				searching = 0;
				lookAtMe = 0
				$("#necromancer").prop("checked", false);
				$('#userInput').val('');
				$('span.marker').css({"background": "#4f2683", "border": "none"});
				$('.graveyard').hide();
				$('table:visible').hide();
				$('tr').css({"border-bottom": "2px dotted #3d1452"});
				$('button.icon').css({"border": "2px solid #3d1452"}) ;
				return(searching) ;
			});
			
		},
		
		changeFloor: function(){
			$('#floorSelect').change( function(){
				let url = $(this).val();
				window.location.assign(url);
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
		},
		
		changeLibrary: function(){
			$('#changeLibrary').click(function(){
				let url = $(this).attr('data-url');
				window.location.assign(url);
			});
		},
		
		
				
	},
			

    init: function() {
        this.fn.addMarkers();
        this.fn.viewContent();
		this.fn.focusContent();
		this.fn.blurContent();
		this.fn.showDetails();
		this.fn.showDevice();
		this.fn.performQuery();
		this.fn.reset();
		this.fn.changeFloor();
		this.fn.changeLibrary();
		this.fn.adjustMarkers();
    }
};



$(function() {
    Markers.init();

});
