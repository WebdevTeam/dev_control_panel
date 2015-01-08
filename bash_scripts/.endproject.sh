
#CONFIG SECTION ------------------------------------
# Get exact path of symlink
SELF_PATH=$(cd -P -- "$(dirname -- "$0")" && pwd -P) && SELF_PATH=$SELF_PATH/$(basename -- "$0")
CONFIG_FILE="config/.config.sh"
CONFIG_FILE_OVERRIDE="config/.endproject_config.sh"
SOURCE_FILE="${SELF_PATH/.endproject.sh/${CONFIG_FILE}}"
SOURCE_FILE_OVERRIDE="${SELF_PATH/.endproject.sh/${CONFIG_FILE_OVERRIDE}}"
#You should create the config file witht he follwing name. Please look at .endproject_config.example.sh
if [ -f $SOURCE_FILE_OVERRIDE ] ; then
	source "${SOURCE_FILE_OVERRIDE}"
elif [ -f $SOURCE_FILE ] ; then
	source "${SOURCE_FILE}"
else
	echo -e "No config file found. Please create either .config.sh or .endproject_config.sh\nPlease look at config/.config.example.sh for an example\n\nNOTE: .endproject_config.sh will take precedence over .config.sh"
	exit
fi
#END CONFIG SECTION ------------------------------------
#......EXCEPT FOR SECTION 1.1 APACHE RESTART - Please check the best way to restart apache on your system ----------


exit_port_number=-1

port_number=$exit_port_number

usage="\n--------------End Project--------------\n"
usage+="USAGE: sudo bash .endproject.sh [-h] [-f] [-p] [-l] [-v] [-y] [-u ssh_user] [-n svn_user] [-d deployment_file] [-s sitename]

where:
    -h   :  show this help text
    -p   :  [OPTIONAL] Port of the Branch / Domain you want to delete. Leave blank (or set to $exit_port_number) for more options
    -d   :  [OPTIONAL] Database you want to delete.
    "

while getopts ':hz:p:d' option; do
  case "$option" in
    h) echo -e "$usage"
       exit
       ;;
    p) port_number=$OPTARG
       ;;
    d) database_delete=$OPTARG
       ;;
    :) printf "missing argument for -%s\n" "$OPTARG" >&2
       echo -e "$usage" >&2
       exit 1
       ;;
   \?) printf "illegal option: -%s\n" "$OPTARG" >&2
       echo -e "$usage" >&2
       exit 1
       ;;
  esac
done
shift $((OPTIND - 1))

# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

domains_array=($(jq -r '.[].domain' $control_panel_log))
branches_array=($(jq -r '.[].branch' $control_panel_log))
repos_array=($(jq -r '.[].repo' $control_panel_log))
ports_array=($(jq -r '.[].port' $control_panel_log))

# Check if the port given is one of the ports from the feed file
row=-1;
for i in "${!ports_array[@]}"; do
	port=${ports_array[$i]}
	if [ "$port" == "$port_number" ] ; then
		row=$i;
	fi
done

if [ "$port_number" == "$exit_port_number" ] || [ "$row" == "-1" ]; then

	echo -e "\n ------------- PROJECTS ------------------";
	echo -e "[PORT] -- [BRANCH] -- [DOMAIN] -- [REPOSITORY]\n";
	for i in "${!domains_array[@]}"; do
		r=${domains_array[$i]}
		domain=${domains_array[$i]}
		branch=${branches_array[$i]}
		repo=${repos_array[$i]}
		port=${ports_array[$i]}
		echo -e "[$port] $branch -- $domain -- repo:$repo";
	done
	echo -e "\nEnter a port number to delete project: (\"$exit_port_number\" to delete manually)"
	read -p "Port Number: " port_number
fi


if [ "$port_number" == "-1" ] ; then
	echo -e "\n---- YOU ARE DELETING A PROJECT MANUALLY ----"
	read -p "Please enter Domain Name to be deleted: " domain_delete
	read -p "Please enter Brnach Name to be deleted:" branch_delete
	read -p "Please choose Repo - sitebase/centraladmin/privatesales/logisticsAPI/TMO? ( s/c/p/l/t )" repo_delete

else
	domain_delete=${domains_array[$row]}
	branch_delete=${branches_array[$row]}
	repo_delete=${repos_array[$row]}
fi


echo -e "\n-------------- REPO LOCATION ------------------"
if [ "$repo_delete" == "" ]; then
	echo "SVN repo - sitebase/centraladmin/privatesales/logisticsAPI/TMO? ( s/c/p/l/t ): \c"
	read repo_delete
fi
if [ "$repo_delete" = "s" ] || [ "$repo_delete" = "s" ]; then
	svn_branch_url="http://svn.worldstores.co.uk/sitebase/branch"
	sites_base_directory=$sites_base_directory_s
elif [ "$repo_delete" = "k" ] || [ "$repo_delete" = "K" ]; then
	svn_branch_url="http://svn.worldstores.co.uk/skubase/branches/"
	sites_base_directory=$sites_base_directory_k
elif [ "$repo_delete" = "c" ] || [ "$repo_delete" = "C" ]; then
	svn_branch_url="http://svn.worldstores.co.uk/centraladmin/branches"
	sites_base_directory=$sites_base_directory_c
elif [ "$repo_delete" = "p" ] || [ "$repo_delete" = "P" ]; then
	svn_branch_url="http://svn.worldstores.co.uk/privatesales/branches"
	sites_base_directory=$sites_base_directory_p
elif [ "$repo_delete" = "l" ] || [ "$repo_delete" = "L" ]; then
	svn_branch_url="http://svn.worldstores.co.uk/logisticsapi/branch/"
	sites_base_directory=$sites_base_directory_l
elif [ "$repo_delete" = "t" ] || [ "$repo_delete" = "T" ]; then
	sites_base_directory=$sites_base_directory_t
	svn_branch_url="http://svn.worldstores.co.uk/tmo/branches/"
else
	echo -e "INAPPROPRIATE RESPONSE [$repo_delete]. EXITING.........\n"
	exit
fi

echo -e "\nYou have selected to delete:\n[Dir]: $sites_base_directory\n[Domain]: $domain_delete\n[Branch]: $svn_branch_url/$branch_delete\n"
read -p "Continue? (y/n): " delete_confirm
if [ "$delete_confirm" != "y" ] ; then
	echo -e "\nYou have chosen NOT to continue\n"
	exit
fi





echo -e "\n-------------- STARTING ------------------\n"

remove_project=0


	echo -e "SVN Branch root URL is set to $svn_branch_url\n"


if [ ! -z "$domain_delete" ] && [ "$domain_delete" != "-" ]; then

#VHOSTS ------------------------------------
	echo -e "\n-------------- 1. VHOST ------------------"
	vhost_file="$vhost_path/$domain_delete.conf"

	if [ -f "$vhost_file" ]
	then
		echo "$vhost_file found."
		echo "DELETING $vhost_file......"
		/bin/rm -f $vhost_file
		echo "DELETED $vhost_file"

		echo "-------------- 1.1. RESTART APACHE ------------------"
		/usr/sbin/apachectl configtest
		echo "RESTARTING APACHE.............."
		/usr/sbin/apachectl restart
		echo "APACHE RESTARTED"

	else
		echo "NOTHING TODO - $vhost_file DOES NOT EXIST!"
	fi
#END VHOSTS ------------------------------------

#HOSTS ------------------------------------
	echo -e "\n-------------- 2. HOSTS ------------------"
	if grep -n "$domain_delete" $hosts_file &>/dev/null
		then
		echo "dev.$domain_delete found in hosts file"
		echo "DELETING dev.$domain_delete line from $hosts_file.............."
		sed "/$domain_delete/d" $hosts_file > "$hosts_file~temp"

		if mv "$hosts_file~temp" $hosts_file
			then
				echo "DELETED dev.$domain_delete entry from $hosts_file"
		fi
	else
		echo "NOTHING TODO - dev.$domain_delete ENTRY DOES NOT EXIST IN $hosts_file"
	fi
#END HOSTS ------------------------------------

else
		echo "SKIPPING STEP 1 & 2 - domain_name NOT SPECIFIED"
fi

if [ ! -z "$branch_delete" ] && [ "$branch_delete" != "-" ]; then
#SITES ------------------------------------
	echo -e "\n-------------- 3. SITES ------------------"
	sites_directory="$sites_base_directory/$branch_delete"
	if [ -d "$sites_directory" ]; then
		echo "DIRECTORY $sites_directory EXISTS"
		read -p "Okay to delete? (y/n): " answer
		if [ "$answer" = "y" ] || [ "$answer" = "Y" ]
			then
			/bin/rm -Rf $sites_directory
			echo "DELETED $sites_directory"
			remove_project=1
		else
			echo "KEEPING $sites_directory"
		fi
	else
		echo "NOTHING TODO - $sites_directory DOES NOT EXIST"
		remove_project=1
	fi
#END SITES ------------------------------------

#SVN PURGE ------------------------------------
	echo -e "\n-------------- 4. SVN Purge Branch ------------------"
	# Check branch exists....
	svnbranch="$svn_branch_url/$branch_delete"
	$svn_path info --username $svn_username $svnbranch &>/dev/null
	#Result is 0 if it exists, 1 if not
	branch_exists=$?
	if [ $branch_exists -eq 0 ]; then
		echo "BRANCH $svnbranch EXISTS"
		read -p "Okay to purge? (y/n): " svnanswer
		if [ "$svnanswer" = "y" ] || [ "$svnanswer" = "Y" ]
			then
			$svn_path delete --username $svn_username  $svnbranch -m "[PURGE]: /branch/ $svnbranch"

			$svn_path info --username $svn_username $svnbranch &>/dev/null
			#Result is 0 if it exists, 1 if not
			branch_still_exists=$?
			if [ $branch_still_exists -eq 0 ]; then
				echo "ERROR DELETING BRANCH $svnbranch - IT STILL EXISTS"
			else
				echo "PURGED $svnbranch"
			fi

		else
			echo "KEEPING $svnbranch"
		fi
	else
		echo "NOTHING TODO - BRANCH $svnbranch DOES NOT EXIST"
	fi
#END SVN PURGE ------------------------------------

else
		echo "SKIPPING STEP 3 & 4 - branch_name NOT SPECIFIED"
fi


 # SET database name
	if [ ! -z "$database_delete" ] && [ "$database_delete" != "-" ]; then
		database_name=$database_delete
	else
		database_name="$domain_delete"
	fi

	if [ ! -z "$database_name" ] && [ "$database_name" != "-" ]; then

#LOCAL DATABASE ------------------------------------
		echo -e "\n-------------- 5. LOCAL DATABASE ------------------"
		#Check if database exists
		if mysql $db_conn -e "USE $database_name" > /dev/null 2>&1; then

			read -p "Do you want to delete the local database $database_name? (y/n): " dbanswer

			if [ "$dbanswer" = "y" ] || [ "$dbanswer" = "Y" ]; then

				mysql $db_conn -e "DROP DATABASE $database_name;"
				#Check if database exists
				if mysql $db_conn -e "USE $database_name" > /dev/null 2>&1; then
					echo "THERE WAS AN ERROR DROPPING DATABASE $database_name - IT STILL EXISTS!!"
				else
					echo "SUCCESSFULLY DROPPED LOCAL DATABASE $database_name"
				fi

			else
				echo "KEEPING LOCAL DATABASE $database_name"
			fi
		else
		echo "NOTHING TODO - database $database_name DOES NOT EXIST"
		fi
#END LOCAL DATABASE ------------------------------------

	else
			echo "SKIPPING STEP 5 - database_name or domain_name NOT SPECIFIED"
	fi

#DEV CONTROL PANEL ------------------------------------
	echo -e "\n-------------- 6. DEV CONTROL PANEL ------------------"

if [ ! -z "$branch_delete" ] && [ "$branch_delete" != "-" ]; then
	dev_identifier=$branch_delete
elif [ ! -z "$domain_delete" ] && [ "$domain_delete" != "-" ]; then
	dev_identifier=$domain_delete
else
	dev_identifier="-"
fi

if [ ! -z "$dev_identifier" ] && [ "$dev_identifier" != "-" ]; then

	if grep -n "$dev_identifier" $control_panel_log &>/dev/null
		then
		echo "$dev_identifier found in dev control panel log file"
		echo "DELETING $dev_identifier line from $control_panel_log..."
		chmod 777 $control_panel_log
		sed "/$dev_identifier/d" $control_panel_log > "$control_panel_log~temp"

		if mv "$control_panel_log~temp" $control_panel_log
			then
			echo "DELETED $dev_identifier entry from $control_panel_log"
		fi
		chmod 777 $control_panel_log
	else
		echo "NOTHING TODO - $dev_identifier ENTRY DOES NOT EXIST IN $control_panel_log"
	fi
else
	echo "SKIPPING STEP 6 - branch_name or domain_name NOT SPECIFIED"
fi
#END DEV CONTROL PANEL ------------------------------------

if [ ! -z "$domain_delete" ] && [ "$domain_delete" != "-" ]; then
	sublime_project=$domain_delete
elif [ ! -z "$branch_delete" ] && [ "$branch_delete" != "-" ]; then
	sublime_project=$branch_delete
else
	sublime_project="-"
fi


if [ ! -z "$sublime_project" ] && [ "$sublime_project" != "-" ]; then

#SUBLIME PROJECT FILE ------------------------------------
	echo -e "\n-------------- 7. SUBLIME PROJECT FILE ------------------"
	sublime_project_file="$sublime_projects_path/$sublime_project.sublime-project"
	sublime_workspace_file="$sublime_projects_path/$sublime_project.sublime-workspace"

	if [ -f "$sublime_project_file" ]; then

		if [ $remove_project -eq 0 ]; then
			read -p "\nDo you want to delete the sublime project info $sublime_project? (y/n): " subanswer
			if [ "$subanswer" = "y" ] || [ "$subanswer" = "Y" ]; then
				remove_project=1
			fi
		fi

		if [ $remove_project -eq 1 ]; then
			echo "$sublime_project_file found."
			echo "DELETING $sublime_project_file......"
			/bin/rm -f $sublime_project_file
			echo "DELETED $sublime_project_file"
		fi

	else
		echo "NOTHING TODO - $sublime_project_file DOES NOT EXIST!"
	fi

	if [ -f "$sublime_workspace_file" ]; then

		if [ $remove_project -eq 1 ]; then
			echo "$sublime_workspace_file found."
			echo "DELETING $sublime_workspace_file......"
			/bin/rm -f $sublime_workspace_file
			echo "DELETED $sublime_workspace_file"
		fi
	else
		echo "NOTHING TODO - $sublime_workspace_file DOES NOT EXIST!"
	fi

#END SUBLIME PROJECT FILE ------------------------------------

	if [ $remove_project -eq 0 ]; then
		echo "KEEPING SUBLIME PROJECT"
	fi


else
	echo "SKIPPING STEP 7 & 8 - branch_name or domain_name NOT SPECIFIED"
fi


echo -e "\n-------------- COMPLETE ------------------"

echo -e "\n"