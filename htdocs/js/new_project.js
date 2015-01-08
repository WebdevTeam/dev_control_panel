function update_branch_list(repo)
{
	$("#update_icon").css("display", "inline");
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
			$("#update_icon").hide();
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
		var fields = Object.create(null);
		fields["domain"] = {required: true, display_name: "Domain"};
		fields["repository"] = {required: true, display_name: "Repository"};
		fields["branch_input"] = {required: true, display_name: "Branch"};
		fields["ticket_number"] = {required: true, display_name: "Ticket Number"};
		fields["database"] = {required: true, display_name: "Database"};
		fields["host"] = {required: true, display_name: "Host"};
		fields["commit_message"] = {required: false, display_name: "Commit Message"};

		var fail = false;
		for(var field in fields)
		{
			if (!fail)
			{
				var value = $('#'+field).val();
				if (typeof value != 'undefined' && value != "")
				{
					fields[field].value = value;
				}
				else if (!fields[field].required)
				{
					fields[field].value = "" ;
				}
				else
				{
					fail = true;
					alert(fields[field].display_name + " must not be empty");
				}
			}
		}

		if (!fail && confirm('Are you sure you want to create this project?'))
		{
			$.ajax(
			{
				url: '/new_project.php',
				type: "POST",
				data:
				{
					data: JSON.stringify(fields)
				},
				success: function (data)
				{
					result = JSON.parse(data);
					if (result['status'].match("^tab 1 of window id"))
					{
						alert('Please go to your terminal to complete the rest of process');
					}
					else
					{
						alert('Not able to open new terminal. Please try again or run manually:\n\n' + result['command'] + '\n\n');
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