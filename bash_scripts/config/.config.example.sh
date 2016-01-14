#!/bin/sh
user="{MAC_USERNAME}"
svn_username="{SVN_USERNAME}"
SUBUSER_REPLACE="{LIMBO_USERNAME}"

cc_base_directory="/usr/local/etc"
vhost_path="/etc/apache2/other"
hosts_file="/etc/hosts"

svn_url="http://svn.worldstores.co.uk"
svn_path="/usr/local/bin/svn"

use_git=0
git_url="git@bitbucket.org:worldstores"
git_path="/usr/bin/git"

###Local File structure.
sites_base_directory="/Users/$user/Sites"
#Dir location for sitebase
sites_base_directory_s="/Users/$user/Sites"
#Dir location for SKU
sites_base_directory_k="/Users/$user/Sites"
#Dir location for CentralAdmin
sites_base_directory_c="/Users/$user/Sites"
#Dir location for PrivateSales (Casafina)
sites_base_directory_p="/Users/$user/Sites"
#Dir location for Logistics API
sites_base_directory_l="/Users/$user/Sites"
#Dir location for TMO
sites_base_directory_t="/Users/$user/Sites"

sublime_projects_path="/Users/$user/Documents/sublime_projects"

control_panel_log_dir="/Users/$user/Sites/dev_control_panel/logs"
control_panel_log_filename="devsites.sitebase.log"
control_panel_log="$control_panel_log_dir/$control_panel_log_filename"

db_user="root"
db_pass="optelligence"
db_host="127.0.0.1"
default_database_host="localhost"
db_conn="-h$db_host -u$db_user -p$db_pass"

###This file conatins the max port number and is used to assing distinct port for each new site
##Create this file with a starting port number
port_count_file="/usr/local/etc/port_count"

###File Dependencies
# orig_sitebase_login_file="path/to/your/sitebase_login.php" #Uncomment to override. Set it to your sitebase_login.php if you dont want to include the one included in this project
set_php_storm_proj=0 #set to 1 if you are using PhpStorm

###These get included from include folder. Only set these if you want to override with your own files
# flaturl_path="/etc/apache2/extra/flaturl.conf"
# php_storm_proj="/Users/$user/Sites/common/.idea"