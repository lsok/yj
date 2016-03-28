<?php
/*
|
|--------------------------------------------------------------------------
| 图片详情页数据
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


//接收图片ID
$item_id = (isset($_GET['id'])) ? $_GET['id'] : 0;


//更新图片点击数
$query = sprintf('UPDATE %simages SET ' .
				'READCOUNT = READCOUNT + 1
				WHERE
				ID = %d',

				DB_TBL_PREFIX,
				$item_id);

mysql_query("set names 'utf8'");
mysql_query($query, $GLOBALS['DB']);


//检索图片信息
$query = sprintf('SELECT ID, CAT_ID, IMG_NAME, IMG_URL, IMG_DESC, IS_SHOW, READCOUNT, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %simages WHERE ID = %d', DB_TBL_PREFIX, $item_id);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$row = mysql_fetch_array($result);
	
	//定义图片类别ID变量
	$item_catid = $row['CAT_ID'];
	
	//检索图片类别名称
	$query = sprintf('SELECT ID, CATENAME FROM %simages_cates WHERE ID = %d', DB_TBL_PREFIX, $item_catid);
	
	mysql_query("set names 'utf8'");
	$cate_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$cate_row = mysql_fetch_array($cate_result);
	$catename = $cate_row['CATENAME'];
	
	mysql_free_result($cate_result);
	
	//定义个性化元标签
	$unique_title = $row['IMG_NAME'] . '|' . $catename . '|承插件|承插管件';
	$unique_description = str_intercept(strip_tags($row['IMG_DESC']), 160);
	
	//定义面包屑中部分链接
	$breadcrumb = ' - <a href="picture.php?catid=' . $row['CAT_ID'] . '">' . $catename . '</a> - ' . $row['IMG_NAME'];
	
	//定义图片详细信息
	$picture_details = '<div class="pro_dts">';
	$picture_details .= '<img src="' . $row['IMG_URL'] . '" alt="' .  $row['IMG_NAME']. '">';
	$picture_details .= '<h2>图片名称:' . $row['IMG_NAME'] . ' 浏览:' . $row['READCOUNT'] . '<a href="javascript:history.go(-1);"><img src="templates/images/back.png" title="返回前页" class="back"></a></h2>';
	//$picture_details .= '<h3>图片说明</h3><div>' . $row['IMG_DESC'] . '</div>';
	$picture_details .= '</div>';
}
else
{
	$picture_details = '';
}

mysql_free_result($result);


//相关图片数据
$query = sprintf('SELECT ID, IMG_NAME, IMG_URL FROM %simages WHERE CAT_ID = %d AND ID != %d AND IS_DELETE = 0 LIMIT 0, 8', DB_TBL_PREFIX, $item_catid, $item_id);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$related_picture = '<h3 class="related_title">相关图片</h3>';
	$related_picture .= '<ul class="product_ul">';
	
	//为消除图片列表每行最后一项的右边距,定义一个迭代器
	$i = 1;
						   
	while ($row = mysql_fetch_array($result))
	{
		if (($i%4) == 0)
		{
			$related_picture .= '<li style="margin-right:0;"><a href="picture-view-' . $row['ID'] . '.html"><img src="' . $row['IMG_URL'] . '">' . $row['IMG_NAME'] . '</a></li>';
		}
		else
		{
			$related_picture .= '<li><a href="picture-view-' . $row['ID'] . '.html"><img src="' . $row['IMG_URL'] . '">' . $row['IMG_NAME'] . '</a></li>';
		}
		
		$i = $i + 1;
	}

	$related_picture .= '</ul>';
}
else
{
	$related_picture = '';
}

mysql_free_result($result);


//调用图片详情页模板
include 'templates/picture_view.php';
?>