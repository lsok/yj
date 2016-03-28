<?php
/*
|
|--------------------------------------------------------------------------
| 图片列表页数据
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
	
//定义图片类别ID
$catid = $_GET['catid'];

//检索类别名称
$query = sprintf('SELECT CATENAME FROM %simages_cates WHERE ID = %d', DB_TBL_PREFIX, $catid);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$cate_row = mysql_fetch_assoc($result);
$catename = $cate_row['CATENAME'];

//定义页面元标签(title)
$unique_title = '三通_' . $catename;

//定义面包屑中类别名称
$breadcrumb = ' - ' . $catename;

mysql_free_result($result);

//按类别检索图片
$query = sprintf('SELECT ID, CAT_ID, IMG_NAME, IMG_URL FROM %simages WHERE CAT_ID = %d AND IS_DELETE = 0 ORDER BY SUBMIT_DATE DESC', DB_TBL_PREFIX, $catid);

//执行第一次查询
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$num = mysql_num_rows($result);  //获取记录总条数

$max = 12;  //设置每页记录数
$pagenum = ceil($num/$max);  //计算可分页数

if(!isset($_GET['page']) or !intval($_GET['page']) or !is_numeric($_GET['page']) or $_GET['page'] > $pagenum)
{
	$page = 1; //当页数不存在、不为十进制数、不是数字或大于可分页数时 值为1
}
else
{
	$page = $_GET['page'];  //接收当前页码数
}

$min = ($page-1)*$max;  //当前页从第$min条记录开始

mysql_free_result($result); //清除第一次查询结果集

$query .=  " LIMIT $min,$max"; //定义显示当前页数据所需的查询语句

// 根据第一次查询的记录数判断，如有数据则输出
if ($num) 
{
	$imgs_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

	ob_start();
	
	//图片列表
	echo '<ul class="product_ul">';
	
	//为消除图片列表每行最后一项的右边距,定义一个迭代器
	$i = 1;
						   
	while ($imgs_row = mysql_fetch_array($imgs_result))
	{
		if (($i%2) == 0)
		{
			echo '<li style="margin-right:0;"><a href="picture-view-' . $imgs_row['ID'] . '.html"><img src="' . $imgs_row['IMG_URL'] . '" alt="' . $imgs_row['IMG_NAME']. '">' . $imgs_row['IMG_NAME'] . '</a></li>';
		}
		else
		{
			echo '<li><a href="picture-view-' . $imgs_row['ID'] . '.html"><img src="' . $imgs_row['IMG_URL'] . '" alt="' . $imgs_row['IMG_NAME']. '">' . $imgs_row['IMG_NAME'] . '</a></li>';
		}
		
		$i = $i + 1;
	}

	echo '</ul>';
	
	mysql_free_result($imgs_result);

	//在分页链接中传递类别参数  
	$pars = (isset($catid)) ? '&catid=' . $catid : '';
	
	//分页代码
	echo '<div class="page">';
	picture_page($pars);
	echo '</div>';

	$images = ob_get_clean();
}
else
{
	$images = '<p class="nodata">无相关数据!</p>';
}


//调用图片列表页模板
include 'templates/picture.php';
?>