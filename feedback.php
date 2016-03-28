<?php
/*
|
|--------------------------------------------------------------------------
| 在线留言页数据
|--------------------------------------------------------------------------
|
*/
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
include 'includes/share.php';
include 'includes/functions.php';

session_start();

//包含左栏数据(产品导航 最新文章 联系方式)
include 'includes/leftdata.php';

//定义个性化元标签
$unique_title = '三通_在线留言';

//如用户点击咨询某产品，检索产品名称
if (isset($_GET['askpro'])) 
{
	$query = sprintf('SELECT PRO_NAME FROM %sproducts WHERE ID = %d', DB_TBL_PREFIX, $_GET['askpro']);

	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($result))
	{
		$proRow = mysql_fetch_array($result);
		$proName = $proRow['PRO_NAME'];
	}
	
	mysql_free_result($result);
}

//检索留言参数
$query = sprintf('SELECT * FROM %sguest_param', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
$guest_row = mysql_fetch_assoc($result);

mysql_free_result($result);


//调用留言页模板
include 'templates/feedback.php';
?>