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
		
			changeLibrary: function(){
			$('#changeLibrary, #change-Library').click(function(){
				let url = $(this).attr('data-url');
				//console.log(url);
				window.location.assign(url);
			});
		},
	},
	
	init: function() {
		this.fn.displayReport();
		this.fn.changeLibrary();
	}
	
};

$(function() {
    Handler.init();

});
