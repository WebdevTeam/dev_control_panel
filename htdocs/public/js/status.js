// function update_branch_list(repo)
// {
// 	$.ajax(
// 	{
// 		url: '/update_branch_list.php',
// 		type: "POST",
// 		async: false,
// 		cache: false,
// 		data: {
// 			repo:repo
// 		},
// 		success: function (data) {

// 		}
// 	});
// }

// function get_branch_list(repo)
// {
// 	var branch_select = '';
// 	$.ajax(
// 	{
// 		url: '/get_branch_list.php',
// 		type: "POST",
// 		data: 
// 		{
// 			repo:repo
// 		},
// 		success: function (data) 
// 		{
// 			if(data.substr(0,7)=='<select')
// 			{
// 				$('.branch_input').replaceWith(data)
// 			}
// 			else
// 			{
// 				alert("No branches found. Please  refresh the branch list and try again. Or enter the exact name of the branch");
// 				$('.branch_input').replaceWith('<input type="text" class="branch_input" name="branch_input" id="branch_input" placeholder="Branch" title="Branch Name: Creating new branch, enter the name you would like without date at start and ticket number at end. OR the exact name of branch you want to checkout">');
// 			}

// 		}
// 	});
// }

// function toggleBranchSelection(is_new_branch)
// {
// 	if (is_new_branch == "y")
// 	{
// 		$("#branch_selection").hide();
// 	}
// 	else
// 	{
// 		$("#branch_selection").show();
// 	}
// }

// function check_branch_options()
// {
// 	var new_branch = $('input:radio[name=new_branch]:checked').val();
// 	toggleBranchSelection(new_branch);

// 	// if(new_branch != "")
// 	// {
// 	// 	var repo = $('#repository').val();

// 	// 	if(repo != '')
// 	// 	{
// 	// 		if(new_branch == 'n')
// 	// 		{
// 	// 			get_branch_list(repo);
// 	// 		}
// 	// 		else
// 	// 		{
// 	// 			$('.branch_input').replaceWith('<input type="text" class="branch_input" name="branch_input" id="branch_input" placeholder="Branch" title="Branch Name: Creating new branch, enter the name you would like without date at start and ticket number at end.">');
// 	// 		}

// 	// 		$('#project_options_level_3').show();
// 	// 	}
// 	// 	else
// 	// 	{
// 	// 		$('#project_options_level_3').hide();
// 	// 	}
// 	// }
// 	// else
// 	// {
// 	// 	$('#project_options_level_3').hide();
// 	// }
// }

// $(document).tooltip();

$(document).ready(function() 
{
	// $(document).tooltip();


	$('.delete_project').click(function()
	{
		var port = $(this).attr('id');
		var domain = $(this).attr('domain');
		if (confirm('Are you sure you want to delete this domain ' + domain + '?'))
		{
			$.ajax(
			{
				url: '/end_project.php',
				type: "POST",
				data: 
				{
					port:port
				},
				success: function (data) {
					if (data.match("^tab 1 of window id"))
					{
						alert('Please go to your terminal to complete the rest of process');
					}
					else
					{
						alert('Not able to open new terminal. Please try again or try run the command in terminal manually');
					}

				}
			});
		}
	});

	$('#create_project').click(function()
	{
		var domain = $('#domain').val();
		var repository = $('#repository').val();
		var branch = $('#branch_input').val();
		var ticket_number = $('#ticket_number').val();
		var database = $('#database').val();
		var host = $('#host').val();
		var commit_message = $('#commit_message').val();

		if (confirm('Are you sure you want to delete this?')) 
		{
			$.ajax(
			{
				url: '/new_project.php',
				type: "POST",
				data: 
				{
					domain:domain,
					repository:repository,
					domain:domain,
					branch:branch,
					ticket_number:ticket_number,
					database:database,
					host:host,
					commit_message:commit_message
				},
				success: function (data) 
				{
					if (data.match("^tab 1 of window id"))
					{
						alert('Please go to your terminal to complete the rest of process');
					}
					else
					{
						alert('Not able to open new terminal. Please try again or try run the command in terminal manually');
					}
				}
			});
		}
	});

	$("#go").click(function() 
	{
		var ticket=$("#ticket").val();

		//Check if the ticket number starts with #. (if it does, substring  and slice would be same)
		if(ticket.slice(0,1) == '#')
		{
			ticket = ticket.slice(1);
		}

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
		if (table_id == 'new_project')
		{
			check_branch_options();
		}

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