<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
 * @copyright	2001-2006 by moleSoftware GmbH
 * @copyright	2006-2009 by ispCP | http://isp-control.net
 * @version		SVN: $Id: protected_group_add.php 1952 2009-08-30 08:50:02Z kilburn $
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
$tpl->define_dynamic('page', Config::get('CLIENT_TEMPLATE_PATH') . '/puser_gadd.tpl');
$tpl->define_dynamic('page_message', 'page');
$tpl->define_dynamic('usr_msg', 'page');
$tpl->define_dynamic('grp_msg', 'page');
$tpl->define_dynamic('logged_from', 'page');
$tpl->define_dynamic('pusres', 'page');
$tpl->define_dynamic('pgroups', 'page');

$theme_color = Config::get('USER_INITIAL_THEME');

$tpl->assign(
	array(
		'TR_CLIENT_WEBTOOLS_PAGE_TITLE'	=> tr('ispCP - Client/Webtools'),
		'THEME_COLOR_PATH'				=> "../themes/$theme_color",
		'THEME_CHARSET'					=> tr('encoding'),
		'ISP_LOGO'						=> get_logo($_SESSION['user_id'])
	)
);

function padd_group(&$tpl, &$sql, $dmn_id) {
	if (isset($_POST['uaction']) && $_POST['uaction'] == 'add_group') {
		// we have to add the group
		if (isset($_POST['groupname'])) {
			if (!chk_username($_POST['groupname'])) {
				set_page_message(tr('Invalid group name!'));
				return;
			}

			$groupname = $_POST['groupname'];

			$query = "
				SELECT
					`id`
				FROM
					`htaccess_groups`
				WHERE
					`ugroup` = ?
				AND
					`dmn_id` = ?
			";

			$rs = exec_query($sql, $query, array($groupname, $dmn_id));

			if ($rs->RecordCount() == 0) {
				$change_status = Config::get('ITEM_ADD_STATUS');

				$query = "
					INSERT INTO `htaccess_groups`
						(`dmn_id`, `ugroup`, `status`)
					VALUES
						(?, ?, ?)
				";

				$rs = exec_query($sql, $query, array($dmn_id, $groupname, $change_status));

				send_request();

				$admin_login = $_SESSION['user_logged'];
				write_log("$admin_login: add group (protected areas): $groupname");
				user_goto('protected_user_manage.php');
			} else {
				set_page_message(tr('Group already exists!'));
				return;
			}
		} else {
			set_page_message(tr('Invalid group name!'));
			return;
		}
	} else {
		return;
	}
}

/*
 *
 * static page messages.
 *
 */

gen_client_mainmenu($tpl, Config::get('CLIENT_TEMPLATE_PATH') . '/main_menu_webtools.tpl');
gen_client_menu($tpl, Config::get('CLIENT_TEMPLATE_PATH') . '/menu_webtools.tpl');

gen_logged_from($tpl);

check_permissions($tpl);

padd_group($tpl, $sql, get_user_domain_id($sql, $_SESSION['user_id']));

$tpl->assign(
	array(
		'TR_HTACCESS'			=> tr('Protected areas'),
		'TR_ACTION'				=> tr('Action'),
		'TR_USER_MANAGE'		=> tr('Manage user'),
		'TR_USERS'				=> tr('User'),
		'TR_USERNAME'			=> tr('Username'),
		'TR_ADD_USER'			=> tr('Add user'),
		'TR_GROUPNAME'			=> tr('Group name'),
		'TR_GROUP_MEMBERS'		=> tr('Group members'),
		'TR_ADD_GROUP'			=> tr('Add group'),
		'TR_EDIT'				=> tr('Edit'),
		'TR_GROUP'				=> tr('Group'),
		'TR_DELETE'				=> tr('Delete'),
		'TR_GROUPS'				=> tr('Groups'),
		'TR_PASSWORD'			=> tr('Password'),
		'TR_PASSWORD_REPEAT'	=> tr('Repeat password'),
		'TR_CANCEL'				=> tr('Cancel'),
	)
);

gen_page_message($tpl);

$tpl->parse('PAGE', 'page');
$tpl->prnt();

if (Config::get('DUMP_GUI_DEBUG')) {
	dump_gui_debug();
}
unset_messages();