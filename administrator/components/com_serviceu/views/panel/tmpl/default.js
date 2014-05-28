jQuery(document).ready(function() {
	jQuery('#update_serviceu').click(function() {
		jQuery('#update_serviceu_status').html('Loading events from ServiceU API...');
	
		jQuery.getJSON('../index.php',{
			option: "com_serviceu",
			task: "updateEvents",
			format: "json"
		},
		function(json){
			if (json.process === "complete") {
				jQuery('#update_serviceu_status').html('Fetch completed in ' + Math.round(json.time) + ' second(s).');
				
				setTimeout(function  () {
					window.location = "index.php?option=com_serviceu";
				}, 3000);

			} else {
				jQuery('#update_serviceu_status').html('Fetch failed');
			}
		});
	});
});
