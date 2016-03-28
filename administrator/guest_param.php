<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//检索留言参数表
$query = sprintf('SELECT * FROM %sguest_param', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
$row = mysql_fetch_array($result);

// 设置表单各字段默认值
$is_mail = $row['IS_MAIL'];
$is_show = $row['IS_SHOW'];
$is_vericode = $row['IS_VERICODE'];
$receive_email = $row['RECEIVE_EMAIL'];
$reply_smtp = $row['REPLY_SMTP'];
$reply_smtp_username = $row['REPLY_SMTP_USERNAME'];
$reply_smtp_password = $row['REPLY_SMTP_PASSWORD'];

//如果提交了表单则更新数据
if (isset($_POST['submitted'])) 
{ 
	// 接收表单值
	$is_mail = $_POST['is_mail'];
	$is_show = $_POST['is_show'];
	$is_vericode = $_POST['is_vericode'];
	$receive_email = (isset($_POST['receivemail'])) ? trim($_POST['receivemail']) : '';
	$reply_smtp = (isset($_POST['smtphost'])) ? trim($_POST['smtphost']) : '';
	$reply_smtp_username = (isset($_POST['smtpuser'])) ? trim($_POST['smtpuser']) : '';
	$reply_smtp_password = (isset($_POST['smtpps'])) ? trim($_POST['smtpps']) : '';
	
	//如果设置了留言发送到邮箱且管理员邮箱为空 则提示错误
	if ($is_mail == 1 && $receive_email == '')
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("管理员邮箱不能为空!");history.back(-1);</script>';
	}
	else
	{
	
		$query = sprintf('UPDATE %sguest_param SET ' .
				'IS_MAIL = %d,
				 IS_SHOW = %d,
				 IS_VERICODE = %d,
				 RECEIVE_EMAIL = "%s",
				 REPLY_SMTP = "%s",
				 REPLY_SMTP_USERNAME = "%s",
				 REPLY_SMTP_PASSWORD = "%s"',
				 
				 DB_TBL_PREFIX,
				 $is_mail,
				 $is_show,
				 $is_vericode,
				 mysql_real_escape_string($receive_email, $GLOBALS['DB']),
				 mysql_real_escape_string($reply_smtp, $GLOBALS['DB']),
				 mysql_real_escape_string($reply_smtp_username, $GLOBALS['DB']),
				 mysql_real_escape_string($reply_smtp_password, $GLOBALS['DB'])
				 );
				 
		
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo "<script>alert('参数设置成功!');location.href='guest_param.php';</script>";
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>留言参数设置</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/tips.css" media="all"/>
<script type="text/javascript" src="js/tips.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
</head>
<body>
<div id="wrap">
<h1>留言管理<a href="guest_manage.php" target="mainFrame">留言列表</a>
<?php
//如存在未回复留言则显示“待回复”链接
$query = sprintf('SELECT * FROM %sguestbook WHERE CONTENT_REPLY IS NULL', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$noreplys = mysql_num_rows($result);

if($noreplys != 0)
{
	echo '<a href="guest_manage.php?noreply" class="alert">' . $noreplys . ' 条待回复</a>';
}

mysql_free_result($result);
?>
<a href="guest_param.php" target="mainFrame">参数设置</a></h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<table border="0" class="formtable">
<tr>
<td colspan="2" class="td_title">留言同时发送到管理员邮箱？ <input type="radio" name="is_mail" value="1" id="ismyes" class="radio" <?php if($is_mail == 1) { ?>checked="checked"<?php } ?> /> <label for="ismyes" class="radio_label">是</label><input type="radio" name="is_mail" value="0" id="ismno" class="radio" <?php if($is_mail == 0) { ?>checked="checked"<?php } ?> /> <label for="ismno" class="radio_label">否</label>
</td>
</tr>

<tr>
<td class="label"><label for="receivemail">管理员邮箱</label></td>
<td><input type="text" name="receivemail"  id="receivemail" class="textinput" value="<?php echo $receive_email; ?>" /><a href="#" class="hintanchor" onMouseover="showhint('客户留言会同时发送到此邮箱', this, event, '165px')"><img src="images/info.png" /></td>
</tr>

<tr>
<td colspan="2" class="td_title">前台是否显示客户留言列表？ <input type="radio" name="is_show" value="1" id="isshow" class="radio" <?php if($is_show == 1) { ?>checked="checked"<?php } ?> /> <label for="isshow" class="radio_label">是</label><input type="radio" name="is_show" value="0" id="noshow" class="radio" <?php if($is_show == 0) { ?>checked="checked"<?php } ?> /> <label for="noshow" class="radio_label">否</label>
</td>
</tr>

<tr>
<td colspan="2" class="td_title">留言表单中是否开启验证码？ <input type="radio" name="is_vericode" value="1" id="isopen" class="radio" <?php if($is_vericode == 1) { ?>checked="checked"<?php } ?> /> <label for="isopen" class="radio_label">是</label><input type="radio" name="is_vericode" value="0" id="noopen" class="radio" <?php if($is_vericode == 0) { ?>checked="checked"<?php } ?> /> <label for="noopen" class="radio_label">否</label>
</td>
</tr>

<tr>
<td class="label"><label for="smtphost">SMTP服务器</label></td>
<td  id="set1"><input type="text" name="smtphost" id="smtphost" class="textinput" value="<?php echo $reply_smtp; ?>" /><a href="#" class="hintanchor" onMouseover="showhint('执行邮件发送任务的SMTP服务器,如 smtp.163.com', this, event, '300px')"><img src="images/tips.gif" /></a>
</tr>

<tr>
<td class="label"><label for="smtpuser">SMTP用户名</label></td>
<td><input type="text" name="smtpuser" id="smtpuser" class="textinput" value="<?php echo $reply_smtp_username; ?>" /><a href="#" class="hintanchor" onMouseover="showhint('SMTP用户名,即发信邮箱用户名', this, event, '190px')"><img src="images/tips.gif" /></a></td>
</tr>

<tr>
<td class="label"><label for="smtpps">SMTP密码</label></td>
<td><input type="text" name="smtpps" id="smtpps" class="textinput" value="<?php echo $reply_smtp_password; ?>" /><a href="#" class="hintanchor" onMouseover="showhint('SMTP密码,即发信邮箱登录密码', this, event, '190px')"><img src="images/tips.gif" /></a></td>
</tr>

<tr><td colspan="2"><input type="submit" name="submit" value="保存设置" id="submitbutton" class="submit" /><input type="hidden" name="submitted" value="true" /></td></tr>
</table>
</form>
</div>
</body>
</html>
