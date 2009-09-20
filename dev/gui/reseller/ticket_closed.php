<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
 * @copyright	2001-2006 by moleSoftware GmbH
 * @copyright	2006-2009 by ispCP | http://isp-control.net
 * @version		SVN: $Id: ticket_closed.php 1744 2009-05-07 03:21:47Z haeber $
 * @link		http://isp-control.net
 * @author		ispCP Team
 *
 * @license
 *   This program is free software; you can redistribute it and/or modify it under
 *   the terms of the MPL General Public License as published by the Free Software
 *   Foundation; either version 1.1 of the License, or (at your option) any later
 *   version.
 *   You should have received a copy of the MPL Mozilla Public License along with
 *   this program; if not, write to the Open Source Initiative (OSI)
 *   http://opensource.org | osi@opensource.org
 */

require '../include/ispcp-lib.php';

check_login(__FILE__);

$tpl = new pTemplate();
$tpl->define_dynamic('page', Config::get('RESELLER_TEMPLATE_PATH') . '/ticket_closed.tpl');
$tpl->define_dynamic('page_message', 'page');
$tpl->define_dynamic('logged_from', 'page');
$tpl->define_dynamic('tickets_list', 'page');
$tpl->define_dynamic('tickets_item', 'tickets_list');
$tpl->define_dynamic('scroll_prev_gray', 'page');
$tpl->define_dynamic('scroll_prev', 'page');
$tpl->define_dynamic('scroll_next_gray', 'page');
$tpl->define_dynamic('scroll_next', 'page');

// page functions.
function gen_tickets_list(&$tpl, &$sql, $user_id) {
	$start_index = 0;

	$rows_per_page = Config::get('DOMAIN_ROWS_PER_PAGE');

	if (isset($_GET['psi'])) $start_index = $_GET['psi'];

	$count_query = <<<SQL_QUERY
		SELECT
			COUNT(`ticket_id`) AS cnt
		FROM
			`tickets`
		WHERE
			(`ticket_from` = ? OR `ticket_to` = ?)
		AND
			`ticket_status` = 0
		AND
			`ticket_reply` = 0
SQL_QUERY;

	$rs = exec_query($sql, $count_query, array($user_id, $user_id));
	$records_count = $rs->fields['cnt'];

	$query = <<<SQL_QUERY
		SELECT
			`ticket_id`,
			`ticket_status`,
			`ticket_urgency`,
			`ticket_date`,
			`ticket_subject`,
			`ticket_message`
		FROM
			`tickets`
		WHERE
			(`ticket_from` = ? OR `ticket_to` = ?)
		AND
			`ticket_status` = 0
		AND
			`ticket_reply` = 0
		ORDER BY
			`ticket_date` DESC
		LIMIT
			$start_index, $rows_per_page
SQL_QUERY;

	$rs = exec_query($sql, $query, array($user_id, $user_id));

	if ($rs->RecordCount() == 0) {
		$tpl->assign(
			array(
				'TICKETS_LIST' => '',
				'SCROLL_PREV' => '',
				'SCROLL_NEXT' => ''
			)
		);

		set_page_message(tr('You have no support tickets.'));
	} else {
		$prev_si = $start_index - $rows_per_page;

		if ($start_index == 0) {
			$tpl->assign('SCROLL_PREV', '');
		} else {
			$tpl->assign(
				array(
					'SCROLL_PREV_GRAY' => '',
					'PREV_PSI' => $prev_si
				)
			);
		}

		$next_si = $start_index + $rows_per_page;

		if ($next_si + 1 > $records_count) {
			$tpl->assign('SCROLL_NEXT', '');
		} else {
			$tpl->assign(
				array(
					'SCROLL_NEXT_GRAY' => '',
					'NEXT_PSI' => $next_si
				)
			);
		}

		global $i;

		while (!$rs->EOF) {
			$ticket_id		= $rs->fields['ticket_id'];
			$ticket_urgency = $rs->fields['ticket_urgency'];
			$ticket_status	= $rs->fields['ticket_status'];
			$date			= ticketGetLastDate($sql, $ticket_id);

			$tpl->assign(array('URGENCY' => get_ticket_urgency($ticket_urgency)));

			$tpl->assign(array('NEW' => " "));

			$tpl->assign(
				array(
					'LAST_DATE'	=> $date,
					'SUBJECT'	=> htmlspecialchars($rs->fields['ticket_subject']),
					'SUBJECT2'	=> addslashes(clean_html($rs->fields['ticket_subject'])),
					'MESSAGE'	=> htmlspecialchars($rs->fields['ticket_message']),
					'ID'		=> $ticket_id,
					'CONTENT'	=> ($i % 2 == 0) ? 'content' : 'content2'
				)
			);

			$tpl->parse('TICKETS_ITEM', '.tickets_item');
			$rs->MoveNext();
			$i++;
		}
	}
}

// common page data.

if (!Config::get('ISPCP_SUPPORT_SYSTEM')) {
	user_goto('index.php');
}

$theme_color = Config::get('USER_INITIAL_THEME');

$tpl->assign(
	array(
		'TR_CLIENT_QUESTION_PAGE_TITLE' => tr('ispCP - Client/Questions & Comments'),
		'THEME_COLOR_PATH' => "../themes/$theme_color",
		'THEME_CHARSET' => tr('encoding'),
		'ISP_LOGO' => get_logo($_SESSION['user_id']),
	)
);

gen_tickets_list($tpl, $sql, $_SESSION['user_id']);

// static page messages.

gen_reseller_mainmenu($tpl, Config::get('RESELLER_TEMPLATE_PATH') . '/main_menu_ticket_system.tpl');
gen_reseller_menu($tpl, Config::get('RESELLER_TEMPLATE_PATH') . '/menu_ticket_system.tpl');

gen_logged_from($tpl);

$tpl->assign(
	array(
		'TR_SUPPORT_SYSTEM' => tr('Support system'),
		'TR_SUPPORT_TICKETS' => tr('Support tickets'),
		'TR_STATUS' => tr('Status'),
		'TR_NEW' => ' ',
		'TR_ACTION' => tr('Action'),
		'TR_URGENCY' => tr('Priority'),
		'TR_SUBJECT' => tr('Subject'),
		'TR_LAST_DATA' => tr('Last reply'),
		'TR_DELETE_ALL' => tr('Delete all'),
		'TR_OPEN_TICKETS' => tr('Open tickets'),
		'TR_CLOSED_TICKETS' => tr('Closed tickets'),
		'TR_DELETE' => tr('Delete'),
		'TR_MESSAGE_DELETE' => tr('Are you sure you want to delete %s?', true, '%s'),
	)
);

gen_page_message($tpl);

$tpl->parse('PAGE', 'page');
$tpl->prnt();

if (Config::get('DUMP_GUI_DEBUG')) {
	dump_gui_debug();
}
unset_messages();