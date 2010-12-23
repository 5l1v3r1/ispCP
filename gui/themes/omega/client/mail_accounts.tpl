<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title>{TR_CLIENT_MANAGE_USERS_PAGE_TITLE}</title>
	<meta http-equiv='Content-Script-Type' content='text/javascript' />
	<meta http-equiv='Content-Style-Type' content='text/css' />
	<meta http-equiv='Content-Type' content='text/html; charset={THEME_CHARSET}' />
	<meta name='copyright' content='ispCP Omega' />
	<meta name='owner' content='ispCP Omega' />
	<meta name='publisher' content='ispCP Omega' />
	<meta name='robots' content='nofollow, noindex' />
	<meta name='title' content='{TR_CLIENT_MANAGE_USERS_PAGE_TITLE}' />
	<link href="{THEME_COLOR_PATH}/css/ispcp.css" rel="stylesheet" type="text/css" />
	<link href="{THEME_COLOR_PATH}/css/jquery.tablesorter.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="{THEME_SCRIPT_PATH}/jquery.js"></script>
	<script type="text/javascript" src="{THEME_SCRIPT_PATH}/jquery.tablesorter.js"></script>
	<!--[if lt IE 7.]>
		<script defer type="text/javascript" src="{THEME_SCRIPT_PATH}/pngfix.js"></script>
	<![endif]-->
	<script type="text/javascript">
	/* <![CDATA[ */
		$(document).ready(function(){
			// TableSorter - begin
			$('.tablesorter').tablesorter({cssHeader: 'tablesorter'});
			// TableSorter - end
		});

		function action_delete(url, subject) {
			if (!confirm(sprintf("{TR_MESSAGE_DELETE}", subject)))
				return false;
			location = url;
		}
	/* ]]> */
	</script>
</head>
<body>
	<div class="header">
		{MAIN_MENU}
		<div class="logo">
			<img src="{THEME_COLOR_PATH}/images/ispcp_logo.png" alt="ispCP Omega logo" />
			<img src="{THEME_COLOR_PATH}/images/ispcp_webhosting.png" alt="ispCP Omega" />
		</div>
	</div>
	<div class="location">
		<div class="location-area">
			<h1 class="email">{TR_MENU_EMAIL_ACCOUNTS}</h1>
		</div>
		<ul class="location-menu">
			<!-- BDP: logged_from -->
			<li><a href="change_user_interface.php?action=go_back" class="backadmin">{YOU_ARE_LOGGED_AS}</a></li>
			<!-- EDP: logged_from -->
			<li><a href="../index.php?logout" class="logout">{TR_MENU_LOGOUT}</a></li>
		</ul>
		<ul class="path">
			<li><a>{TR_MENU_OVERVIEW}</a></li>
		</ul>
	</div>
	<div class="left_menu">{MENU}</div>
	<div class="main">
		<!-- BDP: page_message -->
		<div class="{MSG_TYPE}">{MESSAGE}</div>
		<!-- EDP: page_message -->
		<h2 class="email"><span>{TR_MAIL_USERS}</span></h2>
		<!-- BDP: mail_message -->
		<div class="{MSG_TYPE}">{MAIL_MSG}</div>
		<!-- EDP: mail_message -->
		<table class="tablesorter">
			<thead>
				<tr>
					<th>{TR_MAIL}</th>
					<th>{TR_TYPE}</th>
					<th>{TR_STATUS}</th>
					<th>{TR_ACTION}</th>
				</tr>
			</thead>
			<!-- BDP: mails_total -->
			<tfoot>
				<tr>
					<td colspan="4">{TR_TOTAL_MAIL_ACCOUNTS}: {TOTAL_MAIL_ACCOUNTS}/{ALLOWED_MAIL_ACCOUNTS}</td>
				</tr>
			</tfoot>
			<!-- EDP: mails_total -->
			<tbody>
				<!-- BDP: mail_item -->
				<tr>
					<td>
						<span class="icon i_mail_icon">{MAIL_ACC}</span>
						<!-- BDP: auto_respond -->
						<div style="display: {AUTO_RESPOND_VIS};font-size: smaller;">
							<br />
					  		{TR_AUTORESPOND}: [ <a href="{AUTO_RESPOND_DISABLE_SCRIPT}">{AUTO_RESPOND_DISABLE}</a> <a href="{AUTO_RESPOND_EDIT_SCRIPT}">{AUTO_RESPOND_EDIT}</a> ]
						  </div>
					  <!-- EDP: auto_respond -->
					</td>
					<td>{MAIL_TYPE}</td>
					<td>{MAIL_STATUS}</td>
					<td>
						<a href="{MAIL_EDIT_SCRIPT}" title="{MAIL_EDIT}" class="icon i_edit"></a>
						<a href="#" onclick="action_delete('{MAIL_DELETE_SCRIPT}', '{MAIL_ACC}')" title="{MAIL_DELETE}" class="icon i_delete"></a>
					</td>
				</tr>
				<!-- EDP: mail_item -->
			</tbody>
		</table>
		<form action="mail_accounts.php" method="post" id="showdefault">
			<div class="buttons">
				<input type="hidden" name="uaction" value="{VL_DEFAULT_EMAILS_BUTTON}" />
			  	<input type="submit" name="Submit" value="{TR_DEFAULT_EMAILS_BUTTON}" />
			</div>
		</form>
	</div>
<!-- INCLUDE "footer.tpl" -->