<?php
require '../include/ispcp-lib.php';

check_login(__FILE__);

if (isset($_GET['id']) || isset($_POST['id'])) {
	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$software_id = $_GET['id'];
	} elseif (isset($_POST['id']) && is_numeric($_POST['id'])) {
		$software_id = $_POST['id'];
	} else {
		set_page_message(tr('Wrong software id.'));
		header('Location: software_manage.php');
	}
	
	if(isset($_POST['change']) && $_POST['change'] == "add"){
		$reseller_id = $_POST['selected_reseller'];
		$user_id = $_SESSION['user_id'];
		$query = "SELECT * FROM `web_software` WHERE `software_id` = ?";
		$rs = exec_query($sql, $query, array($software_id));
		$query=<<<SQL_QUERY
				INSERT INTO
					`web_software`
				(
					`software_master_id`, `reseller_id`, `software_name`, 
					`software_version`, `software_language`, `software_type`,
					`software_db`, `software_archive`, `software_installfile`,
					`software_prefix`, `software_link`, `software_desc`,
					`software_active`, `software_status`, `rights_add_by`,
					`software_depot`
				) VALUES (
					?, ?, ?, 
					?, ?, ?, 
					?, ?, ?, 
					?, ?, ?, 
					?, ?, ?,
					?
				)
SQL_QUERY;
		if($reseller_id == "all"){
			$query2 = "SELECT 
						`reseller_id`
					FROM 
						`reseller_props`
					WHERE
						`software_allowed` = 'yes'
					AND
						`softwaredepot_allowed` = 'yes'";
			$rs2 = exec_query($sql, $query2, array());
			if ($rs2->RecordCount() > 0){
				while(!$rs2->EOF) {
					$query3 = "SELECT 
									`reseller_id`
								FROM 
									`web_software`
								WHERE
									`reseller_id` = ?
								AND 
									`software_master_id` = ?";
					$rs3 = exec_query($sql, $query3, array($rs2->fields['reseller_id'],$software_id));
					if ($rs3->RecordCount() === 0){
						exec_query($sql, $query, array($software_id, $rs2->fields['reseller_id'], $rs->fields['software_name'], $rs->fields['software_version'], $rs->fields['software_language'], $rs->fields['software_type'], $rs->fields['software_db'], $rs->fields['software_archive'], $rs->fields['software_installfile'], $rs->fields['software_prefix'], $rs->fields['software_link'], $rs->fields['software_desc'], $rs->fields['software_active'], "ok", $user_id, "yes"));
					}
					$rs2->MoveNext();
				}
			}else{
				set_page_message(tr('No Resellers found.'));
				header('Location: software_rights.php?id='.$software_id);
			}
		}else{
			exec_query($sql, $query, array($software_id, $reseller_id, $rs->fields['software_name'], $rs->fields['software_version'], $rs->fields['software_language'], $rs->fields['software_type'], $rs->fields['software_db'], $rs->fields['software_archive'], $rs->fields['software_installfile'], $rs->fields['software_prefix'], $rs->fields['software_link'], $rs->fields['software_desc'], $rs->fields['software_active'], "ok", $user_id, "yes"));
		}
		set_page_message(tr('Rights succesfully added.'));
		header('Location: software_rights.php?id='.$software_id);
	} else {
		$reseller_id = $_GET['reseller_id'];
		$delete = "DELETE FROM `web_software` WHERE `software_master_id` = ? AND reseller_id = ?";
		$update = "UPDATE `web_software_inst` SET `software_res_del` = 1 WHERE `software_master_id` = ?";
		exec_query($sql, $delete, array($software_id, $reseller_id));
		exec_query($sql, $update, array($software_id));
		set_page_message(tr('Rights succesfully removed.'));
		header('Location: software_rights.php?id='.$software_id);
	}
} else {
	set_page_message(tr('Wrong software id.'));
	header('Location: software_manage.php');
}
?>