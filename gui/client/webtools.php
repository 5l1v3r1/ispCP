<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
 * @copyright 	2001-2006 by moleSoftware GmbH
 * @copyright 	2006-2008 by ispCP | http://isp-control.net
 * @version 	SVN: $ID$
 * @link 		http://isp-control.net
 * @author 		ispCP Team
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

include '../include/ispcp-lib.php';

check_login(__FILE__);

$tpl = new pTemplate();
$tpl->define_dynamic('page', $cfg['CLIENT_TEMPLATE_PATH'] . '/webtools.tpl');
$tpl->define_dynamic('page_message', 'page');
$tpl->define_dynamic('active_awstats', 'page');
$tpl->define_dynamic('logged_from', 'page');

$theme_color = $cfg['USER_INITIAL_THEME'];

$tpl->assign(
		array(
			'TR_CLIENT_WEBTOOLS_PAGE_TITLE' => tr('ISPCP - Client/Webtools'),
			'THEME_COLOR_PATH' => "../themes/$theme_color",
			'THEME_CHARSET' => tr('encoding'),
			'ISP_LOGO' => get_logo($_SESSION['user_id'])
		)
	);

function gen_page_awstats($tpl) {
	global $cfg;
	$awstats_act = $cfg['AWSTATS_ACTIVE'];
	if ($awstats_act != 'yes') {
		$tpl->assign('ACTIVE_AWSTATS', '');
	} else {
		$tpl->assign(
			array(
				'AWSTATS_PATH' => 'http://' . $_SESSION['user_logged'] . '/stats/',
				'AWSTATS_TARGET' => '_blank'
				)
			);
	}
}

gen_page_awstats($tpl);

/*
 *
 * static page messages.
 *
 */
gen_client_mainmenu($tpl, $cfg['CLIENT_TEMPLATE_PATH'] . '/main_menu_webtools.tpl');
gen_client_menu($tpl, $cfg['CLIENT_TEMPLATE_PATH'] . '/menu_webtools.tpl');

gen_logged_from($tpl);

check_permissions($tpl);

$tpl->assign(
		array(
			'TR_WEBTOOLS' => tr('Webtools'),
			'TR_BACKUP' => tr('Backup'),
			'TR_ERROR_PAGES' => tr('Error pages'),
			'TR_ERROR_PAGES_TEXT' => tr('Customize error pages for your domain'),
			'TR_BACKUP_TEXT' => tr('Backup and restore settings'),
			'TR_WEBMAIL_TEXT' => tr('Access your mail through the web interface'),
			'TR_FILEMANAGER_TEXT' => tr('Access your files through the web interface'),
			'TR_AWSTATS_TEXT' => tr('Access your Awstats statistics'),
			'TR_HTACCESS_TEXT' => tr('Manage protected areas, users and groups')
		)
	);

gen_page_message($tpl);

$tpl->parse('PAGE', 'page');
$tpl->prnt();

if ($cfg['DUMP_GUI_DEBUG'])
	dump_gui_debug();

unset_messages();

?>
