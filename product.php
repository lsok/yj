<?php
/*
|
|--------------------------------------------------------------------------
| 产品列表页数据
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


//开始定义产品列表 根据传递到本页的catid值检索相应类别产品 如有子类则先输出子类导航
$catid = (isset($_GET['catid'])) ? $_GET['catid'] : 0;


//默认显示全部类别产品，如点击产品导航则显示该类别下产品
$query = sprintf('SELECT ID, CAT_ID, PRO_NAME, PRO_TYPE, PRO_PRICE, PRO_IMAGE FROM %sproducts WHERE IS_DELETE = 0', DB_TBL_PREFIX);


//如果传递了类别参数
if ($catid != 0)
{
	//先定义面包屑中产品类别链接
	$cate_query = sprintf('SELECT FAT_ID, CATENAME FROM %sproducts_cates WHERE ID = %d', DB_TBL_PREFIX, $catid);
	
	mysql_query("set names 'utf8'");
	$cate_result = mysql_query($cate_query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$cate_row = mysql_fetch_array($cate_result);
	$catefatid = $cate_row['FAT_ID'];  //获取当前类别的父类别ID
	$catename = $cate_row['CATENAME'];  //获取当前类别的名称
	
	mysql_free_result($cate_result);
	
	//当前类别为顶级类别时仅在面包屑中显示类别名称
	$catelinks = '<a href="product-' . $catid . '-1.html">' . $catename . '</a>';
	
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
		$catelinks = '<a href="product-' . $catefatid . '-1.html">' . $cate_fat_name . '</a> - ' . '<a href="product-' . $catid . '-1.html">' . $catename . '</a>';
		
		if ($cate_fat_fatid != 0)
		{
			$cate_fat_fat = sprintf('SELECT CATENAME FROM %sproducts_cates WHERE ID = %d', DB_TBL_PREFIX, $cate_fat_fatid);
	
			mysql_query("set names 'utf8'");
			$cate_fat_fat_result = mysql_query($cate_fat_fat, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
			$cate_fat_fat_row = mysql_fetch_array($cate_fat_fat_result);
			$cate_fat_fat_name = $cate_fat_fat_row['CATENAME'];  //获取当前类别的父类别的父类别的名称
			
			mysql_free_result($cate_fat_fat_result);
			
			//当上级类别又有上级类别时在面包屑中显示其上级类别链接
			$catelinks = '<a href="product-' . $cate_fat_fatid . '-1.html">' . $cate_fat_fat_name . '</a> - <a href="product-' . $catefatid . '-1.html">' . $cate_fat_name . '</a> - ' . '<a href="product-' . $catid . '-1.html">' . $catename . '</a>';
		}
	}
	
	//在面包屑中加入各级类别链接
	$breadcrumb = ' - ' . $catelinks;
	
	//定义个性化元标签
	$unique_title = $catename . '_三通';
		
	
	//检查此类别是否包含下级分类，如包含下级分类则显示所有下级分类的产品
	$sub_cate = sprintf('SELECT ID, CATENAME FROM %sproducts_cates WHERE FAT_ID = %d', DB_TBL_PREFIX, $catid);
	
	mysql_query("set names 'utf8'");
	$sub_result = mysql_query($sub_cate, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($sub_result))  //如果此类别包含子类别
	{
		$sub_array = array();//包含该类别子类别ID和名称的数组
		
		while ($sub_row = mysql_fetch_array($sub_result))
		{
			$sub_array[] = array(
										'sub_cate_id' =>$sub_row['ID'],
										'sub_cate_name' =>$sub_row['CATENAME']
										); 
		}
		
		foreach ($sub_array as $subcate)  //检查子类别是否又包含子类别
		{
			$sub_sub_cate = sprintf('SELECT ID,CATENAME FROM %sproducts_cates WHERE FAT_ID = %d', DB_TBL_PREFIX, $subcate['sub_cate_id']);
		
			mysql_query("set names 'utf8'");
			$sub_sub_result = mysql_query($sub_sub_cate, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
			if (mysql_num_rows($sub_sub_result))
			{
				//如某个子类别又包含子类别，则在数组中删除该子类别ID，因包含子类别的类别中不会包含产品数据
				$key = array_search($subcate['sub_cate_id'],$sub_array);
				unset($sub_array[$key]);
				
				while ($sub_sub_row = mysql_fetch_array($sub_sub_result))
				{
					//将该类别的子类别的子类别ID添加到数组
					$sub_array[] = array(
										'sub_cate_id' =>$sub_sub_row['ID'],
										'sub_cate_name' =>$sub_sub_row['CATENAME']
										);   
				}
			}
			
			mysql_free_result($sub_sub_result);
		}
		
		mysql_free_result($sub_result);
		
		//将包含该类别的 子类别 与 子类别的子类别 的ID的数组转换为以逗号分隔的字符串
		$sub_ids_data = array();
		foreach ($sub_array as $subcate_ids)
		{
			$sub_ids_data[] = $subcate_ids['sub_cate_id'];
		}
		
		$sub_ids = implode(',', $sub_ids_data);
		
		$query .= ' AND CAT_ID IN (' . $sub_ids . ')';
	}
	else
	{
		$query .= ' AND CAT_ID = ' . $catid;
	}
}

//如果执行搜索
if (isset($_REQUEST['search']))
{
	$keyword = mysql_real_escape_string($_REQUEST['search'], $GLOBALS['DB']);

	if ($keyword != '')
	{
		$query .= ' AND (PRO_NAME LIKE "%' . $keyword . '%" OR PRO_TYPE LIKE "%' . $keyword . '%")';
	}
	else
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("请输入产品名称或型号!");history.back(-1);</script>';
	}
}

$query .= ' ORDER BY SUBMIT_DATE DESC';

//执行第一次查询
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$num = mysql_num_rows($result);  //获取记录总条数

$max = 16;  //设置每页记录数
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

$query .=  " limit $min,$max"; //定义显示当前页数据所需的查询语句

// 根据第一次查询的记录数判断，如有数据则输出
if ($num) 
{
	$product_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

	ob_start();

	//显示当前类别的各级子类别链接
	if(isset($sub_array))
	{
		$sublinks = '';
		
		foreach ($sub_array as $subcate_link)
		{
			$sublinks .= '<a href="product-' . $subcate_link['sub_cate_id'] . '-1.html" class="subcatelink">' . $subcate_link['sub_cate_name'] . '</a>';
		}
		
		echo '<div class="subcatelinks"><a href="product-' . $catid . '-1.html" class="subcatelink subcatelink_first">' . $catename . '</a> >> ' . $sublinks . '</div>';
	}
	
	//产品列表
	echo '<ul class="product_ul">';
	
	//为消除图片列表每行最后一项的右边距,定义一个迭代器
	$i = 1;
						   
	while ($product_row = mysql_fetch_array($product_result))
	{
		if (($i%4) == 0)
		{
			echo '<li style="margin-right:0;"><a href="product-view-' . $product_row['ID'] .'.html"><img src="' . $product_row['PRO_IMAGE'] . '" alt="' . $product_row['PRO_NAME']. '">' . $product_row['PRO_NAME'] . '</a></li>';
		}
		else
		{
			echo '<li><a href="product-view-' . $product_row['ID'] .'.html"><img src="' . $product_row['PRO_IMAGE'] . '" alt="' . $product_row['PRO_NAME']. '">' . $product_row['PRO_NAME'] . '</a></li>';
		}
		
		$i = $i + 1;
	}

	echo '</ul>';
	
	mysql_free_result($product_result);

	//如果执行了搜索则在分页URL中传递搜索参数
	$pars = (isset($keyword)) ? '&search=' . $keyword : '';
	
	//如果选择了类别则在分页URL中传递类别参数
	if ($catid != 0)
	{
		$pars = '&catid=' . $catid;
	}
	
	//分页代码
	echo '<div class="page">';
	product_page($pars);
	echo '</div>';

	$products = ob_get_clean();
}
else
{
	$products = '<p class="nodata">无相关数据!</p>';
}


//调用产品列表页模板
include 'templates/product.php';
?>