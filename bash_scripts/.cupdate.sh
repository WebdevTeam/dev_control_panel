#!/bin/bash
##title           :cupdate.sh
##description     :This script will create update composer with specific user access
##author      	  :Dave Ward
##date            :2014-05-06
##last revised    :2014-05-06
##usage           :sh .newproject.sh
##notes           :
#                 :
##bash_version    :4.2.24(1)-release (x86_64-pc-linux-gnu)
##==============================================================================

#CONFIG SECTION ------------------------------------

# Get exact path of symlink
SELF_PATH=$(cd -P -- "$(dirname -- "$0")" && pwd -P) && SELF_PATH=$SELF_PATH/$(basename -- "$0")
CONFIG_FILE="config/.cupdate_config.sh"
#From  current path of the symlink, replace this file with config file. This will create full path to config file
SOURCE_FILE="${SELF_PATH/.cupdate.sh/${CONFIG_FILE}}"

#You should create the config file config/.cupdate_config.sh. Please look at config/.cupdate_config.example.sh
if  [ -f $SOURCE_FILE ] ; then
	source "${SOURCE_FILE}"
else
	echo -e "No config file found. Please create /config/.cupdate_config.sh\nPlease look at config/.cupdate_config.example.sh for an example"
	exit
fi
#END CONFIG SECTION ------------------------------------

#There should not be any need to edit this file


if [ -z $password ]; then
	privkey_file_string="$privkey_file"
else
	privkey_file_string="$privkey_file\",\"password\" : \"$password"
fi

#END CONFIG SECTION ------------------------------------


usage="\n--------------Worldstores Composer Update--------------\n\n"
usage+="USAGE: .cupdate.sh [-r repository url] [-h] [-v] [-f] [-u]

where:
    -h  :  show this help text
    -v  :  Verbose output mode
    -f  :  set the forced flag -> no update confirmation required
    -u  :  Perform selfupdate for Composer
    -r  :  set the repository url which points to the package manager for composer to connect to via ssh2.sftp [default: pacman.worldstores.co.uk]
    "
#usage+="\nNOTE: This script must be run as root"
usage+="\n\n--------------end of help--------------\n"

verbose=false
forced=false
selfupdate=false

while getopts ':hvfur:' option; do
  case "$option" in
    h) echo "$usage"
       exit
       ;;
    r) repository_url=$OPTARG
       ;;
    v) verbose=true
       ;;
    f) forced=true
       ;;
    u) selfupdate=true
       ;;
    :) printf "missing argument for -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit 1
       ;;
   \?) printf "illegal option: -%s\n" "$OPTARG" >&2
       echo "$usage" >&2
       exit 1
       ;;
  esac
done
shift $((OPTIND - 1))

if [ -z $repository_url ]; then
	repository_url=$default_repository_url

	if [ $verbose == true ]; then
		echo "\nNOTICE: Domain name not specified - using default: $repository_url"
	fi
fi

#SHOULDN'T BE ANY NEED TO EDIT BELOW HERE ------------------------------------

#DO SOME CHECKS BEFORE SCRIPT IS RUN
# Ensure composer.json file exists in current directory.

working_directory=$(pwd)
if [ ! -f "composer.json" ]; then
	echo "ERROR: composer.json file does not exist in $working_directory"
	echo "EXITING"
	exit
fi


echo "\nSTARTING ------------->>>>>"

	if [ $selfupdate == true ]; then
		echo "\n>>------------ COMPOSER SELF-UPDATE ------------------\n"
		echo "Running composer self-update........."
		composer self-update
		echo "\n<<------------- EOF COMPOSER SELF-UPDATE ------------------\n"
	fi

	sed -i.bak "s/$master_repository_url/$repository_url/g" composer.json
	sed -i '' "s/\"username\": \"composer\"/\"username\": \"$username\"/g" composer.json
	sed -i '' "s/\/home\/composer\/.ssh\/id_rsa.pub/$pubkey_file/g" composer.json
	sed -i '' "s/\/home\/composer\/.ssh\/id_rsa/$privkey_file_string/g" composer.json

	if [ $verbose == true ]; then
		echo ">>------------ SWITCHING COMPOSER PARAMETERS ------------------\n"

		echo "Switched Repository URL: $master_repository_url >> $repository_url"
		echo "Switched Repository username: composer >> $username"
		echo "Switched Repository pubkey_file: /home/composer/.ssh/id_rsa.pub >> $pubkey_file"
		echo "Switched Repository privkey_file: /home/composer/.ssh/id_rsa >> $privkey_file_string"

		echo "\n<<------------ EOF SWITCHING COMPOSER PARAMETERS ------------------\n"
	fi

	echo "\n>>------------ COMPOSER UPDATE  ------------------\n"

	if [ $verbose == true ]; then

		echo "\n>>>------------ COMPOSER FILE PREVIEW ------------------\n"
		cat composer.json
		echo "\n<<<------------ EOF COMPOSER FILE PREVIEW ------------------\n"

		if [ $forced == true ]; then
			composer update -vvv -n
		else
			composer update -vvv
		fi
	else
		if [ $forced == true ]; then
			composer update -v -n
		else
			composer update -v	
		fi
	fi

	echo "\n<<------------ EOF COMPOSER UPDATE  ------------------\n"

	rm composer.json
	mv composer.json.bak composer.json

	if [ $verbose == true ]; then
		echo ">>------------ REVERTING COMPOSER.JSON ------------------\n"

		echo "Moved composer.json.bak >> composer.json"

		echo "\n<<------------ EOF REVERTING COMPOSER.JSON ------------------\n"
	fi

	if [ $verbose == true ]; then
		echo "\n>>>------------ COMPOSER FILE PREVIEW ------------------\n"
		cat composer.json
		echo "\n<<<------------ EOF COMPOSER FILE PREVIEW ------------------\n"
	fi


echo "\n--------------------------------------------------------------------------------------------------------"
echo "COMPOSER SUCCESSFULLY UPDATED FOR $working_directory"
echo "--------------------------------------------------------------------------------------------------------"

echo "\nCOMPLETE -------------<<<<<\n"
