<?php
/**
 * ispCP ω (OMEGA) a Virtual Hosting Control System
 *
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
 * The Original Code is "ispCP - isp Control Panel".
 *
 * The Initial Developer of the Original Code is ispCP Team.
 * Portions created by Initial Developer are Copyright (C) 2006-2011 by
 * isp Control Panel. All Rights Reserved.
 */

require '../include/ispcp-lib.php';

check_login(__FILE__);

if (isset($_GET['edit_id']) && $_GET['edit_id'] !== '') {

	$cfg = ispCP_Registry::get('Config');

	$dns_id = (int) $_GET['edit_id'];
	$dmn_id = get_user_domain_id($sql, $_SESSION['user_id']);

	$query = "
		SELECT
			`domain_dns`.`domain_dns_id`, `domain_dns`.`domain_dns`,
			`domain_dns`.`alias_id`,
			IFNULL(`domain_aliasses`.`alias_name`, `domain`.`domain_name`) AS domain_name,
			IFNULL(`domain_aliasses`.`alias_id`, `domain_dns`.`domain_id`) AS id,
			`domain_dns`.`protected`
		FROM
			`domain_dns`
		LEFT JOIN
			`domain_aliasses` USING (`alias_id`, `domain_id`), `domain`
		WHERE
			`domain_dns`.`domain_id` = ?
		AND
			`domain_dns`.`domain_dns_id` = ?
		AND
			`domain`.`domain_id` = `domain_dns`.`domain_id`
		;
	";

	$rs = exec_query($sql, $query, array($dmn_id, $dns_id));
	$dom_name = $rs->fields['domain_name'];
	$dns_name = $rs->fields['domain_dns'];
	$id = $rs->fields['id'];
	$alias_id = $rs->fields['alias_id'];

	// DNS record not found or not owned by current customer ?
	if ($rs->recordCount() == 0) {
		// Back to the main page
		user_goto('domains_manage.php');
	} elseif($rs->fields['protected'] == 'yes') {
		set_page_message(
			tr('You are not allowed to remove this DNS record!'),
			'error'
		);
		user_goto('domains_manage.php');
	}

	// Delete DNS record from the database
	$query = "
		DELETE FROM
			`domain_dns`
		WHERE
			`domain_dns_id` = ?
		;
	";

	$rs = exec_query($sql, $query, $dns_id);

	if (empty($alias_id)) {

		$query = "
			UPDATE
				`domain`
			SET
				`domain`.`domain_status` = ?
			WHERE
   				`domain`.`domain_id` = ?
   			;
  		";

		exec_query($sql, $query, array($cfg->ITEM_DNSCHANGE_STATUS, $dmn_id));

	} else {

		$query = "
 			UPDATE
 				`domain_aliasses`
			SET
				`domain_aliasses`.`alias_status` = ?
 			WHERE
				`domain_aliasses`.`domain_id` = ?
			AND
				`domain_aliasses`.`alias_id` = ?
			;
		";

		exec_query(
			$sql, $query,
			array($cfg->ITEM_DNSCHANGE_STATUS, $dmn_id, $alias_id)
		);
	}

	// Send request to ispCP daemon
	send_request();

	write_log(
		$_SESSION['user_logged'] . ': deletes dns zone record: ' . $dns_name .
		' of domain ' . $dom_name
	);

	set_page_message(tr('Custom DNS record scheduled for deletion!'), 'success');
}

//  Back to the main page
user_goto('domains_manage.php');
