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
			$('header option').click( function(){
				let url = $(this).val();
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
		},
		
		orderFlexItems: function(){
			 $(".overview").children("h3").remove();
			let first = $(".generic:contains(Total)").css({"order" : "1"});
			
		},
		
		print: function(){
			
			$('#print').click(function(){
				$('#display').find(':visible').addClass('print');
				
			});
		}
	},
		
	init: function() {
		this.fn.displayReport();
		this.fn.changeFloor();
		this.fn.styleToGo();
		this.fn.orderFlexItems();
		this.fn.print();
	}
	
};

$(function() {
    Handler.init();

});
