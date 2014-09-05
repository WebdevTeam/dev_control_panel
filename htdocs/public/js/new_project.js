function update_branch_list(repo)
{
	$.ajax(
	{
		url: '/update_branch_list.php',
		type: "POST",
		async: false,
		cache: false,
		data: {
			repo:repo
		},
		success: function (data) 
		{
			if (data == "completed")
			{
				get_branch_list(repo);
			}
			else
			{
				alert("No branches found. Please refresh the branch list and try again. Or enter the exact name of the branch");
				setFreeBranchInput(true);
			}
		}
	});
}

function get_branch_list(repo)
{
	var branch_select = '';
	$.ajax(
	{
		url: '/get_branch_list.php',
		type: "POST",
		data: 
		{
			repo:repo
		},
		success: function (data) 
		{
			if(data.substr(0,7)=='<select')
			{
				// $('.branch_input').replaceWith(data)
				setFreeBranchInput(false, data);
			}
			else if (data == "file_not_found")
			{
				update_branch_list(repo);
			}
			else
			{
				alert("No branches found. Please refresh the branch list and try again. Or enter the exact name of the branch");
				// $('.branch_input').replaceWith('<input type="text" class="branch_input" name="branch_input" id="branch_input" placeholder="Branch" title="Branch Name: Creating new branch, enter the name you would like without date at start and ticket number at end. OR the exact name of branch you want to checkout">');
				setFreeBranchInput(true);
			}
		}
	});
}

function setFreeBranchInput(isFree, newValue)
{
	if (isFree)
	{
		$("#refresh_branch_list_all").hide();
		$('.branch_input').replaceWith('<input type="text" class="branch_input" name="branch_input" id="branch_input" placeholder="Branch" title="Branch Name: Creating new branch, enter the name you would like without date at start and ticket number at end. OR the exact name of branch you want to checkout">');
		$("input:radio[name=new_branch][value=y]").prop("checked", true);
	}
	else
	{
		$("#refresh_branch_list_all").show();
		$('.branch_input').replaceWith(newValue);
	}
}

function toggleBranchSelection(is_new_branch)
{
	if (is_new_branch == "y")
	{
		$("#refresh_branch_list_all").hide();
	}
	else
	{
		$("#refresh_branch_list_all").show();
	}
}

function check_branch_options()
{
	//New branch or not
	var new_branch = $('input:radio[name=new_branch]:checked').val();

	var repo = $('#repository').val();

	if(repo != '')
	{
		if(new_branch == 'n')
		{
			get_branch_list(repo);
		}
		else
		{
			setFreeBranchInput(true);
		}
		toggleBranchSelection(new_branch);
	}
	else
	{
		if (new_branch == 'n')
		{
			setFreeBranchInput(true);
			alert("Please select a repository first.");
			// $("input:radio[name=new_branch][value=y]").prop("checked", true);
		}
	}

}

$(document).ready(function() 
{
	$(document).tooltip();
	$('#create_project').click(function()
	{
		var domain = $('#domain').val();
		var repository = $('#repository').val();
		var branch = $('#branch_input').val();
		var ticket_number = $('#ticket_number').val();
		var database = $('#database').val();
		var host = $('#host').val();
		var commit_message = $('#commit_message').val();

		if (confirm('Are you sure you want to create this project?')) 
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

	$('#refresh_branch_list_all').click(function()
	{
		var repo = $('#repository').val();
		update_branch_list(repo);
	});

	// $('#repository').change(function () 
	// {
	// 	if ($(this).val() == "")
	// 	{
	// 		$("input:radio[name=new_branch][value=y]").prop("checked", true);
	// 	}
	// });

});