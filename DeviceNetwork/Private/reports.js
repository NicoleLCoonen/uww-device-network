
var Handler = {
	
	fn: {
		displayReport: function(){
			let report = $("#display").attr("data-report-type");
			 if(typeof(report) === "undefined"){
				 report = "";
			 }else if(report !== ""){
				let showReport = "#" + report ;
				$("#display").show();
				$(showReport).show();
			 };
			
		},
		
		changeFloor: function(){
			$('#floorSelect').change( function(){
				let url = $(this).val();
				window.location.assign(url);
			});
		},
		
		changeLibrary: function(){
			$('#changeLibrary').click(function(){
				let url = $('#changeLibrary').attr('data-url');
				//console.log(url);
				window.location.assign(url);
			});
		},
		
		styleToGo: function(){
		
			let shown = $('#display').children(':visible');
				
			if(shown.length !== 0){
				let formH = $('#reportForm').outerHeight();
				$('#display').css({"top": formH});
			};
			
			let	$table = $(".floor > table");
			if($table.length > 0){
				$(".overview").css({"height" : "5%"});
				
				$(".breakdown").css({
					"display" : "flex",
					"flex-flow" : "row wrap",
					"justify-content" : "space-between"
				});
					
				$(".floor").css({"flex": "1 1 16.5%", "height" : "100%"});
				$("th:first-child,th:nth-child(3),th:nth-child(4),th:last-child").hide();
				$("td:first-child,td:nth-child(3),td:nth-child(4),td:last-child").hide();
				
			};
			
			let graveyard = $('.graveyard table:visible');
			
			if(graveyard.length > 0){
				$(".overview").css('display' , 'block');
				$('.graveyard th:last-child').prev('th').hide();
				$('.graveyard td:last-child').prev('td').hide();
				$('.graveyard th:last-child').show();
				let $recall = $('.graveyard td:last-child:contains(1)');
				$recall.replaceWith("<td><button type='button' class='recall'>Recall</button><button type='button' class='edit'>Edit</button></td>");
			};
		},
		
		orderFlexItems: function(){
			let generalReport = $('#general:visible');
			if(generalReport.length > 0){
				$(".overview").children("h3").remove(); 
				let totals =  $(".generic:contains(Total)")
				let notTotal = $('.overview > .generic:not(:contains(Total))');
				for(let x = 0; x < totals.length; x++){
					$(totals).css("order", 1);
					let group = $(totals[x]).prevAll(".generic");
					let section = [totals[x]];
					for(let y = 0; y < group.length; y++){
						$(group[y]).css("order", y+2);
						section[y+1] = group[y];
					}
					$(section).wrapAll("<div class='overview'></div>")
				}
				
			}
		},
		
		recallDevice: function(){
			$('.recall').on('click',function(){
				let formHidden = $('#recallForm:hidden');
				let formVisible = $('#recallForm:visible');
				
				if(formHidden.length === 1){
					
					let $row = $(this).closest('tr');
					let id = $row.children('td:first-child').text();
					let name = $row.children('td:nth-child(2)').text();
					let noncap = $row.children('td:nth-child(3)').text();
					let model = $row.children('td:nth-child(4)').text();
					
					$('#deviceID').val(id);
					$('#deviceName').val(name);
					$('#deviceNC').val(noncap);
					$('#deviceModel').val(model);
					$('#recallForm').val('true').show();
					
					//shows floor selection by branch
					$('.branch').click(function(){
						$(this).css("background", "#4f2683");
						let $branch = $(this).attr('id');
						$('#selectByBranch').show();
						if($branch === 'Andersen'){
							$('#AportFloor').show();
							$('#Lenox').css("background", "#c3c5f2")
							$('#LportFloor:visible').hide();
						}else if($branch === 'Lenox'){
							$('#LportFloor').show();
							$('#Andersen').css("background", "#c3c5f2")
							$('#AportFloor:visible').hide();
						};
						
					});
					
					// generates a list of ports as radio inputs labeled with their name
					$('#AportFloor, #LportFloor').change(function(){
						// clear previous list
						let oldList = $('#portSelection').find(":radio , label");
						oldList.remove();
						let dataCap = $(this).val();
						let portList = JSON.parse(dataCap);
						let ports = portList.Ports;
						let radioSelect ='';
						
						for(i=0; i < ports.length; i++){
							let portID = ports[i].ID;
							let portName = ports[i].Port_Name;
							
							radioSelect += "<input type='radio' name='newPort' id='" + portID +"' value='" + portID +"' >";
							radioSelect +=	"<label for='" + portID + "'>" + portName + "</label></br>";
						};
						
						// inserts the list and makes the radio input a required field
						$('#portSelection p').after(radioSelect);
						$(':radio:first-child[name=newPort]').attr("required", true);
						$('#portSelection').show();
						
					});
				
				
					let officeCheck = name.indexOf('-O');
						console.log(officeCheck);
						if(officeCheck !== -1){
							let staffCap = $('#officeSelection').attr('data-caption');
							let staffList = $.parseJSON(staffCap);
							let staff = staffList.Staff;
							let staffSelect ='';
							
							for(i=0; i < staff.length; i++){
								let staffID = staff[i].ID;
								let staffName = staff[i].Name;
								
								staffSelect += "<input type='radio' name='newUser' id='" + staffID +"' value='" + staffID +">";
								staffSelect +=	"<label for='" + staffID + "'>" + staffName + "</label></br>";
							};
						
							$('#officeSelection p').after(staffSelect);
							$(':radio:first-child[name=newUser]').attr("required", true);
							$('#officeSelection').show();
						}else if(officeCheck === -1){
							$('#officeSelection').hide();
						};
						
				}else if(formVisible.length === 1){
				
					$('.recall').off('click');
				};
			});
		},
		closeForm: function() {
			$("#closeForm").click(function(){
				$('#deviceID').val('');
				$('#deviceName').val('');
				$('#recallForm').val('false').hide();	
			});
		},				
		
		print: function(){
			
			$('#print').click(function(){
				let portTables = $('#available:visible,#broken:visible');
				if(portTables.length > 0){
					$('.floor').removeAttr('style');
					$('.floor').css({
						"flex-flow": "none"
					});
					$(".floor h4").css({"width" : "100%"});
				};
				$('#display').find(':visible').addClass('print');
				$('header, form, #print').addClass('noprint');
				
				window.print();
				$(".print").removeClass("print");
				$(".noprint").removeClass("noPrint");
				Handler.fn.styleToGo();
			});
		}
	},
		
	init: function() {
		this.fn.displayReport();
		this.fn.changeFloor();
		this.fn.changeLibrary();
		this.fn.styleToGo();
		this.fn.orderFlexItems();
		this.fn.recallDevice();
		this.fn.closeForm();
		this.fn.print();
	}
	
};

$(function() {
    Handler.init();

});
