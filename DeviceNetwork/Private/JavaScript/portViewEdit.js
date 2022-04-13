const ICON = '<img class="icon" src="../Private/Styling/plugIcon.png" alt="" width="16" height="16">' ; 
var lookAtMe = 0 ;
var searching = 0 ;

var Markers = {
    fn: {
       addMarkers: function() {
            var target = $('#image-wrapper');
			var data = target.attr('data-captions-port');
            var captions = JSON.parse(data);
            var coords = captions.coords; // An array of port group objects 
			
            for (i = 0; i < coords.length; i++) {
                var obj = coords[i];
				var top = obj.top - 10;
				var left = obj.left - 10;
				var id = obj.ID;
				var text = obj.text ;
                var ports = text.ports; 
				var devices = text.devices;
				var portGroup = obj.Ports ; 
				var portArr = Object.values(portGroup); 
				
				if(ports == 0){continue;}
				
				// This view is table-based. Each row in the table represents a point of connection,
				// either a port that a device can be connected to or a device that can connect to accessories.
				// I visualize it as one of those molecular model kits used in high school chem. 
				// First we create a table for the port group:
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
						}
					// Each port gets represented as a row in the table.
					let tableContent = '<tr><td>'+ portID + '</td><td>' + myPort.Port_Name + '</td><td>' + status + '</td>' ;
					
					// If there is a device attached to the port, it gets its own table and a button to indicate & show the connection.
					if(portInfo.length === 5){
						tableContent += '<td><button class="icon" type="button">' + ICON + "</button</td>" ;
						let myDevice = myPort.Device;
						let deviceInfo = Object.values(myDevice);
						// Checks for accessories
						if(myDevice.hasOwnProperty('Accessories')){
							let accessories = myDevice.Accessories;
							for(p = 0; p < accessories.length; p++){
								let acc = accessories[p];
								/* We'll have to shove accessories in later on.
								 We need to add empty table cells just to keep the display tidy.
								 I'm hiding the first column of data in all tables because that's
								 where I'm storing ID numbers for now.*/
								insert += '<tr class="accessory"><td>' + acc.ID + '</td><td>' + acc.Name + '</td><td></td><td></td></tr>';
							}
						}
						//Differentiates between office and public equipment
						if(deviceInfo.length === 5){
							tableContent += '<td><table class="device ';
							// give an additional class based on the type of device
							if(myDevice.Computer_Name.indexOf("-O") !== -1){
								tableContent += 'office"';
							} else if(myDevice.Computer_Name.indexOf("-O") === -1){
								tableContent += 'public"';
							}
							// This builds a computer table and shoves accessories in at the end.
							tableContent += '"><th>ID</th><th>Name</th><th>Model</th><th>Noncap</th>' ;						
							tableContent += '<tr><td>' + myDevice.ID + '</td><td>' + myDevice.Computer_Name + "</td><td>" + myDevice.Model + "</td>" ;
							tableContent += "<td>" + myDevice.Noncap + "</td></tr>" ;
							
							if( insert !== 'undefined'){ tableContent += insert; }
							
							tableContent += "</table></td>";
							
						} else {
							// This builds a table for printers, scanners, and other third-party devices.
							tableContent += '<td><table class="device other"><th>ID</th><th>Name</th><th>Model</th><th>Noncap</th>' ;
							tableContent += '<th>Vendor</th><th>Vendor ID</th><th></th><th></th>';
							tableContent += '<tr><td>' + myDevice.ID + '</td><td>' + myDevice.Device_Name + "</td><td>" + myDevice.Model + "</td>" ;
							tableContent += "<td>" + myDevice.Noncap + "</td><td>" + myDevice.Vendor +"</td><td>" + myDevice.Vendor_Name + "</td>" ;
							tableContent += "<td>" + myDevice.Vendor_Phone + "</td><td>" + myDevice.Vendor_Website +"</td></tr></table></td>";
						}
					} else {
						tableContent += "<td></td><td></td>" ;
					}
					
					tableContent += "</tr>" ;
					
					// Then we shove the device table inside the port row and repeat for each port.
					portTable += tableContent ;
				}
			
				// And finally we're ready to bring it all together in the port group marker.
               $('<span class="marker"/>').css({
                    top: top,
                    left: left
                }).html('<span class="caption">Ports: ' + ports +  '; Devices: ' + devices + "</span>"
					+ PORT_HEAD + portTable + CLOSE_TABLE).data("top", top).data("left", left).data('id', id).
                appendTo(target);
				
				// Hides a bunch of nonsense to keep things tidy.
				$("table.details, table.device").find('th:first-child, td:first-child').hide();
				$("table.device").find('th:nth-child(6)').hide();
				$("table.device").find('th:nth-child(7)').hide();
				$("table.device").find('th:nth-child(8)').hide();
				$("table.device").find('td:first-child').hide();
				$("table.device").find('td:nth-child(6)').hide();
				$("table.device").find('td:nth-child(7)').hide();
				$("table.device").find('td:nth-child(8)').hide();
				$("#includeWithdrawn").prop("checked", false);
			

            }
        },
		
		viewContent: function(){
			   $('span.marker').hover(function() {
				   if(lookAtMe === 0 && searching ===0){
					   let $marker = $(this);
					   $marker.css({"z-index": 2});
					   let $caption = $('span.caption', $marker);
					   $caption.css({"z-index": 2}).slideDown(200);
					   let $others = $marker.siblings('span.marker') ;
					   $others.css({"z-index" : 0 });
				   }else{
					   $('span.marker').stop(); 
				   };
				  
			   },
			   //hover out : Big thanks to Branden for writing this codeblock
			   function() {
					let $marker = $(this);
					let $caption = $('span.caption', $marker);
					setTimeout(function() {
						if ($marker.is(':hover') !== true){
							$caption.slideUp(50);
						}
					}, 200);
					
			   });
		},
		// Adjusts styling of elements when a marker gets focus
		// Shows port/device tables
		focusContent: function(){
			$('span.marker').on('click', function() {
				if(searching === 0){
						lookAtMe = 1 ;
						let $marker = $(this).closest('span.marker') ;
							$marker.css({"background": "#4f2683" ,"z-index" : 3 });
						let $details = $marker.children('table.details');
							$details.css({"z-index": 5});
							$details.show() ;
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
							$marker.css({"background": "#4f2683" ,"z-index" : 3 });
							let $others = $marker.siblings('span.marker');
							$others.css({"background" : "#c3c5f2", "z-index" : 0 });
							let $details = $marker.children('table.details');
								$details.show();
								
				};
				return(lookAtMe);						
			});
		},
		// Resets stying when content gets blurred
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
		
		showDevice: function(){
			
			$('button.icon').click(function() {
					let $currentCell = $(this).parent("td");
					console.log(this);
					let $tableCell = $currentCell.next("td");
					let $table = $tableCell.children('table.device');
					$tableCell.toggle;
					$table.toggle();		
			});
			
		},
		
		//toggleDeviceView: function(){
			
		//},
		
		performQuery: function(){
			$('#userInput').keyup(function(){
			$userInput = $('#userInput').val();	
				if($userInput !== ""){
					searching = 1 ;
					let raiseDead = $("#includeWithdrawn:checked");
					
					let match = ":contains(" + $userInput + ")" ;
					let notMatch = ":not(:contains("+ $userInput + "))";
					$("span.marker").css({"border": "none","background" : " #d7adeb"});
					let $genResult = $("span.marker" + match);
					$genResult.css({"border": "2px solid LightCoral", "background": "#4f2683", "z-index" : 3});
					$("tr").css({"border-bottom": "2px dotted #3d1452"});
					let $result = $("td" + match);
					let $resultInside = $result.parents('table.device');
					
					let $others = $("span.marker" + notMatch);
					$others.css({"background" : "#c3c5f2", "z-index" : 0 });
					
					
					if($resultInside.length > 0 ){
						let $parentCell = $resultInside.parents('td');
						let $buttonCell = $parentCell.prev('td');
						$buttonCell.children('button.icon').css({"border": "2px solid LightCoral"});
						$buttonCell.parent().css({"border": "2px solid LightCoral"});
					}
					
						if($result.length <= 2){
							$result.closest('table').show();
							$result.parents('tr').css({"border": "2px solid LightCoral"});
						}else {
							$("span.marker").children(":visible").hide();
						}
						
					if(raiseDead.length > 0){
						let grave = $result.parents("div.graveyard");
						if(grave.length >= 1){
							$('.graveyard').show();
							let $zombie = $result.closest('tr');
								$zombie.siblings(':not(:has(th))').hide();
								$zombie.show();
						}
					}
						
				} else {
					searching = 0 ;	
					$("span.marker").css({"border": "none", "background": "#4f2683"});
					
				}
				return(searching) ;
			});
		},
		
		closeGraveyard: function() {
				$("#closeGraveyard").click(function(){
					$(".graveyard").hide();
				});
		},
		
		reset: function() {
			$('#reset').click(function() {
				searching = 0;
				lookAtMe = 0;
				$("#includeWithdrawn").prop("checked", false);
				$('#userInput').val('');
				$('span.marker').css({"background": "#4f2683", "border": "none"});
				$('.graveyard').hide();
				$('table:visible').hide();
				$('tr').css({"border-bottom": "2px dotted #3d1452"});
				$('button.icon').css({"border": "2px solid #3d1452"});
				return(searching) ;
			});
			
		},
		
		// Honestly, this could probably be written more clearly
		// I wrote this when I was just learning JQuery traversing and was too lazy to give each field an ID. 
		displayForms: function() {
			$("table.details td").dblclick(function() {
					let w = $("#image-wrapper").css("width");
					let scrollPos = $('#updateForms').scrollTop();
					//console.log(scrollPos);
					w = w.slice(0, -2);
					w = parseFloat(w);
					w = (w + 20) + "px";
					$("#updateForms").css({"left": w});
					$("#updateForms").show();
					let initialTop = $("#updateForms").css("top");
					$('input[type="number"], #phpDelete').hide();
					$('input[type="tel"]').val('');
					$(':checkbox,:radio').prop('checked', false);
					$('input[disabled]').attr('disabled', false);
					$(':text').val('');
					// get data from port table row
					let $row = $(this).closest("tr");
					let $portID = $row.children("td").html();
					let $portName = $row.children("td").next("td").html();
					let $status = $row.children("td:nth-child(3)").text();
					let $connection = $row.find("table.device");
					 
					// this populates the form with the port data
					$("#portID").val($portID);
					$("#portName").val($portName);
					
					if($connection.length === 0){	
						// Clear any previous data from the form
						$("#deviceID,#model,#deviceName,#nonCap").val('');	
						$('#delete, #move').attr("disabled", true);					
						$('#new').attr("disabled", false);
						
						if ($("#connectionError:visible")) {
								$("#connectionError").hide();	
						}
						
						if($status.length === 2) {
							$("#On").prop("checked", true);
							$("#new").attr("disabled", false);
							
						} else if($status.length === 3) {
							$("#Off").prop("checked", true);
							$("#new").attr("disabled", true);
						}  else if($status.length > 3){
							$("#Off").prop("checked", true);
							$("#broken").prop("checked", true);
						}
					} else if($connection.length == 1){
						/* If the port has a connected device, this code
						   grabs that data */
						$connection.show();
						$('#delete, #move').attr("disabled", false);
						$('#new').attr("disabled", true);
						let $device = $row.find("table.device").find("tr:nth-child(2)");
						let $accessories = $device.nextAll();
						let $deviceID = $device.children("td").html();
						let $deviceName = $device.children("td:nth-child(2)").text();
						let $model = $device.children("td:nth-child(3)").text();
						let $nonCap = $device.children("td:nth-child(4)").text();
						$("#deviceID").val($deviceID);
						$("#deviceName").val($deviceName);
						$("#model").val($model);
						$("#nonCap").val($nonCap);
						$("#new").attr("disabled", true);
						let inputs = '';
						
						// Looks for accessories in the table and generates/populates inputs accordingly
						for(n = 0; n < $accessories.length; n++ ){
							 a = $accessories[n]; 
							let aID = $(a).children('td:first-child').text();
							let aName = $(a).children('td:nth-child(2)').text();
							inputs += "<span><input type='text' name='accessory"+ aID + "Name'";
							inputs += "value='" + aName + "' readonly></input>";
							inputs += "<input type='checkbox' id='accessory" + aID + "'";
							inputs += "name='accessory" + aID + "'" + "value='true'>";
							inputs += "<label for='accessory" + aID + "'>Release</label>";
						}
						$('#accessories').html(inputs);
						/* Check for the type of device & populate
						   the form accordingly. Library-owned 
						   computers have 4 total columns,while 
						   3rd-party devices have 8.
						*/
						if($device.children("td").length > 4) {
							let $vendor = $device.children("td:nth-child(5)").text(); 
							let $vendorID = $device.children("td:nth-child(6)").text();
							let $vendorPhone = $device.children("td:nth-child(7)").text();
							let $vendorWeb = $device.children("td:nth-child(8)").text();
							
							$("#vendor").val($vendor);
							$("#vendorName").val($vendorID);
							$("#vendorContact").val($vendorPhone);
							
							$("#vendor").after('<a href="https://www.' + $vendorWeb + '">'
								+ $vendorWeb +'</a>');
						}
						
						if($status.length === 2) {
							$("#On").prop("checked", true);
							
							if ($("#connectionError:visible")) {
								$("#connectionError").hide();	
							}
							
						} else if($status.length === 3) {
							$("#Off").prop("checked", true);
							$("#connectionError").show();
						} else {
							$("#Off").prop("checked", true);
							$("#broken").prop("checked", true);
							$("#connectionError").show();
						}
					}
			});
		},
		
		
		closeForm: function() {
				$("#closeForm").click(function(){
					$("#updateForms").hide();
					$("div.error, #morgue").hide();
					$("input[checked=true]").attr("checked", false);
					$("input[required]").attr('required', false);
				});
		},
		
		moveDevice: function() {
			$("#move").click(function(){
				$('span.marker').css({"background": "#4f2683", "border": "none"});
				$("#portName, #portID").val('');
				$("#portName").attr('required', true);
				$("#On").prop("checked", false);
				$("#Off").prop("checked", false);
				$("#broken").prop("checked", false);
				$("#instructions").html("<p>Click a port to select it, then click " 
				 + "<strong>Update</strong> to move the device.</p>");
				 
				 // Again, could probably be more neatly written.
				 $("tr").on("click", function() {
					let $row = $(this).closest("tr");
					let $connection = $row.children("td").next("td").next("td").next("td").children("button");
					let $status = $row.children("td").next("td").next("td").html();
					let $portName = $row.children("td").next("td").html();
					let $portID = $row.children("td").html();
					
					$("#portName").val($portName);
					$("#portID").val($portID);
					
					if($connection.length === 0){
						if($("#portInUse:visible")) {
							$("#portInUse").hide();
						}
					
						if($status.length === 2) {
								$("#On").prop("checked", true);
								
								if ($("#connectionError:visible")) {
									$("#connectionError").hide();
									$("#updateButton").attr("disabled", false);									
								}
								
							} else if($status.length === 3) {
								$("#Off").prop("checked", true);
								$("#connectionError").show();
								$("#updateButton").attr("disabled", true);
							} else {
								$("#Off").prop("checked", true);
								$("#broken").prop("checked", true);
								$("#connectionError").show();
								$("#updateButton").attr("disabled", true);
							}
					} else if($connection.length === 1){
						$("#portInUse").show();
						$("#updateButton").attr("disabled", true);
						
						if($status.length === 2) {
							$("#On").prop("checked", true);
							
							if ($("#connectionError:visible")) {
									$("#connectionError").hide();
									$("#updateButton").attr("disabled", false);
							}
							
						} else if($status.length === 3) {
							$("#Off").prop("checked", true);
						}  else {
							$("#Off").prop("checked", true);
							$("#broken").prop("checked", true);
						}
					}
				 });
			});
		},
		
		portOn: function(){
			$("#On").click(function(){
				if($("#connectionError:visible")){
					$("#connectionError").hide();
				}
				if($("#broken").prop("checked", true)){
					$("#broken").prop("checked", false);
				}
				if($("#updateButton").attr("disabled", true) && $('#portInUse:hidden')){
					$("#updateButton").attr("disabled", false);
				}
				
				let deviceID = $("#deviceID").val();
	
				if(!deviceID || deviceID === '') {
					$("#new").attr("disabled", false);
				}
				
			});	
		},
		
		removeDevice: function(){
			$("#delete").click(function(){
			let userConfirm = window.confirm("Are you sure you want to remove this device?");
					if(userConfirm !== false){
					// The morgue collects extra info on the device before it's sent to the graveyard.
					$("#morgue").show();
					$("#dateRemoved").attr("required", true);
					$("#phpDelete").prop("checked", true);
					$("#move").attr("disabled", true);
					$('#delete').attr("disabled", true);
				}	
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
		
		addDevice: function(){
			$("#new").click(function(){
				$(":input[readonly]").removeAttr('readonly');
				$("#deviceID").val('');
				$("#deviceName").attr("required", true);
				$("#model").attr("required", true);
				$("#nonCap").attr("required", true);
			});
		},
		
		update: function(){
			$("#updateButton").click(function() {
				$("tr").off("click");
				$("input[required]").attr('required', false);
			
			});
		},
		
		redisplayPage: function(){
			let output = $('#output').text();
			if(output.length > 0 && output !== 'undefined'){
				let loc = window.location.href ;
				
				window.location.assign(loc);
			}
		}
		
		/* This code is for a lower-priority functionality to be
			added later in the project. It is incomplete.
		
		relocateMakers: function(){
			$('#editMarkers').click(function(){
				//$('span.marker').off('hover');
				//$('span.marker').off('click');
				$('span.marker').css('background', '#FFD700').draggable();
				$('#saveMarkers').show();
				
			});
		},
		
		saveMarkers: function(){
			$('#saveMarkers').click(function(){
				Markers.fn.redisplayPage();
			});
		},
		
		// We're not ready to work on admin stuff yet.
		openPage: function(){
			$('#reports, #admin').click(function(){
				let loc = $(this).attr("data-url");
				window.location.assign(loc);
				
			});
		}*/
			
	},
			

    init: function() {
        this.fn.addMarkers();
        this.fn.viewContent();
		this.fn.focusContent();
		this.fn.blurContent();
		this.fn.showDevice();
		this.fn.performQuery();
		this.fn.closeGraveyard();
		//this.fn.toggleDeviceView();
		this.fn.reset();
		this.fn.displayForms();
		this.fn.closeForm();
		this.fn.moveDevice();
		this.fn.portOn();
		this.fn.removeDevice();
		this.fn.addDevice();
		this.fn.update();
		this.fn.adjustMarkers();
		//this.fn.redisplayPage();
		//this.fn.openPage();
    }
};

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