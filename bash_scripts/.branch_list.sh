#!/bin/sh

#CONFIG SECTION ------------------------------------
# Get exact path of symlink
SELF_PATH=$(cd -P -- "$(dirname -- "$0")" && pwd -P) && SELF_PATH=$SELF_PATH/$(basename -- "$0")
CONFIG_FILE="config/.config.sh"
SOURCE_FILE="${SELF_PATH/.branch_list.sh/${CONFIG_FILE}}"



#You should create the config file witht he follwing name. Please look at /config/.config.example.sh
if [ -f $SOURCE_FILE ] ; then
	source "${SOURCE_FILE}"
else
	echo -e "No config file found. Please create /config/.config.sh.\nPlease look at /config/.config.example.sh for an example"
	exit
fi

while getopts r: option
do
        case "${option}"
        in
                r) repo_location=${OPTARG};;
        esac
done

	if [ "$repo_location" == "" ]; then
		echo "SVN repo - sitebase/centraladmin/privatesales/LogisticAPI/TMO? ( s/c/p/l/t ) : \c"
		read repo_location
	fi
	if [ "$repo_location" = "s" ] || [ "$repo_location" = "S" ]; then
		svn_branch_url="$svn_url/sitebase/branch"
		log_file="branch_list_s.xml"
	elif [ "$repo_location" = "k" ] || [ "$repo_location" = "K" ]; then
		svn_branch_url="$svn_url/skubase/branches"
		log_file="branch_list_k.xml"
	elif [ "$repo_location" = "c" ] || [ "$repo_location" = "C" ]; then
		svn_branch_url="$svn_url/centraladmin/branches"
		log_file="branch_list_c.xml"
	elif [ "$repo_location" = "p" ] || [ "$repo_location" = "P" ]; then
		svn_branch_url="$svn_url/privatesales/branches"
		log_file="branch_list_p.xml"
	elif [ "$repo_location" = "l" ] || [ "$repo_location" = "L" ]; then
		svn_branch_url="$svn_url/logisticsapi/branch"
		log_file="branch_list_l.xml"
	elif [ "$repo_location" = "t" ] || [ "$repo_location" = "T" ]; then
		svn_branch_url="$svn_url/tmo/branches"
		log_file="branch_list_t.xml"
	else
		echo -e "INAPPROPRIATE RESPONSE. EXITING.........\n"
		exit
	fi

$svn_path list --xml $svn_branch_url > $control_panel_log_dir/$log_file
osascript -e 'tell application "Terminal" to activate' -e 'tell application "System Events" to tell process "Terminal" to keystroke "w" using command down'

#Create a sentinel file so php knows the script has finished
touch $control_panel_log_dir/"update_complete"
 exit 1