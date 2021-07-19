var Handler = {
	
	fn: {
		displayReport: function(){
			$("#display").children('div').hide();
			$('.topic button').click(function(){
				let topic = $(this).attr("data-topic");
				let showTopic= "#" + topic ;
				$("#display").show();
				let $show = $(showTopic).show();
				$show.siblings().hide;
			});
		},
		
		changeFloor: function(){
			$('#floorSelect').change( function(){
				let url = $(this).val();
				window.location.assign(url);
			});
		}
	},
	
	init: function() {
		this.fn.displayReport();
		this.fn.changeFloor();
	}
	
};

$(function() {
    Handler.init();

});
