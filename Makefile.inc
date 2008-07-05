#!/usr/bin/make -f
#
# ispCP ω (OMEGA) a Virtual Hosting Control Panel
# Copyright (c) 2001-2006 by moleSoftware GmbH
# http://www.molesoftware.com
# Copyright (c) 2006-2008 by isp Control Panel
# http://isp-control.net
#
#
# License:
#    This program is free software; you can redistribute it and/or
#    modify it under the terms of the MPL Mozilla Public License
#    as published by the Free Software Foundation; either version 1.1
#    of the License, or (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    MPL Mozilla Public License for more details.
#
#    You may have received a copy of the MPL Mozilla Public License
#    along with this program.
#
#    An on-line copy of the MPL Mozilla Public License can be found
#    http://www.mozilla.org/MPL/MPL-1.1.html
#
#
# The ispCP ω Home Page is at:
#
#    http://isp-control.net
#
ifndef INST_PREF
        INST_PREF=/tmp/ispcp
endif

HOST_OS=openbsd

ROOT_CONF=$(INST_PREF)/etc

SYSTEM_ROOT=$(INST_PREF)/var/www/ispcp
SYSTEM_CONF=$(INST_PREF)/etc/ispcp
SYSTEM_LOG=$(INST_PREF)/var/log/ispcp
SYSTEM_APACHE_BACK_LOG=$(INST_PREF)/var/www/log/backup
SYSTEM_VIRTUAL=$(INST_PREF)/var/www/virtual
SYSTEM_AWSTATS=$(INST_PREF)/var/www/awstats
SYSTEM_FCGI=$(INST_PREF)/var/www/fcgi
SYSTEM_MAIL_VIRTUAL=$(INST_PREF)/var/mail/virtual
SYSTEM_MAKE_DIRS=/bin/mkdir -p
SYSTEM_MAKE_FILE=/bin/touch

.PHONY: install uninstall clean