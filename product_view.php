<?php
/*
|
|--------------------------------------------------------------------------
| 产品详情页数据
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


//接收产品ID
$item_id = (isset($_GET['id'])) ? $_GET['id'] : 0;


//更新产品点击数
$query = sprintf('UPDATE %sproducts SET ' .
				'READCOUNT = READCOUNT + 1
				WHERE
				ID = %d',

				DB_TBL_PREFIX,
				$item_id);

mysql_query("set names 'utf8'");
mysql_query($query, $GLOBALS['DB']);


//检索产品信息
$query = sprintf('SELECT ID, CAT_ID, PRO_NAME, PRO_TYPE, PRO_PRICE, PRO_IMAGE, PRO_CONTENT, READCOUNT FROM %sproducts WHERE ID = %d AND IS_DELETE = 0', DB_TBL_PREFIX, $item_id);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$row = mysql_fetch_array($result);
	
	//先定义面包屑中产品类别链接
	$cate_query = sprintf('SELECT FAT_ID, CATENAME FROM %sproducts_cates WHERE ID = %d', DB_TBL_PREFIX, $row['CAT_ID']);
	
	mysql_query("set names 'utf8'");
	$cate_result = mysql_query($cate_query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$cate_row = mysql_fetch_array($cate_result);
	$catefatid = $cate_row['FAT_ID'];  //获取当前类别的父类别ID
	$catename = $cate_row['CATENAME'];  //获取当前类别的名称
	
	mysql_free_result($cate_result);
	
	//当前类别为顶级类别时仅在面包屑中显示类别名称
	$catelinks = '<a href="product-' . $row['CAT_ID'] . '-1.html">' . $catename . '</a> - ' . $row['PRO_NAME'];
	
	//如当前类别非顶级类别，则在面包屑中显示其各级上级类别链接
	if ($catefatid != 0) 
	{
		$cate_fat = sprintf('SELECT FAT_ID, CATENAME FROM %sproducts_cates WHERE ID = %d', DB_TBL_PREFIX, $catefatid);
	
		mysql_query("set names 'utf8'");
		$cate_fat_result = mysql_query($cate_fat, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
		$cate_fat_row = mysql_fetch_array($cate_fat_result);
		$cate_fat_fatid = $cate_fat_row['FAT_ID'];  //获取当前类别的父类别的父类别ID
		$cate_fat_name = $cate_fat_row['CATENAME'];  //获取当前类别的父类别名称
		
		mysql_free_result($cate_fat_result);
		
		//当前类别有上级类别时在面包屑中显示上级类别链接
		$catelinks = '<a href="product-' . $catefatid . '-1.html">' . $cate_fat_name . '</a> - ' . '<a href="product-' . $row['CAT_ID'] . '-1.html">' . $catename . '</a> - ' . $row['PRO_NAME'];
		
		if ($cate_fat_fatid != 0)
		{
			$cate_fat_fat = sprintf('SELECT CATENAME FROM %sproducts_cates WHERE ID = %d', DB_TBL_PREFIX, $cate_fat_fatid);
	
			mysql_query("set names 'utf8'");
			$cate_fat_fat_result = mysql_query($cate_fat_fat, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
			$cate_fat_fat_row = mysql_fetch_array($cate_fat_fat_result);
			$cate_fat_fat_name = $cate_fat_fat_row['CATENAME'];  //获取当前类别的父类别的父类别的名称
			
			mysql_free_result($cate_fat_fat_result);
			
			//当上级类别又有上级类别时在面包屑中显示其上级类别链接
			$catelinks = '<a href="product-' . $cate_fat_fatid . '-1.html">' . $cate_fat_fat_name . '</a> - <a href="product-' . $catefatid . '-1.html">' . $cate_fat_name . '</a> - ' . '<a href="product-' . $row['CAT_ID'] . '-1.html">' . $catename . '</a> - ' . $row['PRO_NAME'];
		}
	}
	
	//在面包屑中加入各级类别链接
	$breadcrumb = ' - ' . $catelinks;
	
	//定义个性化元标签
	$unique_title = $row['PRO_NAME'] . '_' . $catename . '系列_三通';
	$unique_description = str_intercept(strip_tags($row['PRO_CONTENT']), 160);
	
	//定义产品型号(仅在填写型号后显示)
	$pro_type = ($row['PRO_TYPE'] != '') ? '型号:' .$row['PRO_TYPE'] : '';
	
	//定义产品图片及其它详情
	$product_details = '<div class="pro_dts">';
	$product_details .= '<img src="' . $row['PRO_IMAGE'] . '" alt="' .  $row['PRO_NAME']. '">';
	$product_details .= '<h2>';
	
	if ($row['CAT_ID'] == 1 || $row['CAT_ID'] == 2 || $row['CAT_ID'] == 3 || $row['CAT_ID'] == 4 || $row['CAT_ID'] == 6)
	{
		$product_details .= '承插件: ';
	}
	else if ($row['CAT_ID'] == 5)
	{
		$product_details .= '螺纹管件: ';
	}
	else if ($row['CAT_ID'] == 16)
	{
		$product_details .= '补偿器: ';
	}
	else if ($row['CAT_ID'] == 17)
	{
		$product_details .= '软接头: ';
	}
	
	$product_details .= $row['PRO_NAME'] . ' ' . $pro_type . ' 浏览:' . $row['READCOUNT'] . ' <a href="javascript:history.go(-1);"><img src="templates/images/back.png" title="返回前页" class="back"></a></h2>';
	if ($row['PRO_CONTENT'] != '')
	{
		$product_details .= '<h3>';

		if ($row['CAT_ID'] == 1 || $row['CAT_ID'] == 2 || $row['CAT_ID'] == 3 || $row['CAT_ID'] == 4 || $row['CAT_ID'] == 6)
		{
			$product_details .= '承插件: ';
		}
		else if ($row['CAT_ID'] == 5)
		{
			$product_details .= '螺纹管件: ';
		}
		else if ($row['CAT_ID'] == 16)
		{
			$product_details .= '补偿器: ';
		}
		else if ($row['CAT_ID'] == 17)
		{
			$product_details .= '软接头: ';
		}
		
		$product_details .= $row['PRO_NAME'] . '说明</h3><div>' . $row['PRO_CONTENT'] . '</div>';
		}
	
		$product_details .= '</div>';
}
else
{
	$product_details = '';
}

mysql_free_result($result);


//相关产品数据
$query = sprintf('SELECT ID, PRO_NAME, PRO_IMAGE FROM %sproducts WHERE CAT_ID = %d AND ID != %d AND IS_DELETE = 0 LIMIT 0, 8', DB_TBL_PREFIX, $row['CAT_ID'], $item_id);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$related_product = '<h3 class="related_title">其它' . $catename . '产品</h3>';
	$related_product .= '<ul class="product_ul" style="margin-left:0">';
	
	//为消除图片列表每行最后一项的右边距,定义一个迭代器
	$i = 1;
						   
	while ($row = mysql_fetch_array($result))
	{
		if (($i%4) == 0)
		{
			$related_product .= '<li style="margin-right:0;"><a href="product-view-' . $row['ID'] . '.html"><img src="' . $row['PRO_IMAGE'] . '">' . $row['PRO_NAME'] . '</a></li>';
		}
		else
		{
			$related_product .= '<li><a href="product-view-' . $row['ID'] . '.html"><img src="' . $row['PRO_IMAGE'] . '">' . $row['PRO_NAME'] . '</a></li>';
		}
		
		$i = $i + 1;
	}

	$related_product .= '</ul>';
}
else
{
	$related_product = '';
}

mysql_free_result($result);


//调用产品详情页模板
include 'templates/product_view.php';
?>