<?php
/*
|
|--------------------------------------------------------------------------
| 首页数据
|--------------------------------------------------------------------------
|
*/
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
include 'includes/share.php';
include 'includes/functions.php';


//公司简介(已改为静态文本)
/* $query = sprintf('SELECT PAGE_CONTENT FROM %spages WHERE ID = 1', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$row = mysql_fetch_assoc($result);
   $company_profile = str_intercept($row['PAGE_CONTENT'], 278) . '…';
}

mysql_free_result($result); */


//最新动态
$query = sprintf('SELECT ID, CAT_ID, ART_TITLE, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sarticles WHERE CAT_ID = 1 AND IS_DELETE = 0 ORDER BY IS_TOP DESC, SUBMIT_DATE DESC LIMIT 0, 5', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$news = '<ul>';
	
	while ($row = mysql_fetch_array($result))
	{
		$news .= '<li><a href="news-view-' . $row['ID'] . '.html">' . str_intercept($row['ART_TITLE'], 30) . '</a><span>[' . date('Y-m-d', $row['SUBMIT_DATE']) . ']</span></li>';
	}
	
	$news .= '</ul>';
}
else
{
	$news = '';
}

mysql_free_result($result);


//产品展示
$query = sprintf('SELECT ID, PRO_NAME, PRO_IMAGE FROM %sproducts WHERE IS_HOMESHOW = 0 AND IS_DELETE = 0 ORDER BY SUBMIT_DATE DESC LIMIT 0, 3', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$products = '<ul class="index_prolist">';
		   
	while ($row = mysql_fetch_array($result))
	{
		
		$products .= '<li>';
		$products .= '<a href="product_view.php?id=' . $row['ID'] . '"><img src="' . $row['PRO_IMAGE'] . '" width="193" height="180"></a>';
		$products .= '<div class="pro_name">' . $row['PRO_NAME'] . '</div>';
		$products .= '</li>';
	}
	
	$products .= '</ul>';
}
else
{
	$products = '';
}

mysql_free_result($result);


//调用首页模板
include 'templates/index.php';
?>