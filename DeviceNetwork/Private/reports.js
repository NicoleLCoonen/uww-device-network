
var Handler = {
	
	fn: {
		displayReport: function(){
			var report = $("#display").attr("data-report-type");
			 if(report === "undefined"){
				 report = "";
			 }else if(report !== ""){
				 console.log(report);
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
			$(".overview:visible").css({
				"display":"flex",
				"flex-flow": "row wrap",
				"height": "150px"
			});
			
			
		
			
			let shown = $('#display').children(':visible');
				
			if(shown.length !== 0){
				let formH = $('#reportForm').outerHeight();
					$('#display').css("top", formH);
				/*let headingH = $('.heading').outerHeight();
					
					$('.overview').css("top", headingH);
				let overviewH = $('.overview').outerHeight();
					overviewH = outerHeight + headingH;
					$('.breakdown').css("top", overviewH);*/
					
			}
		}
	},
		
	init: function() {
		this.fn.displayReport();
		this.fn.changeFloor();
		this.fn.styleToGo();
	}
	
};

$(function() {
    Handler.init();

});
