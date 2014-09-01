$( document ).ready(function() 
{
	$("#go").click(function() 
	{
		var ticket=$("#ticket").val();
		//If entered value is int, assume it to be a ticket number
		if(Math.floor(ticket) == ticket && $.isNumeric(ticket))
		{
			var url = "http://trac.worldstores.co.uk/ticket/" + ticket;
		}
		// Else assume, searching track
		else
		{
			var url = "http://trac.worldstores.co.uk/search?q=" + ticket;
		}
		
		var win = window.open(url, '_blank');
		win.focus();
	});


	$(".repository_dropdown").click(function() 
	{
		var table_id = $(this).attr("table_id");
		$("#"+table_id).fadeToggle();
	});

	$("#ticket").keypress(function(event)
	{
	    if(event.keyCode == 13)
	    {
	        event.preventDefault();
	        $("#go").click();
	    }
	});

	if (window.addEventListener) 
	{
		window.addEventListener("keydown", function(e) 
		{
			//key 68 = 'D' - Select ticket input field
			if (e.keyCode == "68" && e.ctrlKey == true)
			{
				$("#ticket").select();
			}
			//key 78 = 'N' - Create new ticket
			if (e.keyCode == "78" && e.ctrlKey == true)
			{
				var url = "http://trac.worldstores.co.uk/newticket";
				var win = window.open(url, '_blank');
       			win.focus();
			}
		}, true);
	}
});