#!/bin/sh


#CONFIG SECTION ------------------------------------
# Get exact path of symlink
SELF_PATH=$(cd -P -- "$(dirname -- "$0")" && pwd -P) && SELF_PATH=$SELF_PATH/$(basename -- "$0")
CONFIG_FILE="config/.config.sh"
CONFIG_FILE_OVERRIDE="config/.newproject_config.sh"
SOURCE_FILE="${SELF_PATH/.newproject_git.sh/${CONFIG_FILE}}"
SOURCE_FILE_OVERRIDE="${SELF_PATH/.newproject_git.sh/${CONFIG_FILE_OVERRIDE}}"

INCLUDE_FLATURL="includes/flaturl.conf"
flaturl_path="${SELF_PATH/.newproject_git.sh/${INCLUDE_FLATURL}}"

SITEBASE_LOGIN="includes/sitebase_login.php"
orig_sitebase_login_file="${SELF_PATH/.newproject_git.sh/${SITEBASE_LOGIN}}"

#You should create the config file witht he follwing name. Please look at .endproject_config.example.sh
if [ -f $SOURCE_FILE_OVERRIDE ] ; then
	source "${SOURCE_FILE_OVERRIDE}"
elif [ -f $SOURCE_FILE ] ; then
	source "${SOURCE_FILE}"
else
	echo -e "No config file found. Please create either .config.sh or .newproject_config.sh.\nPlease look at config/.config.example.sh for an example\n\nNOTE: .newproject_config.sh will take precedence over .config.sh"
	exit
fi

owner_group="$user:staff"

#END CONFIG SECTION ------------------------------------

#There should not be any need to edit this file
#......EXCEPT FOR SECTION 1.1 APACHE RESTART - Please check the best way to restart apache on your system ----------

# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

usage="\n--------------New Project--------------\n"
usage+="USAGE: .newproject.sh [-h] [-f] [-p] [-l] [-v] [-y] [-d deployment_file] [-s sitename]

where:
    -h   :  show this help text
    -d   :  Domain Name **required**
    -b   :  Branch Name **required**
    -t   :  Ticket Number
    -m   :  Commit Message
    -s   :  Database Name
    -o   :  Database Host
    -r   :  Repository - s/c/p/l/t/k (sitebase/centraladmin/privatesales/LogisticAPI/TMO/SkuBase)
    -f   :  Branch From
    -y   :  Is Trunk
    "

#Parameter Mapping ------------------------------------
param_database="default"
param_database_host="default"
branch_type="site"
while getopts ':hz:y:d:b:t:m:s:o:r:f' option; do
  case "$option" in
    h) echo -e "$usage"
       exit
       ;;
    y) branch_type="master"
       ;;
    d) param_domain_name=$OPTARG
       ;;
    b) param_branch_name=$OPTARG
       ;;
    t) param_ticket_number=$OPTARG
       ;;
    m) param_commit_message=$OPTARG
       ;;
    s) param_database=$OPTARG
       ;;
    o) param_database_host=$OPTARG
       ;;
    r) param_repo_location=$OPTARG
       ;;
    f) param_branch_from=$OPTARG
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

if [ -z $param_domain_name ]; then
    echo -e "\nERROR: Domain Name Required"
    echo -e "$usage" >&2
    exit
fi

if [ -z $param_branch_name ]; then
    echo -e "\nERROR: Branch Name Required"
    echo -e "$usage" >&2
    exit
fi

default_database=$param_domain_name


#DO SOME CHECKS BEFORE FILE IS RUN E.G. port_count file must exist
if [ -f "$port_count_file" ]; then
    port_number=$(<$port_count_file)

	if [[ $port_number =~ ^[0-9]{3,4}$ ]]; then
		port_number=$((port_number+1))
	else
		echo "SETUP FAILURE 1 - Port Number File - NO PORT NUMBER EXISTS in $port_count_file"
		exit
	fi
else
	echo "SETUP FAILURE 1 - Port Number File - NO port_count file exists in $port_count_file"
	exit
fi

if [ ! -f "$flaturl_path" ]; then
	echo "SETUP WARNING 1 - Flat URL file DOES NOT EXIST IN DIRECTORY $flaturl_path - mod-rewrites will not work"
fi




	repo_location="$param_repo_location"
	if [ "$repo_location" == "" ]; then
		echo -e "\n-------------- STARTING WITH MANUAL CONFIGURATION ------------------\n"
		echo -e "\n-------------- REPO LOCATION ------------------"
		echo "Choose GIT Repo - Sitebase/Centraladmin/Privatesales/Logistics_api/Tmo/SKUbase?"
		read -p "( s/c/p/l/t/k ) : " repo_location
	else
		echo -e "\n-------------- STARTING WITH AUTO-CONFIG ------------------\n"
		echo -e "\n-------------- REPO LOCATION ------------------"
	fi
	if [ "$repo_location" = "s" ] || [ "$repo_location" = "s" ]; then
	    echo "No Longer available"
	    exit
	elif [ "$repo_location" = "k" ] || [ "$repo_location" = "K" ]; then
	    sites_base_directory=$sites_base_directory_k
		git_master_url="$git_url/skubase.git"
		repo_location="k"
	elif [ "$repo_location" = "c" ] || [ "$repo_location" = "C" ]; then
	    sites_base_directory=$sites_base_directory_c
		git_master_url="$git_url/centraladmin.git"
		repo_location="c"
	elif [ "$repo_location" = "p" ] || [ "$repo_location" = "P" ]; then
	    sites_base_directory=$sites_base_directory_p
		git_master_url="$git_url/casafina.git"
		repo_location="p"
	elif [ "$repo_location" = "l" ] || [ "$repo_location" = "L" ]; then
	    sites_base_directory=$sites_base_directory_l
	    git_master_url="$git_url/logisticapi.git"
		repo_location="l"
	elif [ "$repo_location" = "t" ] || [ "$repo_location" = "T" ]; then
	    sites_base_directory=$sites_base_directory_t
		git_master_url="$git_url/tmo.git"
		repo_location="t"
	else
		echo -e "INAPPROPRIATE RESPONSE. EXITING.........\n"
		exit
	fi
		echo -e "GIT Path is set to $git_master_url\n"


absolute_mode=0
tracticket_message=""
tracticket=""
if [ ! -z "$param_ticket_number" ] && [ "$param_ticket_number" != "-" ]; then
	tracticket="_$param_ticket_number"
	tracticket_message="For Trac Ticket #$param_ticket_number"
fi
branch_name_orignal="$param_branch_name"
if [[ $param_branch_name =~ ^http ]]; then
	branch_name="$param_branch_name"
	absolute_mode=1
elif [[ $param_branch_name =~ ^[0-9]{4} ]]; then
	branch_name="$param_branch_name"
else
	DATE=`date +%Y_%m_%d_`
	branch_name="$DATE$param_branch_name$tracticket"
fi

# if [ $absolute_mode -eq 1 ]; then
# svnbranch="$branch_name"
# else
# svnbranch="$svn_branch_url/$branch_name"
# svnbranch_orignal="$svn_branch_url/$branch_name_orignal"
# fi

if [ ! -z "$param_database" ] && [ "$param_database" != "-" ]; then
	database_name=$param_database
else
	database_name="$default_database"
fi

if [ ! -z "$param_database_host" ] && [ "$param_database_host" != "-" ]; then
	database_host=$param_database_host
else
	database_host="$default_database_host"
fi

echo -e "\n-------------- 1. CLONE AND CREATE BRANCH ------------------"

if [ $absolute_mode -eq 0 ]; then
	$git_path ls-remote --heads $git_master_url | grep -sw $branch_name &>/dev/null
	orignal_branch_exists=$?
	echo "[$git_master_url] - [$branch_name] Branch Found = $orignal_branch_exists"
	
	#Result is 0 if it exists, 1 if not
fi

use_branch='N'
branch_exists=1

if [ $branch_type != "master" ]; then
	if [ $orignal_branch_exists -eq 0 ]; then
		echo "Branch [ $branch_name_orignal ] exists."
		echo "New branch [ $branch_name ]"
		echo "Use current branch ? ( y/n ) : \c"
		read use_branch
		if [ "$use_branch" = "y" ] || [ "$use_branch" = "Y" ];then
			branch_exists=0
			branch_name="$branch_name_orignal"
		fi
	fi
else
	branch_name="master"
fi

	# if [ $branch_exists -ne 0 ];then
	# 	echo "This branch does not exists."
	# 	$svn_path info --username $svn_username $svnbranch &>/dev/null
	# 	#Result is 0 if it exists, 1 if not
	# 	branch_exists=$?
	# fi
php_storm_project_name=""
if [ $absolute_mode -eq 1 ]; then

	if [ -z "$param_domain_name" ] || [ "$param_domain_name" == "-" ]; then
		echo "In absolute_mode for repo url - domain must be specified to continue\n"
		echo "Exiting\n"
		exit
	fi

	sites_directory="$sites_base_directory/$param_domain_name"
	php_storm_project_name="$param_domain_name"
else
	sites_directory="$sites_base_directory/$branch_name"
	php_storm_project_name="$branch_name"
fi

echo "Do you want to clone git Repository to $sites_directory? ( y/n ) : \c"
read checkoutanswer
if [ "$checkoutanswer" = "y" ] || [ "$checkoutanswer" = "Y" ]; then
	echo "Creating Directory $sites_directory"
	mkdir -p $sites_directory
	echo "Change owner_group on $sites_directory"
	chown -R $owner_group $sites_directory
	echo "Start cloning"
	$git_path clone $git_master_url $sites_directory
	echo "Repository has been cloned to $sites_directory"
	echo "Change location to $sites_directory"
	cd $sites_directory
	if [ $orignal_branch_exists -eq 0 ]; then
		echo "Fetch all branches"
		$git_path fetch
		echo "checkout branche $branch_name"
		$git_path checkout $branch_name
	else
		
		echo "[NEW BRANCH]: - $branch_name"
		echo "[REPO]: - $git_master_url"
		read -p "Okay to create? ( y/n ) : " gitcreatanswer

		if [ "$gitcreatanswer" = "y" ] || [ "$gitcreatanswer" = "Y" ]; then
			echo "Creating and checkout Branch $branch_name"
			$git_path checkout -b $branch_name
		fi
	fi
	chown -R $owner_group $sites_directory
else
	echo "GIT Repository $git_master_url NOT CLONED"

fi



echo -e "\n-------------- 2. SETUP PROJECT ------------------"



if [ -d "$sites_directory" ]; then
	echo -e "\nDo you want to setup a new project for $branch_name? ( y/n ) : \c"
	read setup_project_answer
	if [ "$setup_project_answer" = "y" ] || [ "$setup_project_answer" = "Y" ]; then

	if [ ! -z "$param_domain_name" ] && [ "$param_domain_name" != "-" ]; then

		echo -e "\n-------------- 3. VHOST ------------------"

	#Port number should be set by now - only increment if we have used it to create vhost
	if [ -f "$port_count_file" ]; then

		if [[ $port_number =~ ^[0-9]{3,4}$ ]]; then
			echo "$port_number" > $port_count_file
		fi
	fi

		vhost_file="$vhost_path/$param_domain_name.conf"

		if [ -f "$vhost_file" ]; then
			apacherestart=0
			echo "NOTHING TODO - $vhost_file ALREADY EXISTS!"
		else
			apacherestart=1
			echo "CREATING VHOST $vhost_file........"

	if [ "$repo_location" = "s" ] || [ "$repo_location" = "k" ]; then
#create vhost file
cat <<EOF > $vhost_file
Listen *:61$port_number

<VirtualHost *:80>
	DocumentRoot $sites_directory/htdocs/
	ServerName dev.$param_domain_name
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/htdocs">
		allow from all
		Options -Indexes
	</Directory>
	Include $flaturl_path
</VirtualHost>

<VirtualHost *:61$port_number>
	ServerName https://dev.$param_domain_name
	DocumentRoot $sites_directory/httpsdocs
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/httpsdocs">
		allow from all
		Options -Indexes
	</Directory>
#SSL config
	SSLEngine on
	SSLCertificateFile /etc/apache2/certs/ssl.cert
	SSLCertificateKeyFile /etc/apache2/certs/ssl.key
</VirtualHost>
EOF
	elif [ "$repo_location" = "c" ] || [ "$repo_location" = "C" ]; then

#create vhost file
cat <<EOF > $vhost_file
Listen *:61$port_number

<VirtualHost *:80>
	ServerName dev.$param_domain_name
	Redirect / https://dev.$param_domain_name:61$port_number/admin/admin_login.php
</VirtualHost>

<VirtualHost *:61$port_number>
	ServerName https://dev.$param_domain_name
	DocumentRoot $sites_directory/httpsdocs
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/httpsdocs">
		allow from all
		Options -Indexes
	</Directory>
#SSL config
	SSLEngine on
	SSLCertificateFile /etc/apache2/certs/ssl.cert
	SSLCertificateKeyFile /etc/apache2/certs/ssl.key
</VirtualHost>
EOF
	elif [ "$repo_location" = "p" ] || [ "$repo_location" = "P" ]; then

#create vhost file
cat <<EOF > $vhost_file
Listen *:61$port_number

<VirtualHost *:80>
	DocumentRoot $sites_directory/htdocs/
	ServerName dev.$param_domain_name
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/htdocs">
		allow from all
		Options -Indexes
	</Directory>
	Include $flaturl_path
</VirtualHost>

<VirtualHost *:61$port_number>
	ServerName https://dev.$param_domain_name
	DocumentRoot $sites_directory/httpsdocs
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/httpsdocs">
		allow from all
		Options -Indexes
	</Directory>
#SSL config
	SSLEngine on
	SSLCertificateFile /etc/apache2/certs/ssl.cert
	SSLCertificateKeyFile /etc/apache2/certs/ssl.key
</VirtualHost>
EOF
	elif [ "$repo_location" = "l" ] || [ "$repo_location" = "L" ]; then

#create vhost file
cat <<EOF > $vhost_file
Listen *:61$port_number

<VirtualHost *:80>
	ServerName dev.$param_domain_name
	Redirect / https://dev.$param_domain_name:61$port_number/v1/index.php
</VirtualHost>

<VirtualHost *:61$port_number>
	ServerName https://dev.$param_domain_name
	DocumentRoot $sites_directory/httpsdocs
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/httpsdocs">
		allow from all
		Options -Indexes
	</Directory>
#SSL config
	SSLEngine on
	SSLCertificateFile /etc/apache2/certs/ssl.cert
	SSLCertificateKeyFile /etc/apache2/certs/ssl.key
</VirtualHost>
EOF
	elif [ "$repo_location" = "t" ] || [ "$repo_location" = "T" ]; then

#create vhost file
cat <<EOF > $vhost_file
Listen *:61$port_number

<VirtualHost *:80>
	ServerName dev.$param_domain_name
	Redirect / https://dev.$param_domain_name:61$port_number/index.php
</VirtualHost>

<VirtualHost *:61$port_number>
	ServerName https://dev.$param_domain_name
	DocumentRoot $sites_directory
	ErrorLog $sites_directory/logfiles/error.log
	TransferLog $sites_directory/logfiles/access.log
	<Directory "$sites_directory/httpsdocs">
		allow from all
		Options -Indexes
	</Directory>
#SSL config
	SSLEngine on
	SSLCertificateFile /etc/apache2/certs/ssl.cert
	SSLCertificateKeyFile /etc/apache2/certs/ssl.key
</VirtualHost>
EOF
	fi

			echo "$vhost_file SUCCESSFULLY CREATED"
		fi

		echo -e "\n-------------- 4. HOSTS ------------------"
		if grep -n "$param_domain_name" $hosts_file &>/dev/null
			then
			echo "NOTHING TODO - dev.$param_domain_name ENTRY ALREADY EXISTS IN $hosts_file"
		else
			hosts_newline="127.0.0.1\tdev.$param_domain_name"
			echo -e "APPENDING $hosts_newline TO $hosts_file"
			echo -e $hosts_newline >> $hosts_file
			echo "dev.$param_domain_name ENTRY SUCCESSFULLY APPENDED TO $hosts_file"

		fi

	else
			echo "SKIPPING STEP 3 & 4 - domain_name NOT SPECIFIED"
	fi

		if [ -d "$sites_directory" ]; then

			echo -e "\n-------------- 5. LOGS DIRECTORY ------------------"
			logs_directory="$sites_directory/logs"
			if [ -d "$logs_directory" ]; then
				echo "NOTHING TODO - $logs_directory EXISTS"
			else
				echo "CREATING LOGFILES DIRECTORY $logs_directory"
				mkdir $logs_directory
				chmod 777 $logs_directory
				echo "$logs_directory SUCCESSFULLY CREATED"
			fi

			echo -e "\n-------------- 5.1. LOGFILES DIRECTORY ------------------"
			logfiles_directory="$sites_directory/logfiles"
			if [ -d "$logfiles_directory" ]; then
				echo "NOTHING TODO - $logfiles_directory EXISTS"
			else
				echo "CREATING LOGFILES DIRECTORY $logfiles_directory"
				mkdir $logfiles_directory
				chmod 777 $logfiles_directory
				echo "$logfiles_directory SUCCESSFULLY CREATED"
			fi


			if [ "$repo_location" = "l" ] || [ "$repo_location" = "L" ]; then

				if [ "$database_host" = "default" ]; then
					database_host="api.worldstores.co.uk";
				fi
				if [ "$database_name" = "default" ]; then
					database_name="innodb_combined";
				fi

				includes_dev_file="$sites_directory/config/config.php"
				if [ -f $includes_dev_file ]; then
					echo "NOTHING TODO - $includes_dev_file EXISTS"
				else
					echo "CREATING INCLUDES DEV FILE $includes_dev_file"
cat <<EOF > $includes_dev_file
<?php

// The serialized ruleset required for the Rules Engine
\$rules_file = '../../rules/Logistics_api.rules';

// The minimum version of PHP required to run the API
\$php_min_version = '5.3.0';

// The database name is not so important since the queries address the databases directly
\$database = '$database_name';
\$user     = 'db_Syte_9106';
\$password = 'SiteBase_Db_Pwd';
\$host     = '$database_host';

/*\$database = 'combined';
\$user     = 'root';
\$password = 'optelligence';
\$host     = 'beast';
*/
/**
 * This is the URL the front end PHPUnit tests will send
 * HTTP requests to.
 *
 * @see tests/ApiTest.php
 */
\$test_url = 'https://dev.logisticsapi.local:61151/v1/';

/**
 * Determines if the API will validate the callers IP address against the
 * known list taken from the server_ips/gateway_ip tables in Limbo.
 *
 * This should be on for all cases except dev/test
 */
\$validate_ip = false;
EOF
					chmod 777 $includes_dev_file
					chown $owner_group $includes_dev_file
					echo "$includes_dev_file SUCCESSFULLY CREATED"
				fi

			else



				echo -e "\n-------------- 6. INCLUDES DEV DIRECTORY ------------------"
				if [ "$repo_location" = "t" ]; then
					includes_dev_dir="$sites_directory/includes/dev"
					if [ -d $includes_dev_dir ]; then
						echo "NOTHING TODO - $includes_dev_dir EXISTS"
					else
						echo "CREATING INCLUDES DEV DIRECTORY $includes_dev_dir"
						mkdir $includes_dev_dir
						chmod 777 $includes_dev_dir
						chown $owner_group $includes_dev_dir
						echo "$includes_dev_dir SUCCESSFULLY CREATED"
					fi

				else
					ht_directorys=( htdocs httpsdocs )

					for d in "${ht_directorys[@]}"
					do
						includes_dev_dir="$sites_directory/$d/includes/dev"
						if [ -d $includes_dev_dir ]; then
							echo "NOTHING TODO - $includes_dev_dir EXISTS"
						else
							echo "CREATING INCLUDES DEV DIRECTORY $includes_dev_dir"
							mkdir $includes_dev_dir
							chmod 777 $includes_dev_dir
							chown $owner_group $includes_dev_dir
							echo "$includes_dev_dir SUCCESSFULLY CREATED"
						fi
					done

				fi



				echo -e "\n-------------- 6.1 INCLUDES DEV FILE ------------------"

				if [ "$repo_location" = "t" ]; then
					includes_dev_file="$sites_directory/includes/dev/$param_domain_name.php"
					if [ -f $includes_dev_file ]; then
						echo "NOTHING TODO - $includes_dev_file EXISTS"
					else
						echo "CREATING INCLUDES DEV FILE $includes_dev_file"
cat <<EOF > $includes_dev_file
<?php
\$db_name = "$database_name";
\$db_host = "$database_host";
\$db_port = "";
\$db_user = "$db_user";
\$db_password = "$db_pass";
\$dev_site = "$param_domain_name";
\$dev_port = "$port_number";
\$dev_images_directory = "$sites_directory/htdocs";
\$dev_site_url = "$param_domain_name";
\$missing_images_notification = FALSE;


\$beanstalk_config = array();
\$beanstalk_host = '127.0.0.1';
\$beanstalk_port = '11300';

\$site_distribution_url = "https://www.localhost.co.uk";
EOF
						chmod 777 $includes_dev_file
						chown $owner_group $includes_dev_file
						echo "$includes_dev_file SUCCESSFULLY CREATED"
					fi
				else


					for d in "${ht_directorys[@]}"
					do
						includes_dev_file="$sites_directory/$d/includes/dev/$param_domain_name.php"
						if [ -f $includes_dev_file ]; then
							echo "NOTHING TODO - $includes_dev_file EXISTS"
						else
							echo "CREATING INCLUDES DEV FILE $includes_dev_file"
cat <<EOF > $includes_dev_file
<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

\$db_name = "$database_name";
\$db_host = "$database_host";
\$db_port = "";
\$db_user = "$db_user";
\$db_password = "$db_pass";
\$dev_site = "$param_domain_name";
\$dev_port = "$port_number";
\$dev_images_directory = "$sites_directory/htdocs";
\$dev_site_url = "$param_domain_name";
\$missing_images_notification = FALSE;

\$db_info["db"]["db_host"] = \$db_host;
\$db_info["db"]["db_name"] = \$db_name;
\$db_info["db"]["db_user"] = \$db_user;
\$db_info["db"]["db_pass"] = \$db_password;

\$beanstalk_config = array();
\$beanstalk_host = '127.0.0.1';
\$beanstalk_port = '11300';
\$beanlog_dir = '$sites_directory/logs/beanstalk';
\$site_distribution_url = "https://www.localhost.co.uk";

\$elastic_search_info = array();
EOF
							chmod 777 $includes_dev_file
							chown $owner_group $includes_dev_file
							echo "$includes_dev_file SUCCESSFULLY CREATED"
						fi
					done
				fi
			fi




			echo -e "\n-------------- 7. ADMIN LINK FILE ------------------"
			useful_links_file="$sites_directory/htdocs/admin.php"
			if [ -f $useful_links_file ]; then
				echo "$useful_links_file found."
				echo "DELETING $useful_links_file......"
				/bin/rm -f $useful_links_file
				echo "DELETED $useful_links_file"
			fi
					echo "CREATING USEFUL LINKS FILE $useful_links_file"
cat <<EOF > $useful_links_file
<?php
header('Location: https://dev.$param_domain_name:61$port_number/admin/admin_login.php');
EOF
				chmod 777 $useful_links_file
				chown $owner_group $useful_links_file

				echo "$useful_links_file SUCCESSFULLY CREATED"

			echo -e "\n-------------- 9. SITEBASE LOGIN FILE ------------------"

			if [ -f "$orig_sitebase_login_file" ]; then
				includes_httpsdocs_dev_dir="$sites_directory/httpsdocs/includes/dev"
				if [ -d $includes_httpsdocs_dev_dir ]; then

					new_sitebase_login_file="$includes_httpsdocs_dev_dir/sitebase_login.php"
					if [ -f $new_sitebase_login_file ] && [ -f $new_sitebase_login_file ]; then
						echo "NOTHING TODO - $new_sitebase_login_file files already EXISTS"
					else
						echo "INSTALLING $new_sitebase_login_file file FROM $orig_sitebase_login_file....."
						cat $orig_sitebase_login_file | sed -e "s/SUBUSER_REPLACE/$SUBUSER_REPLACE/" > ${new_sitebase_login_file}
						sudo cp $orig_sitebase_login_file $new_sitebase_login_file
						chmod 777 $new_sitebase_login_file
						chown $owner_group $new_sitebase_login_file
						echo "$new_sitebase_login_file SUCCESSFULLY INSTALLED"
					fi
				else
					echo " STEP 9 FAILURE - Dev directory DOESN'T EXISTS IN $includes_dev_dir"
				fi

			else
				echo " STEP 9 FAILURE - Sitebase Login File DOESN'T EXISTS IN $sitebase_login_file"
			fi
		else
			echo "$sites_directory DOES NOT EXIST - SKIPPING STEPS 5,6,7,8 (LOGFILES DIRECTORY, INCLUDES DEV DIRECTORY, IP AUTH CHANGE, ADMIN LINKS)"
		fi

		echo "\n-------------- 10. APACHE RESTART ------------------"
		if [ $apacherestart -eq 1 ]; then
			/usr/sbin/apachectl configtest
			echo "RESTARTING APACHE.............."
			/usr/sbin/apachectl restart
			echo "APACHE RESTARTED"

		else
			echo "NOTHING TODO - no vhost change so no apache restart required"
		fi

		echo -e "\n-------------- 11. DEV CONTROL PANEL ------------------"
		#need to check the logfile is writeable
		if grep -n "$branch_name" $control_panel_log &>/dev/null
			then
			echo "NOTHING TODO - $branch_name ENTRY ALREADY EXISTS IN $control_panel_log"
		else
			#panel_newline="port|$port_number||url|http://dev.$param_domain_name||db|$database_name||branch|$branch_name||last_deployed|-||last_deployed_to|-,"
			panel_newline=",{\"repo\":\"$repo_location\",\"type\":\"$branch_type\",\"port\":\"$port_number\",\"ticket\":\"$param_ticket_number\",\"domain\":\"$param_domain_name\",\"db\":\"$database_name\",\"branch\":\"$branch_name\",\"last_deployed\":\"-\",\"last_deployed_to\":\"-\"}"
			echo "APPENDING new project TO $control_panel_log"
			chmod 777 $control_panel_log
			sed -i '' -e '$ d' $control_panel_log
			echo $panel_newline >> $control_panel_log
			echo "]" >> $control_panel_log
			chmod 777 $control_panel_log
			if grep -n "$branch_name" $control_panel_log &>/dev/null
				then
				echo "ENTRY SUCCESSFULLY APPENDED TO $control_panel_log"
			else
				echo "FAILED TO APPEND TO $control_panel_log"
			fi


		fi


		if [ -d "$sites_directory" ]; then

			if [ ! -z "$param_domain_name" ] && [ "$param_domain_name" != "-" ]; then
	    		sublime_project_file="$sublime_projects_path/$param_domain_name.sublime-project"
	    		sublime_project=$param_domain_name
			else
	    		sublime_project_file="$sublime_projects_path/$sites_directory.sublime-project"
	    		sublime_project=$sites_directory
			fi

		echo -e "\n-------------- 12. SUBLIME PROJECT FILE ------------------"

			if [ -f $sublime_project_file ]; then
				echo "NOTHING TODO - $sublime_project_file EXISTS"
			else
				echo "CREATING INCLUDES DEV FILE $sublime_project_file"
cat <<EOF > $sublime_project_file
{
	"folders":
	[
		{
			"path": "$sites_directory"
		}
	]
}
EOF
				chmod 777 $sublime_project_file
				chown $owner_group $sublime_project_file
				echo "$sublime_project_file SUCCESSFULLY CREATED"
			fi


		echo -e "\n-------------- 13. PHP-STORM PROJECT FILE ------------------"
		if [ "$set_php_storm_proj" = "1" ]; then
			sites_phpstorm_proj="$sites_directory/.idea"
			if [ -d "$sites_phpstorm_proj" ]; then
				echo "NOTHING TODO - $sites_phpstorm_proj EXISTS"
			else
				echo "CREATING Proj Folder $sites_phpstorm_proj"
				cp -rf $php_storm_proj $sites_phpstorm_proj
				find $sites_phpstorm_proj -type f -exec sed -i '' "s/replace_withnew_project_name/$php_storm_project_name/g" {} \;
				mv "$sites_phpstorm_proj/replace_withnew_project_name.iml" "$sites_phpstorm_proj/$php_storm_project_name.iml"
				sudo chown -R "$owner_group" "$sites_phpstorm_proj"
			fi
		else
			echo "NOTHING TODO - set_php_storm_proj is NOT set to 1"
		fi


		echo -e "\n-------------- 14. COMPOSER / MAKE ------------------"
		cd "$sites_directory"
		echo "DIRECTORY CHANGED to $sites_directory"
		if [ "$repo_location" = "l" ] || [ "$repo_location" = "L" ]; then
			echo -e "\nStart Make"
			Make
			echo "Make Finished"
		elif [ "$repo_location" = "c" ] || [ "$repo_location" = "k" ] || [ "$repo_location" = "p" ]; then

			sudo bash "${SELF_PATH/.newproject.sh/.cupdate.sh}" -v

			echo "START BOWER"
			bower install --allow-root
			echo "BOWER INSTALLED"
		else
			echo "NO COMPOSER"
		fi


		else
			echo "$sites_directory DOES NOT EXIST - SKIPPING STEPS 12 & 13 (Sublime Project Creation)"
		fi

	fi
else
	echo "$sites_directory DOES NOT EXIST - PROJECT CANNOT BE SETUP"
fi

echo -e "\nCOMPLETE"

echo -e "\n"

