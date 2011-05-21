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

$template = 'mail_add.tpl';

// common page data.

if (isset($_SESSION['email_support']) && $_SESSION['email_support'] == "no") {
	header("Location: index.php");
}

// dynamic page data.

gen_page_mail_acc_props($tpl, $sql, $_SESSION['user_id']);

// static page messages.
gen_logged_from($tpl);
check_permissions($tpl);

$tpl->assign(
	array(
		'TR_PAGE_TITLE'			=> tr('ispCP - Client/Add Mail User'),
		'TR_ADD_MAIL_USER'		=> tr('Add mail users'),
		'TR_USERNAME'			=> tr('Username'),
		'TR_TO_MAIN_DOMAIN'		=> tr('To main domain'),
		'TR_TO_DMN_ALIAS'		=> tr('To domain alias'),
		'TR_TO_SUBDOMAIN'		=> tr('To subdomain'),
		'TR_TO_ALS_SUBDOMAIN'	=> tr('To alias subdomain'),
		'TR_NORMAL_MAIL'		=> tr('Normal mail'),
		'TR_PASSWORD'			=> tr('Password'),
		'TR_PASSWORD_REPEAT'	=> tr('Repeat password'),
		'TR_FORWARD_MAIL'		=> tr('Forward mail'),
		'TR_FORWARD_TO'			=> tr('Forward to'),
		'TR_FWD_HELP'			=> tr("Separate multiple email addresses with a line-break."),
		'TR_ADD'				=> tr('Add'),
		'TR_EMPTY_DATA'			=> tr('You did not fill all required fields')
	)
);

gen_client_mainmenu($tpl, 'main_menu_email_accounts.tpl');
gen_client_menu($tpl, 'menu_email_accounts.tpl');

gen_page_message($tpl);

$tpl->display($template);

if ($cfg->DUMP_GUI_DEBUG) {
	dump_gui_debug();
}

// page functions.

/**
 * @param ispCP_TemplateEngine $tpl
 * @param string $dmn_name
 * @param string $post_check
 */
function gen_page_form_data(&$tpl, $dmn_name, $post_check) {

	$cfg = ispCP_Registry::get('Config');

	$dmn_name = decode_idna($dmn_name);

	if ($post_check === 'no') {

		$tpl->assign(
			array(
				'USERNAME'				=> "",
				'DOMAIN_NAME'			=> tohtml($dmn_name),
				'MAIL_DMN_CHECKED'		=> $cfg->HTML_CHECKED,
				'MAIL_ALS_CHECKED'		=> "",
				'MAIL_SUB_CHECKED'		=> "",
				'MAIL_ALS_SUB_CHECKED'	=> "",
				'NORMAL_MAIL_CHECKED'	=> $cfg->HTML_CHECKED,
				'FORWARD_MAIL_CHECKED'	=> "",
				'FORWARD_LIST'			=> ""
			)
		);

	} else {
		if (!isset($_POST['forward_list'])) {
			$f_list = '';
		} else {
			$f_list = $_POST['forward_list'];
		}

		$tpl->assign(
			array(
				'USERNAME'				=> clean_input($_POST['username'], true),
				'DOMAIN_NAME'			=> tohtml($dmn_name),
				'MAIL_DMN_CHECKED'		=> ($_POST['dmn_type'] === 'dmn') ? $cfg->HTML_CHECKED : "",
				'MAIL_ALS_CHECKED'		=> ($_POST['dmn_type'] === 'als') ? $cfg->HTML_CHECKED : "",
				'MAIL_SUB_CHECKED'		=> ($_POST['dmn_type'] === 'sub') ? $cfg->HTML_CHECKED : "",
				'MAIL_ALS_SUB_CHECKED'	=> ($_POST['dmn_type'] === 'als_sub') ? $cfg->HTML_CHECKED : "",
				'NORMAL_MAIL_CHECKED'	=> (isset($_POST['mail_type_normal'])) ? $cfg->HTML_CHECKED : "",
				'FORWARD_MAIL_CHECKED'	=> (isset($_POST['mail_type_forward'])) ? $cfg->HTML_CHECKED : "",
				'FORWARD_LIST'			=> $f_list
			)
		);
	}
}

/**
 * @param ispCP_TemplateEngine $tpl
 * @param ispCP_Database $sql
 * @param int $dmn_id
 * @param bool $post_check
 */
function gen_dmn_als_list(&$tpl, &$sql, $dmn_id, $post_check) {

	$cfg = ispCP_Registry::get('Config');

	$ok_status = $cfg->ITEM_OK_STATUS;

	$query = "
		SELECT
			`alias_id`, `alias_name`
		FROM
			`domain_aliasses`
		WHERE
			`domain_id` = ?
		AND
			`alias_status` = ?
		ORDER BY
			`alias_name`
	";

	$rs = exec_query($sql, $query, array($dmn_id, $ok_status));
	if ($rs->recordCount() != 0) {
		$first_passed = false;
		while (!$rs->EOF) {
			if ($post_check === 'yes') {
				if (!isset($_POST['als_id'])) {
					$als_id = '';
				} else {
					$als_id = $_POST['als_id'];
				}

				if ($als_id == $rs->fields['alias_id']) {
					$als_selected = $cfg->HTML_SELECTED;
				} else {
					$als_selected = '';
				}
			} else {
				if (!$first_passed) {
					$als_selected = $cfg->HTML_SELECTED;
				} else {
					$als_selected = '';
				}
			}

			$alias_name = decode_idna($rs->fields['alias_name']);
			$tpl->append(
				array(
					'ALS_ID'		=> $rs->fields['alias_id'],
					'ALS_SELECTED'	=> $als_selected,
					'ALS_NAME'		=> tohtml($alias_name)
				)
			);
			$rs->moveNext();

			if (!$first_passed)
				$first_passed = true;
		}
	}
}

/**
 * @param ispCP_TemplateEngine $tpl
 * @param ispCP_Database $sql
 * @param int $dmn_id
 * @param string $dmn_name
 * @param string $post_check
 * @return void
 */
function gen_dmn_sub_list(&$tpl, &$sql, $dmn_id, $dmn_name, $post_check) {

	$cfg = ispCP_Registry::get('Config');

	$ok_status = $cfg->ITEM_OK_STATUS;

	$query = "
		SELECT
			`subdomain_id` AS sub_id, `subdomain_name` AS sub_name
		FROM
			`subdomain`
		WHERE
			`domain_id` = ?
		AND
			`subdomain_status` = ?
		ORDER BY
			`subdomain_name`
";

	$rs = exec_query($sql, $query, array($dmn_id, $ok_status));

	if ($rs->recordCount() != 0) {
		$first_passed = false;

		while (!$rs->EOF) {
			if ($post_check === 'yes') {
				if (!isset($_POST['sub_id'])) {
					$sub_id = '';
				} else {
					$sub_id = $_POST['sub_id'];
				}

				if ($sub_id == $rs->fields['sub_id']) {
					$sub_selected = $cfg->HTML_SELECTED;
				} else {
					$sub_selected = '';
				}
			} else {
				if (!$first_passed) {
					$sub_selected = $cfg->HTML_SELECTED;
				} else {
					$sub_selected = '';
				}
			}

			$sub_name = decode_idna($rs->fields['sub_name']);
			$dmn_name = decode_idna($dmn_name);
			$tpl->append(
				array(
					'SUB_ID'		=> $rs->fields['sub_id'],
					'SUB_SELECTED'	=> $sub_selected,
					'SUB_NAME'		=> tohtml($sub_name . '.' . $dmn_name)
				)
			);
			$rs->moveNext();

			if (!$first_passed)
				$first_passed = true;
		}
	}
}

/**
 * @param ispCP_TemplateEngine $tpl
 * @param ispCP_Database $sql
 * @param int $dmn_id
 * @param string $post_check
 */
function gen_dmn_als_sub_list(&$tpl, &$sql, $dmn_id, $post_check) {

	$cfg = ispCP_Registry::get('Config');

	$ok_status = $cfg->ITEM_OK_STATUS;

	$query = "
		SELECT
			t1.`subdomain_alias_id` AS als_sub_id,
			t1.`subdomain_alias_name` AS als_sub_name,
			t2.`alias_name` AS als_name
		FROM
			`subdomain_alias` AS t1
		LEFT JOIN (`domain_aliasses` AS t2) ON (t1.`alias_id` = t2.`alias_id`)
		WHERE
			t1.`alias_id` IN (SELECT `alias_id` FROM `domain_aliasses` WHERE `domain_id` = ?)
		AND
			t1.`subdomain_alias_status` = ?
		ORDER BY
			t1.`subdomain_alias_name`
	";

	$rs = exec_query($sql, $query, array($dmn_id, $ok_status));

	if ($rs->recordCount() != 0) {
		$first_passed = false;

		while (!$rs->EOF) {
			if ($post_check === 'yes') {
				if (!isset($_POST['als_sub_id'])) {
					$als_sub_id = '';
				} else {
					$als_sub_id = $_POST['als_sub_id'];
				}

				if ($als_sub_id == $rs->fields['als_sub_id']) {
					$als_sub_selected = $cfg->HTML_SELECTED;
				} else {
					$als_sub_selected = '';
				}
			} else {
				if (!$first_passed) {
					$als_sub_selected = $cfg->HTML_SELECTED;
				} else {
					$als_sub_selected = '';
				}
			}

			$als_sub_name = decode_idna($rs->fields['als_sub_name']);
			$als_name = decode_idna($rs->fields['als_name']);
			$tpl->append(
				array(
					'ALS_SUB_ID'		=> $rs->fields['als_sub_id'],
					'ALS_SUB_SELECTED'	=> $als_sub_selected,
					'ALS_SUB_NAME'		=> tohtml($als_sub_name . '.' . $als_name)
				)
			);
			$rs->moveNext();

			if (!$first_passed)
				$first_passed = true;
		}
	}
}

function schedule_mail_account(&$sql, $domain_id, $dmn_name, $mail_acc) {

	$cfg = ispCP_Registry::get('Config');

	$mail_auto_respond = false;
	$mail_auto_respond_text = '';
	$mail_addr = '';

	if (array_key_exists('mail_type_normal',$_POST)) {
		$mail_pass = $_POST['pass'];
		$mail_forward = '_no_';
		if ($_POST['dmn_type'] === 'dmn') {
			$mail_type[] = MT_NORMAL_MAIL;
			$sub_id = '0';
			$mail_addr = $mail_acc.'@'.$dmn_name; // the complete address
		} else if ($_POST['dmn_type'] === 'sub') {
			$mail_type[] = MT_SUBDOM_MAIL;
			$sub_id = $_POST['sub_id'];
			$mail_addr = $mail_acc.'@'.decode_idna($dmn_name); // the complete address
		} else if ($_POST['dmn_type'] === 'als_sub') {
			$mail_type[] = MT_ALSSUB_MAIL;
			$sub_id = $_POST['als_sub_id'];
			$mail_addr = $mail_acc.'@'.decode_idna($dmn_name); // the complete address
		} else if ($_POST['dmn_type'] === 'als') {
			$mail_type[] = MT_ALIAS_MAIL;
			$sub_id = $_POST['als_id'];
			$mail_addr = $mail_acc.'@'.decode_idna($dmn_name); // the complete address
		} else {
			set_page_message(tr('Unknown domain type'), 'warning');
			return false;
		}
	}

	if (array_key_exists('mail_type_forward',$_POST)) {
		if ($_POST['dmn_type'] === 'dmn') {
			$mail_type[] = MT_NORMAL_FORWARD;
			$sub_id = '0';
		} else if ($_POST['dmn_type'] === 'sub') {
			$mail_type[] = MT_SUBDOM_FORWARD;
			$sub_id = $_POST['sub_id'];
		} else if ($_POST['dmn_type'] === 'als_sub') {
			$mail_type[] = MT_ALSSUB_FORWARD;
			$sub_id = $_POST['als_sub_id'];
		} else if ($_POST['dmn_type'] === 'als') {
			$mail_type[] = MT_ALIAS_FORWARD;
			$sub_id = $_POST['als_id'];
		} else {
			set_page_message(tr('Unknown domain type'), 'warning');
			return false;
		}

		if (!isset($_POST['mail_type_normal'])) {
			$mail_pass = '_no_';
		}

		$mail_forward = $_POST['forward_list'];
		$farray = preg_split("/[\n,]+/", $mail_forward);
		$mail_accs = array();

		foreach ($farray as $value) {
			$value = trim($value);
			if (!chk_email($value) && $value !== '') {
				// @todo ERROR .. strange :) not email in this line - warning
				set_page_message(
					tr("Mailformat of an address in your forward list is incorrect!"),
					'warning'
				);
				return false;
			} else if ($value === '') {
				set_page_message(tr("Mail forward list empty!"), 'warning');
				return false;
			} else if ($mail_acc.'@'.decode_idna($dmn_name) == $value){
				set_page_message(
					tr("Forward to same address is not allowed!"),
					'warning'
				);
				return false;
			}
			$mail_accs[] = $value;
		}
		$mail_forward = implode(',', $mail_accs);
	}

	$mail_type = implode(',', $mail_type);
	list($dmn_type) = explode('_', $mail_type, 2);

	$check_acc_query = "
		SELECT
			COUNT(`mail_id`) AS cnt
		FROM
			`mail_users`
		WHERE
			`mail_acc` = ?
		AND
			`domain_id` = ?
		AND
			`sub_id` = ?
		AND
			LEFT (`mail_type`, LOCATE('_', `mail_type`)-1) = ?
	";

	$rs = exec_query($sql, $check_acc_query, array($mail_acc, $domain_id, $sub_id, $dmn_type));

	if ($rs->fields['cnt'] > 0) {
		set_page_message(tr('Mail account already exists!'), 'warning');
		return false;
	}

	if (preg_match("/^normal_mail/", $mail_type)
		|| preg_match("/^alias_mail/", $mail_type)
		|| preg_match("/^subdom_mail/", $mail_type)
		|| preg_match("/^alssub_mail/", $mail_type)) {
		$mail_pass = encrypt_db_password($mail_pass);
	}

	$query = "
		INSERT INTO `mail_users` (
			`mail_acc`,
			`mail_pass`,
			`mail_forward`,
			`domain_id`,
			`mail_type`,
			`sub_id`,
			`status`,
			`mail_auto_respond`,
			`mail_auto_respond_text`,
			`mail_addr`
		) VALUES
			(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
	";

	exec_query($sql, $query, array($mail_acc,
			$mail_pass,
			$mail_forward,
			$domain_id,
			$mail_type,
			$sub_id,
			$cfg->ITEM_ADD_STATUS,
			$mail_auto_respond,
			$mail_auto_respond_text,
			$mail_addr));

	update_reseller_c_props(get_reseller_id($domain_id));

	write_log($_SESSION['user_logged'] . ": adds new mail account: " . (!empty($mail_addr) ? $mail_addr : $mail_acc));
	set_page_message(tr('Mail account scheduled for addition!'), 'success');
	send_request();
	user_goto('mail_accounts.php');
}

function check_mail_acc_data(&$sql, $dmn_id, $dmn_name) {

	$cfg = ispCP_Registry::get('Config');

	$mail_type_normal = isset($_POST['mail_type_normal']) ? $_POST['mail_type_normal'] : false;
	$mail_type_forward = isset($_POST['mail_type_forward']) ? $_POST['mail_type_forward'] : false;

	if (($mail_type_normal == false) && ($mail_type_forward == false)) {
		set_page_message(tr('Please select at least one mail type!'), 'warning');
		return false;
	}

	if ($mail_type_normal) {
		$pass = clean_input($_POST['pass']);
		$pass_rep = clean_input($_POST['pass_rep']);
	}

	if (!isset($_POST['username']) || $_POST['username'] === '') {
		set_page_message(tr('Please enter mail account username!'), 'warning');
		return false;
	}

	$mail_acc = strtolower(clean_input($_POST['username']));
	if (ispcp_check_local_part($mail_acc) == "0") {
		set_page_message(tr("Invalid mail localpart format used!"), 'warning');
		return false;
	}

	if ($mail_type_normal) {
		if (trim($pass) === '' || trim($pass_rep) === '') {
			set_page_message(tr('Password data is missing!'), 'warning');
			return false;
		} else if ($pass !== $pass_rep) {
			set_page_message(tr('Entered passwords differ!'), 'warning');
			return false;
		} else if (!chk_password($pass, 50, "/[`\xb4'\"\\\\\x01-\x1f\015\012|<>^]/i")) {
			// Not permitted chars
			if ($cfg->PASSWD_STRONG) {
				set_page_message(
					sprintf(
						tr('The password must be at least %s chars long and contain letters and numbers to be valid.'),
						$cfg->PASSWD_CHARS
					),
					'warning'
				);
			} else {
				set_page_message(
					sprintf(
						tr('Password data is shorter than %s signs or includes not permitted signs!'),
						$cfg->PASSWD_CHARS
					),
					'warning'
				);
			}
			return false;
		}
	}


	if ($_POST['dmn_type'] === 'sub') {
		$id = 'sub_id';
		$query = '
			SELECT
				CONCAT(t1.`subdomain_name`,\'.\',t2.`domain_name`) AS name
			FROM
				`subdomain` AS t1,`domain` AS t2
			WHERE
				t1.`domain_id` = t2.`domain_id`
			AND
				t1.`subdomain_id` = ?
			AND
				t1.`domain_id` = ?
		';
		$type = tr('Subdomain');
	}

	if ($_POST['dmn_type'] === 'als_sub') {
		$id = 'als_sub_id';
		$query = '
			SELECT
				CONCAT(t1.`subdomain_alias_name`,\'.\',t2.`alias_name`) AS name
			FROM
				`subdomain_alias` AS t1
			LEFT JOIN (`domain_aliasses` AS t2) ON (t1.`alias_id` = t2.`alias_id`)
			LEFT JOIN (`domain` AS t3) ON (t2.`domain_id` = t3.`domain_id`)
			WHERE
				t1.`subdomain_alias_id` = ?
			AND
				t3.`domain_id` = ?
		';
		$type = tr('Subdomain alias');
	}

	if ($_POST['dmn_type'] === 'als') {
		$id = 'als_id';
		$query = 'SELECT `alias_name` AS name FROM `domain_aliasses` WHERE `alias_id` = ? AND `domain_id` = ?';
		$type = tr('Alias');
	}

	if (in_array($_POST['dmn_type'], array('sub', 'als_sub', 'als'))) {
		if (!isset($_POST[$id])) {
			set_page_message(
				sprintf(
					tr('%s list is empty! You cannot add mail accounts!'),
					$type
				),
				'error'
			);
			return false;
		}
		if (!is_numeric($_POST[$id])) {
			set_page_message(
				sprintf(
					tr('%s ID is invalid! You cannot add mail accounts!'),
					$type
				),
				'error'
			);
			return false;
		}
		$rs = exec_query($sql, $query, array($_POST[$id], $dmn_id));
		if ($rs->fields['name'] == '') {
			set_page_message(
				sprintf(
					tr('%s ID is invalid! You cannot add mail accounts!'),
					$type
				),
				'error'
			);
			return false;
		}
		$dmn_name=$rs->fields['name'];
	}

	if ($mail_type_forward && empty($_POST['forward_list'])) {
		set_page_message(tr('Forward list is empty!'), 'notice');
		return false;
	}

	schedule_mail_account($sql, $dmn_id, $dmn_name, $mail_acc);
}

function gen_page_mail_acc_props(&$tpl, &$sql, $user_id) {
	list($dmn_id,
		$dmn_name,,,,,,,
		$dmn_mailacc_limit,,,,,,,,,,,,,,
	) = get_domain_default_props($sql, $user_id);

	list($mail_acc_cnt,,,,) = get_domain_running_mail_acc_cnt($sql, $dmn_id);

	if ($dmn_mailacc_limit != 0 && $mail_acc_cnt >= $dmn_mailacc_limit) {
		set_page_message(tr('Mail accounts limit reached!'), 'warning');
		user_goto('mail_accounts.php');
	} else {
		$post_check = isset($_POST['uaction']) ? 'yes' : 'no';
		gen_page_form_data($tpl, $dmn_name, $post_check);
		gen_dmn_als_list($tpl, $sql, $dmn_id, $post_check);
		gen_dmn_sub_list($tpl, $sql, $dmn_id, $dmn_name, $post_check);
		gen_dmn_als_sub_list($tpl, $sql, $dmn_id, $post_check);
		if (isset($_POST['uaction']) && $_POST['uaction'] === 'add_user') {
			check_mail_acc_data($sql, $dmn_id, $dmn_name);
		}
	}
}
?>
