<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//接收要编辑(即编辑回复)或回复的留言ID
$guest_id = (isset($_GET['id'])) ? $_GET['id'] : 0;

if(!$guest_id)
{
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("非法的留言ID!");history.back(-1);</script>';
	die();
}
else
{
	//取出该ID的留言
	$query = sprintf('SELECT ID, USER_NAME, EMAIL, PHONE, CON_TITLE, CONTENT, CONTENT_REPLY, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sguestbook WHERE ID=%d', DB_TBL_PREFIX, $guest_id);
	
	mysql_query("set names 'utf8'");
	$guest_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$guest_content = mysql_fetch_array($guest_result);
	
	$submit_value = (!$guest_content['CONTENT_REPLY']) ? '立即回复' : '编辑回复';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>留言回复与编辑</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/guestbook.css" media="all"/>
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

//未回复留言数
$noreplys = mysql_num_rows($result);

if($noreplys != 0)
{
	echo '<a href="guest_manage.php?noreply" class="alert">' . $noreplys . ' 条待回复</a>';
}

mysql_free_result($result);
?>
<a href="guest_param.php" target="mainFrame">参数设置</a></h1>

<div class="guest">
	<div class="title">
		<h1><?php echo $guest_content['CON_TITLE']; ?><span><?php echo date('Y-m-d H:i:s', $guest_content['SUBMIT_DATE']); ?></span></h1>
	</div>
	<div class="content"><?php echo $guest_content['CONTENT']; ?></div>
	<ul>
		<li class="user"><?php echo $guest_content['USER_NAME']; ?></li>
		<li class="email"><?php echo $guest_content['EMAIL']; ?></li>
		<li class="phone"><?php echo $guest_content['PHONE']; ?></li>
	</ul>
	<div class="reply"> 
		<div class="write_reply">
			<!-- 回复/编辑回复表单 -->
			<form action="../guest_process.php?reply" method="post">
				<p><textarea name="reply_content" cols="30" rows="5" style="width:50%"><?php echo $guest_content['CONTENT_REPLY']; ?></textarea></p>
				<p><input type="submit" value="<?php echo $submit_value; ?>" id="submitbutton" class="submit" /><input type="hidden" name="guest_id" value="<?php echo $guest_content['ID']; ?>" /></p>
			</form>	
		</div>
	</div>
</div>
<?php mysql_free_result($guest_result); ?>
</div>
</body>
</html>
