var Handler = {
	
	fn: {
		displayReport: function(){
			
			$('.topic button').click(function(){
				let topic = $(this).attr("data-topic");
				let showTopic= "#" + topic ;
				$("#display").show();
				$(showTopic).show();
				$(showTopic).siblings().hide();
				
			});
		},
		

	},
	
	init: function() {
		this.fn.displayReport();
	}
	
};

$(function() {
    Handler.init();

});
