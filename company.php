<?php
/*
|
|--------------------------------------------------------------------------
| 单页数据 公司介绍
|--------------------------------------------------------------------------
|
*/
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
include 'includes/share.php';
include 'includes/functions.php';

//包含左栏数据(产品导航 最新文章 联系方式)
include 'includes/leftdata.php';

//公司介绍
$query = sprintf('SELECT PAGE_TITLE, PAGE_DESCRIPTION, PAGE_KEYWORDS, PAGE_CONTENT FROM %spages WHERE ID = 1', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$row = mysql_fetch_assoc($result);
	
	//定义个性化元标签
	$unique_title = $row['PAGE_TITLE'];
	$unique_description = $row['PAGE_DESCRIPTION'];
	$unique_keywords = $row['PAGE_KEYWORDS'];
	
	//单页内容
   $company = $row['PAGE_CONTENT'];
}

mysql_free_result($result);


//产品展示
$query = sprintf('SELECT ID, PRO_NAME, PRO_PRICE, PRO_IMAGE FROM %sproducts WHERE IS_DELETE = 0 ORDER BY SUBMIT_DATE DESC LIMIT 0, 8', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$products = '<ul class="product_ul">';
		   
	while ($row = mysql_fetch_array($result))
	{
		$products .= '<li><a href="product-view-' . $row['ID'] .'.html"><img src="' . $row['PRO_IMAGE'] . '" alt="' . $row['PRO_NAME']. '">' . $row['PRO_NAME'] . '</a></li>';
	}
	
	$products .= '</ul>';
}
else
{
	$products = '';
}

mysql_free_result($result);


//调用公司介绍页模板
include 'templates/company.php';
?>