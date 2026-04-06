$(document).ready(function(){  
	// code to get all records from table via select box
	$("#tendr").change(function() {
		var id = $(this).find(":selected").val();
		var dataString = 'tid=' + id;
		console.log("gdfg");
		$.ajax({
			url: 'getTenderHistory.php',
			dataType: "json",
			data: dataString,
			cache: false,
			success: function(tdata) {
				if (tdata) {
					$("#heading").show();		  
					$("#no_records").hide();	
					$("#catName").text(tdata.CategoryName);
					$("#Desc").text(tdata.CategoryName);
					$("#price").text(tdata.mtt_price);
					// $("#records").show();		 
				} else {
					$("#heading").hide();
					$("#records").hide();
					$("#no_records").show();
				}
			}
		});
	}) 
});