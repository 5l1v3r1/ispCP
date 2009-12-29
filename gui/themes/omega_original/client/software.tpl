<?xml version="1.0" encoding="{THEME_CHARSET}" ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{TR_CLIENT_SOFTWARE_PAGE_TITLE}</title>
<meta name="robots" content="nofollow, noindex" />
<meta http-equiv="Content-Type" content="text/html; charset={THEME_CHARSET}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<link href="{THEME_COLOR_PATH}/css/ispcp.css" rel="stylesheet" type="text/css" />
<link href="{THEME_COLOR_PATH}/css/software_tooltip.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{THEME_COLOR_PATH}/css/ispcp.js"></script>
<script type="text/javascript">
<!--
function action_delete(url) {
	if (!confirm("{TR_MESSAGE_DELETE}"))
		return false;

	location = url;
}
function action_install(url) {
	if (!confirm("{TR_MESSAGE_INSTALL}"))
		return false;

	location = url;
}
function action_res_delete(url) {
	if (!confirm("{TR_RES_MESSAGE_DELETE}"))
		return false;

	location = url;
}
//-->
</script>
</head>
<body onload="MM_preloadImages('{THEME_COLOR_PATH}/images/icons/database_a.gif','{THEME_COLOR_PATH}/images/icons/domains_a.gif','{THEME_COLOR_PATH}/images/icons/general_a.gif' ,'{THEME_COLOR_PATH}/images/icons/webtools_a.gif','{THEME_COLOR_PATH}/images/icons/statistics_a.gif','{THEME_COLOR_PATH}/images/icons/support_a.gif','{THEME_COLOR_PATH}/images/icons/email_a.gif','{THEME_COLOR_PATH}/images/icons/ftp_a.gif','{THEME_COLOR_PATH}/images/icons/custom_link_a.gif')">
<!-- BDP: logged_from --><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="20" nowrap="nowrap" class="backButton">&nbsp;&nbsp;&nbsp;<a href="change_user_interface.php?action=go_back"><img src="{THEME_COLOR_PATH}/images/icons/close_interface.png" width="16" height="16" border="0" style="vertical-align:middle" alt="" /></a> {YOU_ARE_LOGGED_AS}</td>
      </tr>
    </table>
	<!-- EDP: logged_from -->
<!-- ToolTip -->
<div id="dmn_help" style="background-color:#ffffe0;border: 1px #000000 solid;display:none;margin:5px;padding:5px;font-size:9pt;font-family:Verdana, sans-serif;color:#000000;width:200px;position:absolute;">{TR_DMN_HELP}</div>
<!-- ToolTip end -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%;padding:0;margin:0 auto;">
<tr>
<td align="left" valign="top" style="vertical-align: top; width: 195px; height: 56px;"><img src="{THEME_COLOR_PATH}/images/top/top_left.jpg" width="195" height="56" border="0" alt="ispCP Logogram" /></td>
<td style="height: 56px; width:100%; background-color: #0f0f0f"><img src="{THEME_COLOR_PATH}/images/top/top_left_bg.jpg" width="582" height="56" border="0" alt="" /></td>
<td style="width: 73px; height: 56px;"><img src="{THEME_COLOR_PATH}/images/top/top_right.jpg" width="73" height="56" border="0" alt="" /></td>
</tr>
	<tr>
		<td style="width: 195px; vertical-align: top;">{MENU}</td>
	    <td colspan="2" style="vertical-align: top;"><table style="width: 100%; padding:0;margin:0;" cellspacing="0">
          <tr style="height:95px;">
            <td style="padding-left:30px; width: 100%; background-image: url({THEME_COLOR_PATH}/images/top/middle_bg.jpg);">{MAIN_MENU}</td>
            <td style="padding:0;margin:0;text-align: right; width: 73px;vertical-align: top;"><img src="{THEME_COLOR_PATH}/images/top/middle_right.jpg" width="73" height="95" border="0" alt="" /></td>
          </tr>
          <tr>
            <td colspan=3>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left">
<table width="100%" cellpadding="5" cellspacing="5">
	<tr>
		<td width="25"><img src="{THEME_COLOR_PATH}/images/icons/cd_big.png" width="25" height="25" /></td>
		<td colspan="2" class="title">{TR_INSTALL_SOFTWARE}</td>
	</tr>
</table>			
			</td>
            <td width="27" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td><table width="100%"  border="00" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="25">&nbsp;</td>
                  <td valign="top"><table width="100%" cellspacing="7">
                    <!-- BDP: page_message -->
					<tr>
                      <td colspan="6" nowrap class="title"><font color="#FF0000">{MESSAGE}</font></td>
                      </tr>
                    <!-- EDP: page_message -->
                    <tr>
                      <td nowrap class="content3"><div style="float:left"><b>{TR_SOFTWARE}</b></div><div style="float:right"><a href="{TR_SOFTWARE_ASC}"><img src="{THEME_COLOR_PATH}/images/icons/asc.gif" width="16" height="16" border="0" /></a><a href="{TR_SOFTWARE_DESC}"><img src="{THEME_COLOR_PATH}/images/icons/desc.gif" width="16" height="16" border="0" /></a></div></td>
					  <td nowrap class="content3" align="center" width="70"><b>{TR_VERSION}</b></td>
					  <td nowrap class="content3" align="center" width="100"><div style="float:left"><b>{TR_LANGUAGE}</b></div><div style="float:right"><a href="{TR_LANGUAGE_ASC}"><img src="{THEME_COLOR_PATH}/images/icons/asc.gif" width="16" height="16" border="0" /></a><a href="{TR_LANGUAGE_DESC}"><img src="{THEME_COLOR_PATH}/images/icons/desc.gif" width="16" height="16" border="0" /></a></div></td>
                      <td nowrap class="content3" align="center" width="70"><div style="float:left"><b>{TR_TYPE}</b></div><div style="float:right"><a href="{TR_TYPE_ASC}"><img src="{THEME_COLOR_PATH}/images/icons/asc.gif" width="16" height="16" border="0" /></a><a href="{TR_TYPE_DESC}"><img src="{THEME_COLOR_PATH}/images/icons/desc.gif" width="16" height="16" border="0" /></a></div></td>
					  <td nowrap class="content3" align="center" width="90"><div style="float:left"><b>{TR_NEED_DATABASE}</b></div><div style="float:right"><a href="{TR_NEED_DATABASE_ASC}"><img src="{THEME_COLOR_PATH}/images/icons/asc.gif" width="16" height="16" border="0" /></a><a href="{TR_NEED_DATABASE_DESC}"><img src="{THEME_COLOR_PATH}/images/icons/desc.gif" width="16" height="16" border="0" /></a></div></td>
                      <td nowrap class="content3" align="center" width="90"><b>{TR_STATUS}</b></td>
                      <td nowrap class="content3" align="center" width="150"><b>{TR_ACTION}</b></td>
                    </tr>
					<!-- BDP: t_software_support -->
                    <!-- BDP: software_item -->
                    <tr>
                      <td nowrap class="{ITEM_CLASS}"><img src="{THEME_COLOR_PATH}/images/icons/cd.png" width="14" height="14" align="middle" /> <a href="{VIEW_SOFTWARE_SCRIPT}" class="tt">{SOFTWARE_NAME}<span class="tooltip"><span class="top">&nbsp;</span><span class="middle">{SOFTWARE_DESCRIPTION}</span><span class="bottom">&nbsp;</span></span></a></td>
                      <td nowrap class="{ITEM_CLASS}" align="center">{SOFTWARE_VERSION}</td>
					  <td nowrap class="{ITEM_CLASS}" align="center">{SOFTWARE_LANGUAGE}</td>
					  <td nowrap class="{ITEM_CLASS}" align="center">{SOFTWARE_TYPE}</td>
					  <td nowrap class="{ITEM_CLASS}" align="center">{SOFTWARE_NEED_DATABASE}</td>
                      <td nowrap class="{ITEM_CLASS}" align="center">{SOFTWARE_STATUS}</td>
                      <td nowrap class="{ITEM_CLASS}" align="center"><img src="{THEME_COLOR_PATH}/images/icons/{SOFTWARE_ICON}.png" width="16" height="16" border="0" align="middle" /> <a href="#" class="link" <!-- BDP: software_action_delete -->  onClick="return action_delete('{SOFTWARE_ACTION_SCRIPT}')" <!-- EDP: software_action_delete --><!-- BDP: software_action_install -->  onClick="return action_install('{SOFTWARE_ACTION_SCRIPT}')" <!-- EDP: software_action_install --> >{SOFTWARE_ACTION}</a>
                      </td>
                    </tr>
                    <!-- EDP: software_item -->
					<!-- EDP: t_software_support -->
                    <!-- BDP: no_software_support -->
                    <tr>
                      <td nowrap colspan="7" class="content">{NO_SOFTWARE_AVAIL}</td>
                    </tr>
                    <!-- EDP: no_software_support -->
                    <!-- BDP: software_total -->
                    <tr>
                      <td colspan="7" align="right" nowrap class="content3">{TR_SOFTWARE_AVAILABLE}:&nbsp;<b>{TOTAL_SOFTWARE_AVAILABLE}</b></td>
                    </tr>
                    <!-- EDP: software_total -->
					<!-- BDP: del_software_support -->
					<tr>
                      <td colspan="6" nowrap>&nbsp;</td>
                      </tr>
					<tr>
                      <td colspan="4" nowrap class="content3"><b>{TR_DEL_SOFTWARE}</b></td>
                      <td nowrap class="content3" align="center" width="150"><b>{TR_DEL_STATUS}</b></td>
                      <td nowrap class="content3" align="center" width="150"><b>{TR_DEL_ACTION}</b></td>
                    </tr>
					<!-- BDP: del_software_item -->
                    <tr>
                      <td colspan="4" nowrap class="{DEL_ITEM_CLASS}">{SOFTWARE_DEL_RES_MESSAGE}</td>
                      <td nowrap class="{DEL_ITEM_CLASS}" align="center" width="150">{DEL_SOFTWARE_STATUS}</td>
                      <td nowrap class="{DEL_ITEM_CLASS}" align="center" width="150"><img src="{THEME_COLOR_PATH}/images/icons/delete.png" width="16" height="16" border="0" align="middle" /> <a href="#" class="link" onClick="return action_res_delete('{DEL_SOFTWARE_ACTION_SCRIPT}')">{DEL_SOFTWARE_ACTION}</a>
                      </td>
                    </tr>
                    <!-- EDP: del_software_item -->
					<!-- EDP: del_software_support -->
                  </table>
                    </td>
                </tr>
            </table></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>			
			</td>
          </tr>
        </table>
	  </td>
	</tr>
</table>
</body>
</html>
