<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
 * @copyright 	2001-2006 by moleSoftware GmbH
 * @copyright 	2006-2011 by ispCP | http://isp-control.net
 * @version 	SVN: $Id$
 * @link 		http://isp-control.net
 * @author 		ispCP Team
 *
 * @license
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "VHCS - Virtual Hosting Control System".
 *
 * The Initial Developer of the Original Code is moleSoftware GmbH.
 * Portions created by Initial Developer are Copyright (C) 2001-2006
 * by moleSoftware GmbH. All Rights Reserved.
 * Portions created by the ispCP Team are Copyright (C) 2006-2011 by
 * isp Control Panel. All Rights Reserved.
 */

require '../include/ispcp-lib.php';

check_login(__FILE__);

$cfg = ispCP_Registry::get('Config');

$tpl = ispCP_TemplateEngine::getInstance();
$template = 'ticket_system.tpl';

// dynamic page data

$reseller_id = $_SESSION['user_created_by'];

if (!hasTicketSystem($reseller_id)) {
	user_goto('index.php');
}
if (isset($_GET['psi'])) {
	$start = $_GET['psi'];
} else {
	$start = 0;
}

generateTicketList($tpl, $_SESSION['user_id'], $start,
		$cfg->DOMAIN_ROWS_PER_PAGE, 'client', 'open');

// static page messages
gen_logged_from($tpl);

check_permissions($tpl);

$tpl->assign(
	array(
		'TR_PAGE_TITLE'		=> tr('ispCP - Client/Questions & Comments'),
		'TR_SUPPORT_SYSTEM'	=> tr('Support system'),
		'TR_SUPPORT_TICKETS'	=> tr('Support tickets'),
		'TR_STATUS'		=> tr('Status'),
		'TR_NEW'		=> ' ',
		'TR_ACTION'		=> tr('Action'),
		'TR_URGENCY'		=> tr('Priority'),
		'TR_SUBJECT'		=> tr('Subject'),
		'TR_LAST_DATA'		=> tr('Last reply'),
		'TR_DELETE_ALL'		=> tr('Delete all'),
		'TR_OPEN_TICKETS'	=> tr('Open tickets'),
		'TR_CLOSED_TICKETS'	=> tr('Closed tickets'),
		'TR_DELETE'		=> tr('Delete'),
		'TR_MESSAGE_DELETE'	=> tr('Are you sure you want to delete %s?', true, '%s'),
		'TR_EDIT'		=> tr('Edit')
	)
);

gen_client_mainmenu($tpl, 'main_menu_ticket_system.tpl');
gen_client_menu($tpl, 'menu_ticket_system.tpl');

gen_page_message($tpl);

if ($cfg->DUMP_GUI_DEBUG) {
	dump_gui_debug($tpl);
}

$tpl->display($template);

unset_messages();
?>