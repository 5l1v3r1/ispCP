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
$template = 'alias_edit.tpl';

// "Modify" button has been pressed
if (isset($_POST['uaction']) && ('modify' === $_POST['uaction'])) {
	if (isset($_SESSION['edit_ID'])) {
		$editid = $_SESSION['edit_ID'];
	} else if (isset($_GET['edit_id'])) {
		$editid = $_GET['edit_id'];
	} else {
		unset($_SESSION['edit_ID']);

		$_SESSION['aledit'] = '_no_';
		user_goto('alias.php');
	}
	// Save data to db
	if (check_fwd_data($tpl, $editid)) {
		$_SESSION['aledit'] = "_yes_";
		user_goto('alias.php');
	}
} else {
	// Get user id that comes for edit
	if (isset($_GET['edit_id'])) {
		$editid = $_GET['edit_id'];
	}

	$_SESSION['edit_ID'] = $editid;
	$tpl->assign('PAGE_MESSAGE', "");
}

// static page messages
gen_logged_from($tpl);

gen_editalias_page($tpl, $editid);

$tpl->assign(
	array(
		'TR_PAGE_TITLE' => tr('ispCP - Manage Domain Alias/Edit Alias'),
		'TR_MANAGE_DOMAIN_ALIAS' => tr('Manage domain alias'),
		'TR_EDIT_ALIAS' => tr('Edit domain alias'),
		'TR_ALIAS_NAME' => tr('Alias name'),
		'TR_DOMAIN_IP' => tr('Domain IP'),
		'TR_FORWARD' => tr('Forward to URL'),
		'TR_MODIFY' => tr('Modify'),
		'TR_CANCEL' => tr('Cancel'),
		'TR_ENABLE_FWD' => tr("Enable Forward"),
		'TR_ENABLE' => tr("Enable"),
		'TR_DISABLE' => tr("Disable"),
		'TR_DISABLE' => tr("Disable"),
		'TR_PREFIX_HTTP' => 'http://',
		'TR_PREFIX_HTTPS' => 'https://',
		'TR_PREFIX_FTP' => 'ftp://'
	)
);

gen_reseller_mainmenu($tpl, 'main_menu_users_manage.tpl');
gen_reseller_menu($tpl, 'menu_users_manage.tpl');

if ($cfg->DUMP_GUI_DEBUG) {
	dump_gui_debug($tpl);
}

$tpl->display($template);

unset_messages();

// Begin function block

/**
 * Show user data
 * @param ispCP_TemplateEngine $tpl
 * @param int $edit_id
 */
function gen_editalias_page($tpl, $edit_id) {
	$sql = ispCP_Registry::get('Db');
	$cfg = ispCP_Registry::get('Config');

	$reseller_id = $_SESSION['user_id'];

	$query = "
		SELECT
			t1.`domain_id`,
			t1.`alias_id`,
			t1.`alias_name`,
			t2.`domain_id`,
			t2.`domain_created_id`
		FROM
			`domain_aliasses` AS t1,
			`domain` AS t2
		WHERE
			t1.`alias_id` = ?
		AND
			t1.`domain_id` = t2.`domain_id`
		AND
			t2.`domain_created_id` = ?
	";

	$rs = exec_query($sql, $query, array($edit_id, $reseller_id));

	if ($rs->recordCount() == 0) {
		set_page_message(
			tr('User does not exist or you do not have permission to access this interface!'),
			'error'
		);
		user_goto('alias.php');
	}
	// Get data from sql
	$res = exec_query($sql, "SELECT * FROM `domain_aliasses` WHERE `alias_id` = ?", $edit_id);

	if ($res->recordCount() <= 0) {
		$_SESSION['aledit'] = '_no_';
		user_goto('alias.php');
	}
	$data = $res->fetchRow();
	// Get IP data
	$ipres = exec_query($sql, "SELECT * FROM `server_ips` WHERE `ip_id` = ?", $data['alias_ip_id']);
	$ipdat = $ipres->fetchRow();
	$ip_data = $ipdat['ip_number'] . ' (' . $ipdat['ip_alias'] . ')';

	if (isset($_POST['uaction']) && ($_POST['uaction'] == 'modify')) {
		$url_forward = strtolower(clean_input($_POST['forward']));
	} else {
		$url_forward = decode_idna(preg_replace("(ftp://|https://|http://)", "", $data['url_forward']));

		if ($data["url_forward"] == "no") {
			$check_en = '';
			$check_dis = $cfg->HTML_CHECKED;
			$url_forward = '';
			$tpl->assign(
				array(
					'READONLY_FORWARD'	=>	$cfg->HTML_READONLY,
					'DISABLE_FORWARD'	=>	$cfg->HTML_DISABLED,
					'HTTP_YES'			=>	'',
					'HTTPS_YES'			=>	'',
					'FTP_YES'			=>	''
				)
			);
		} else {
			$check_en = $cfg->HTML_CHECKED;
			$check_dis = '';
			$tpl->assign(
				array(
					'READONLY_FORWARD' => '',
					'DISABLE_FORWARD' => '',
					'HTTP_YES' => (preg_match("/http:\/\//", $data['url_forward'])) ? $cfg->HTML_SELECTED : '',
					'HTTPS_YES' => (preg_match("/https:\/\//", $data['url_forward'])) ? $cfg->HTML_SELECTED : '',
					'FTP_YES' => (preg_match("/ftp:\/\//", $data['url_forward'])) ? $cfg->HTML_SELECTED : ''
				)
			);
		}
		$tpl->assign(
				array(
					'CHECK_EN' => $check_en,
					'CHECK_DIS' => $check_dis
				)
			);
	}
	// Fill in the fields
	$tpl->assign(
		array(
			'ALIAS_NAME' => tohtml(decode_idna($data['alias_name'])),
			'DOMAIN_IP' => $ip_data,
			'FORWARD' => tohtml($url_forward),
			'ID' => $edit_id
		)
	);
} // End of gen_editalias_page()

/**
 * Check input data
 * @param ispCP_TemplateEngine $tpl
 * @param int $alias_id
 */
function check_fwd_data($tpl, $alias_id) {

	$sql = ispCP_Registry::get('Db');
	$cfg = ispCP_Registry::get('Config');

	$forward_url = strtolower(clean_input($_POST['forward']));
	// unset errors
	$ed_error = '_off_';
	// NXW: Unused variable so...
	// $admin_login = '';

	if (isset($_POST['status']) && $_POST['status'] == 1) {
		$forward_prefix = clean_input($_POST['forward_prefix']);
		if (substr_count($forward_url, '.') <= 2) {
			$ret = validates_dname($forward_url);
		} else {
			$ret = validates_dname($forward_url, true);
		}
		if (!$ret) {
			$ed_error = tr("Wrong domain part in forward URL!");
		} else {
			$forward_url = encode_idna($forward_prefix.$forward_url);
		}

		$check_en = $cfg->HTML_CHECKED;
		$check_dis = '';
		$tpl->assign(
			array(
				'FORWARD'			=> tohtml($forward_url),
				'HTTP_YES'			=> ($forward_prefix === 'http://') ? $cfg->HTML_SELECTED : '',
				'HTTPS_YES'			=> ($forward_prefix === 'https://') ? $cfg->HTML_SELECTED : '',
				'FTP_YES'			=> ($forward_prefix === 'ftp://') ? $cfg->HTML_SELECTED : '',
				'CHECK_EN'			=> $check_en,
				'CHECK_DIS'			=> $check_dis,
				'DISABLE_FORWARD'	=>	'',
				'READONLY_FORWARD'	=>	''
			)
		);
	} else {
		$check_en = $cfg->HTML_CHECKED;
		$check_dis = '';
		$forward_url = 'no';
		$tpl->assign(
			array(
				'READONLY_FORWARD' => $cfg->HTML_READONLY,
				'DISABLE_FORWARD' => $cfg->HTML_DISABLED,
				'CHECK_EN' => $check_en,
				'CHECK_DIS' => $check_dis,
			)
		);
	}

	if ($ed_error === '_off_') {
		$query = "
			UPDATE
				`domain_aliasses`
			SET
				`url_forward` = ?,
				`alias_status` = ?
			WHERE
				`alias_id` = ?
		";
		exec_query($sql, $query, array($forward_url, $cfg->ITEM_CHANGE_STATUS, $alias_id));

		$query = "
			UPDATE
				`subdomain_alias`
			SET
				`subdomain_alias_status` = ?
			WHERE
				`alias_id` = ?
		";
		exec_query($sql, $query, array($cfg->ITEM_CHANGE_STATUS, $alias_id));

		send_request();

		// NXW: oh my god... Should be review...
		/*
		$admin_login = $_SESSION['user_logged'];
		write_log("$admin_login: changes domain alias forward: " . $rs->fields['t1.alias_name']);
		*/
		unset($_SESSION['edit_ID']);
		$tpl->assign('MESSAGE', "");
		return true;
	} else {
		$tpl->assign('MESSAGE', $ed_error);
		return false;
	}
} // End of check_user_data()
?>