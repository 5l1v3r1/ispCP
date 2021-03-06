#!/bin/sh

# ispCP Omega helper library for distribution maintenace scripts
#
# ispCP ω (OMEGA) a Virtual Hosting Control Panel
# Copyright (C) 2006-2011 by ispCP | http://ispcp.net
# author	Laurent Declercq <laurent.declercq@ispcp.net>
# version	1.0.3
#
# SVN: $Id$
#
# The contents of this file are subject to the Mozilla Public License
# Version 1.1 (the "License"); you may not use this file except in
# compliance with the License. You may obtain a copy of the License at
# http://www.mozilla.org/MPL/
#
# Software distributed under the License is distributed on an "AS IS"
# basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
# License for the specific language governing rights and limitations
# under the License.
#
# The Original Code is "ispCP ω (OMEGA) a Virtual Hosting Control Panel".
#
# The Initial Developer of the Original Code is ispCP Team.
# Portions created by Initial Developer are Copyright (C) 2006-2011 by
# isp Control Panel. All Rights Reserved.

################################################################################
# Note to ispCP distributions. maintainers:
#
# This library provide a set of functions that can be used in your maintenance
# scripts.
#
# A few helper functions to display the titles and error messages are provided.
#
# When you include this file into your script, some ispCP configuration
# parameters obtained from the 'ispcp.conf' file are exported in your script.
#
# To use this library, you need to include it at the beginning of your script
# using the following line:
#
# . $(dirname "$0")/maintainer-helper.sh
#

################################################################################
#                      ispCP Omega configuration variables                     #
################################################################################

# Retrieving the main ispCP configuration file path
if [ -f "/etc/ispcp/ispcp.conf" ] ; then
    CONF_FILE=/etc/ispcp/ispcp.conf
	CMD_SED=`which sed`
elif [ -f "/usr/local/etc/ispcp/ispcp.conf" ] ; then
    CONF_FILE=/usr/local/etc/ispcp/ispcp.conf
	if [ -f "$(which gsed)" ]; then
		CMD_SED=`which gsed`
	else
		CMD_SED=`which sed`
	fi
else
    printf "\033[1;31m[Error]\033[0m ispCP configuration file not found!\n"
    exit 1
fi

# Retrieving the (g)sed path
# Retrieving the main ispCP configuration file path
if [ -f "$(which gsed)" ]; then
	CMD_SED=`which gsed`
elif [ -f "$(which sed)" ]; then
	CMD_SED=`which sed`
else
    printf "\033[1;31m[Error]\033[0m neither sed nor gsed found!\n"
    exit 1
fi

OLD_IFS=$IFS
IFS=$

# Reading needed entries from ispcp.conf
for a in $(grep -E '^(AMAVIS|APACHE_|BASE_SERVER_IP|CMD_|DEBUG|DATABASE_HOST|DEFAULT_ADMIN_ADDRESS|ETC_|LOG_DIR|MTA_|ROOT_|PHP_FASTCGI|SPAMASSASSIN|Version)' \
${CONF_FILE} | $CMD_SED 's/\s*=\s*\(.*\)/="\1"/') ; do
	 eval $a
done

IFS=$OLD_IFS

# Enable DEBUG mode if needed
if [ $DEBUG -eq 1 ]; then
  echo "now debugging $0 $@"
  set -x
fi

# ispCP Omega version
ISPCP_VERSION=$(echo $Version | $CMD_SED -e 's/\s\+\|[a-z]//gi')

################################################################################
#                                   Logging                                    #
################################################################################

# Log file path
LOGFILE="$LOG_DIR/setup/ispcp-$0-$1.log"

# Make sure that the log directory exists
/usr/bin/install -d $LOG_DIR/setup -m 0755 -o $ROOT_USER -g $ROOT_GROUP

# Removing old log file if it exists
$CMD_RM -f $LOGFILE

################################################################################
#                                Utils functions                               #
################################################################################

# Register shutdown function
trap "shutdown" EXIT

# Default Error message
ERROR_MESSAGE="See the $LOGFILE logfile for the reason!"

# TAB+SP+*+SP (11 bytes) + TITLE length + 1 byte
TITLE_LENGTH=12
PROGRESS_LENGTH=0

################################################################################
# Print a title
#
# Param: string Title to be printed
#
print_title() {
	TITLE_LENGTH=$(($TITLE_LENGTH+$(printf "$1" | wc -c)))
	TITLE="\t \033[1;32m*\033[0m $1"

	printf "[$1]\n" >> $LOGFILE
	printf "$TITLE";
}

################################################################################
# Print status
#
print_status() {
	if [ "$?" -eq 0 ] ; then
		STATUS="\033[1;35m[ \033[1;32mDone \033[1;35m]\033[0m"
		STATUS_LENGTH=8
	else
		STATUS="\033[1;35m[ \033[1;31mFailed \033[1;35m]\033[0m"
		STATUS_LENGTH=10
	fi

	# Getting terminal width
	TERM_WIDTH=$(stty size | cut -d' ' -f2)

	# Calculating separator size
	SEP=$(($TERM_WIDTH-($TITLE_LENGTH+$STATUS_LENGTH+$PROGRESS_LENGTH)))

	printf "%$(($SEP))s$STATUS\n"

	# Reset default length
	TITLE_LENGTH=12
	PROGRESS_LENGTH=0
}

################################################################################
# Print progress
#
progress() {
    printf '.'
	PROGRESS_LENGTH=$(($PROGRESS_LENGTH+1))
}

################################################################################
# Exit with an error message
#
# [param: string Error message that override the default one]
#
failed() {
	print_status

	if ! test -z "$1" ; then
		ERROR_MESSAGE=$1
	fi

	printf "\n\t \033[1;31m[ERROR]\033[0m $ERROR_MESSAGE\n"

	exit 1
}

################################################################################
# Shutdown function
#
shutdown() {
	if test -z "$SEP" ; then
		print_title "Nothing to do..."
		print_status
	fi
}
