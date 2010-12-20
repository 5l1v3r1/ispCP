<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title>{TR_CLIENT_MANAGE_DOMAINS_PAGE_TITLE}</title>
	<meta http-equiv='Content-Script-Type' content='text/javascript' />
	<meta http-equiv='Content-Style-Type' content='text/css' />
	<meta http-equiv='Content-Type' content='text/html; charset={THEME_CHARSET}' />
	<meta name='copyright' content='ispCP Omega' />
	<meta name='owner' content='ispCP Omega' />
	<meta name='publisher' content='ispCP Omega' />
	<meta name='robots' content='nofollow, noindex' />
	<meta name='title' content='{TR_CLIENT_MANAGE_DOMAINS_PAGE_TITLE}' />
	<link href="{THEME_COLOR_PATH}/css/ispcp.css" rel="stylesheet" type="text/css" />
	<link href="{THEME_COLOR_PATH}/css/jquery.tablesorter.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="{THEME_SCRIPT_PATH}/jquery.js"></script>
	<script type="text/javascript" src="{THEME_SCRIPT_PATH}/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="{THEME_SCRIPT_PATH}/ispcp.js"></script>
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
			<h1 class="domains">{TR_MENU_MANAGE_DOMAINS}</h1>
		</div>
		<ul class="location-menu">
			<!-- BDP: logged_from -->
			<li><a href="change_user_interface.php?action=go_back" class="backadmin">{YOU_ARE_LOGGED_AS}</a></li>
			<!-- EDP: logged_from -->
			<li><a href="../index.php?logout" class="logout">{TR_MENU_LOGOUT}</a></li>
		</ul>
		<ul class="path">
			<li><a href="domains_manage.php">{TR_MENU_MANAGE_DOMAINS}</a></li>
			<li><a href="domains_manage.php">{TR_MENU_OVERVIEW}</a></li>
		</ul>
	</div>
	<div class="left_menu">{MENU}</div>
	<div class="main">
		<!-- BDP: page_message -->
		<div class="{MSG_TYPE}">{MESSAGE}</div>
		<!-- EDP: page_message -->
		<h2 class="domains"><span>{TR_DOMAIN_ALIASES}</span></h2>
		<!-- BDP: als_message -->
			<div class="{MSG_TYPE}">{ALS_MSG}</div>
		<!-- EDP: als_message -->
		<!-- BDP: als_list -->
		<table class="tablesorter">
			<thead>
				<tr>
					<th>{TR_ALS_NAME}</th>
					<th style="width:200px">{TR_ALS_MOUNT}</th>
					<th style="width:200px">{TR_ALS_FORWARD}</th>
					<th style="width:100px">{TR_ALS_STATUS}</th>
					<th style="width:200px">{TR_ALS_ACTION}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BDP: als_item -->
				<tr>
					<td><a href="http://{ALS_NAME}/" class="icon i_domain" title="{ALS_NAME}">{ALS_NAME}</a></td>
					<td>{ALS_MOUNT}</td>
					<td>{ALS_FORWARD}</td>
					<td>{ALS_STATUS}</td>
					<td>
						<a href="{ALS_EDIT_LINK}" title="{ALS_EDIT}" class="icon i_edit"></a>
						<a href="#" onclick="action_delete('{ALS_ACTION_SCRIPT}', '{ALS_NAME}')" title="{ALS_ACTION}" class="icon i_delete"></a>
					</td>
				</tr>
				<!-- EDP: als_item -->
			</tbody>
		</table>
		<!-- EDP: als_list -->
		<h2 class="domains"><span>{TR_SUBDOMAINS}</span></h2>
		<!-- BDP: sub_message -->
		<div class="{MSG_TYPE}">{SUB_MSG}</div>
		<!-- EDP: sub_message -->
		<!-- BDP: sub_list -->
		<table class="tablesorter">
			<thead>
				<tr>
					<th>{TR_SUB_NAME}</th>
					<th style="width:200px">{TR_SUB_MOUNT}</th>
					<th style="width:200px">{TR_SUB_FORWARD}</th>
					<th style="width:100px">{TR_SUB_STATUS}</th>
					<th style="width:200px">{TR_SUB_ACTION}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BDP: sub_item -->
				<tr>
					<td><a href="http://{SUB_NAME}.{SUB_ALIAS_NAME}/" title="{SUB_NAME}.{SUB_ALIAS_NAME}" class="icon i_domain">{SUB_NAME}.{SUB_ALIAS_NAME}</a></td>
					<td>{SUB_MOUNT}</td>
					<td>{SUB_FORWARD}</td>
					<td>{SUB_STATUS}</td>
					<td>
						<a href="{SUB_EDIT_LINK}" title="{SUB_EDIT}" class="icon i_edit"></a>
						<a href="#" onclick="action_delete('{SUB_ACTION_SCRIPT}', '{SUB_NAME}')" class="icon i_delete"></a>
					</td>
				</tr>
				<!-- EDP: sub_item -->
			</tbody>
		</table>
		<!-- EDP: sub_list -->
		<!-- BDP: isactive_dns -->
		<h2 class="domains"><span>{TR_DNS}</span></h2>
		<!-- BDP: dns_message -->
		<div class="{MSG_TYPE}">{DNS_MSG}</div>
		<!-- EDP: dns_message -->
		<!-- BDP: dns_list -->
		<table class="tablesorter">
			<thead>
				<tr>
					<th>{TR_DOMAIN_NAME}</th>
					<th>{TR_DNS_NAME}</th>
					<th>{TR_DNS_CLASS}</th>
					<th>{TR_DNS_TYPE}</th>
					<th>{TR_DNS_DATA}</th>
					<th>{TR_DNS_STATUS}</th>
					<th style="width:200px">{TR_DNS_ACTION}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BDP: dns_item -->
				<tr>
					<td><span class="icon i_domain_icon">{DNS_DOMAIN}</span></td>
					<td>{DNS_NAME}</td>
					<td>{DNS_CLASS}</td>
					<td>{DNS_TYPE}</td>
					<td>{DNS_DATA}</td>
					<td>{DNS_STATUS}</td>
					<td>
						<a href="{DNS_ACTION_SCRIPT_EDIT}" title="{DNS_ACTION_EDIT}" class="icon i_edit"></a>
						<a href="#" onclick="action_delete('{DNS_ACTION_SCRIPT_DELETE}', '{DNS_TYPE_RECORD}')" title="{DNS_ACTION_DELETE}" class="icon i_delete"></a>
					</td>
				</tr>
				<!-- EDP: dns_item -->
			</tbody>
		</table>
		<!-- EDP: dns_list -->
		<!-- EDP: isactive_dns -->
	</div>
	<div class="footer">
		ispCP Omega {VERSION}<br />build: {BUILDDATE}<br />Codename: {CODENAME}
	</div>
</body>
</html>