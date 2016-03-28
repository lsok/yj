<?php
include '401.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/Constant.php';
include 'includes/functions.php';

//添加新轮换图
if (isset($_GET['add'])) 
{
	$img_url = (isset($_POST['item_img'])) ? trim($_POST['item_img']) : '';
	$img_link = (isset($_POST['img_link'])) ? trim($_POST['img_link']) : '';
	$img_title = (isset($_POST['img_title'])) ? trim($_POST['img_title']) : '';
	$img_desc = (isset($_POST['img_desc'])) ? trim($_POST['img_desc']) : '';
	
	//设置排序字段值
	$query = sprintf('SELECT ORDERNUM FROM %sbanner ORDER BY ORDERNUM DESC', DB_TBL_PREFIX);
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$rowsnum = mysql_num_rows($result);
	
	if ($rowsnum)
	{
		$lastitem = mysql_fetch_array($result);
		$ordernum = $lastitem['ORDERNUM'] + 1;
	}
	else
	{
		$ordernum = 1;
	}
	
	mysql_free_result($result);
	

	//如必填项为空则提示
	if ($img_url == '') 
	 {
	     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	     echo '<script>alert("请上传轮换图片!");history.back(-1);</script>';
	 }
	  else 
	 {
		$query = sprintf('INSERT INTO %sbanner (IMG_URL, IMG_LINK, IMG_TITLE, IMG_DESC, ORDERNUM) ' .
							'VALUES ("%s", "%s", "%s", "%s", %d)',
							
							DB_TBL_PREFIX,
							mysql_real_escape_string($img_url, $GLOBALS['DB']),
							mysql_real_escape_string($img_link, $GLOBALS['DB']),
							mysql_real_escape_string($img_title, $GLOBALS['DB']),
							mysql_real_escape_string($img_desc, $GLOBALS['DB']),
							$ordernum);
							
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>location.href="banner_manage.php?list";</script>';
	 }
}

//编辑轮换图
if (isset($_GET['edit'])) 
{
	$item_id = $_POST['item_id']; // 接收要编辑的数据id
	$page = $_POST['page']; // 接收页码以便在编辑完成后跳转回原页面
	
	//定义编辑完成后跳转URL中的参数
	$redi_pars = '&page=' . $page;
	
	//接收表单数据
	$img_url = (isset($_POST['item_img'])) ? trim($_POST['item_img']) : '';
	$img_link = (isset($_POST['img_link'])) ? trim($_POST['img_link']) : '';
	$img_title = (isset($_POST['img_title'])) ? trim($_POST['img_title']) : '';
	$img_desc = (isset($_POST['img_desc'])) ? trim($_POST['img_desc']) : '';
	
	
	if ($img_url == '') 
	 {
	     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	     echo '<script>alert("请上传轮换图片!");history.back(-1);</script>';
	 }
	  else 
	 {
		$query = sprintf('UPDATE %sbanner SET ' .
						'IMG_URL = "%s",
						 IMG_LINK = "%s",
						 IMG_TITLE = "%s",
						 IMG_DESC = "%s"
						 WHERE
						 ID = %d',
						 
						 DB_TBL_PREFIX,
						 mysql_real_escape_string($img_url, $GLOBALS['DB']),
						 mysql_real_escape_string($img_link, $GLOBALS['DB']),
						 mysql_real_escape_string($img_title, $GLOBALS['DB']),
						 mysql_real_escape_string($img_desc, $GLOBALS['DB']),
						 $item_id);
							
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>location.href="banner_manage.php?list' . $redi_pars . '";</script>';
	 }
}

//删除轮换图
if (isset($_GET['del'])) 
{
	$item_id = $_GET['id']; // 接收要删除的数据id
	$page = $_GET['page']; // 接收页码以便在编辑完成后跳转回原页面
	
	//定义编辑完成后跳转URL中的参数
	$redi_pars = '&page=' . $page;
	
	//检索该条记录
	$query = sprintf('SELECT * FROM %sbanner WHERE ID = %d', DB_TBL_PREFIX, $item_id);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//如果记录存在则删除记录 同时删除图片
	if (mysql_num_rows($result))
	{
		$row = mysql_fetch_array($result);
		$bannerimg = $row['IMG_URL'];
		
		//删除轮换图记录
		$query = sprintf('DELETE FROM %sbanner WHERE ID = %d', DB_TBL_PREFIX, $item_id);
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
		//删除图片
		unlink($bannerimg);
	}
	
	mysql_free_result($result);
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>location.href="banner_manage.php?list' . $redi_pars . '";</script>';
}

//批量删除轮换图
if (isset($_GET['delitems'])) 
{
	$item_ids = $_GET['item_ids']; //接收要删除数据的id集合  格式如 2,5,6,8
	
	//获取多个轮换图的图片路径　准备删除
	$query = sprintf ('SELECT IMG_URL FROM %sbanner ' .
	                       'WHERE 
						        ID IN  (%s)',
								
								DB_TBL_PREFIX,
								$item_ids
								);
								
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//批量删除轮换图记录
	$query = sprintf('DELETE FROM %sbanner WHERE ID IN  (%s)', DB_TBL_PREFIX, $item_ids);
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//批量删除轮换图LOGO图片
	while($row = mysql_fetch_array($result))
	{
		unlink($row['IMG_URL']);
	}

	mysql_free_result($result);
	
	echo '<script>location.href="banner_manage.php?list";</script>';
}

//上移或下移图片顺序
if (isset($_GET['move']))
{
	$item_id = $_GET['id']; //接收要移动的记录id
	$up_or_down = $_GET['move']; //接收移动方向参数
	$page = $_GET['page']; // 接收页码以便在操作完成后跳转回原页面
	
	//定义编辑完成后跳转URL中的参数
	$redi_pars = '&page=' . $page;
	
	//取得要移动的记录的排序号
	$query = sprintf('SELECT ORDERNUM FROM %sbanner WHERE ID = %d', DB_TBL_PREFIX, $item_id);
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row_ordernum = mysql_fetch_array($result);
	$item_ordernum = $row_ordernum['ORDERNUM'];  //要移动数据的排序号
	mysql_free_result($result);
	
	
	//检索排序号小于或大于要移动数据的记录(即上面或下面的记录)
	if ($up_or_down == 'up')
	{
		$query = sprintf('SELECT * FROM %sbanner WHERE ORDERNUM < %d ORDER BY ORDERNUM DESC', DB_TBL_PREFIX, $item_ordernum);
	}
	else if ($up_or_down == 'down')
	{
		$query = sprintf('SELECT * FROM %sbanner WHERE ORDERNUM > %d ORDER BY ORDERNUM ASC', DB_TBL_PREFIX, $item_ordernum);
	}
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//如果存在记录  则与相邻数据交换排序号
	if (mysql_num_rows($result))
	{
		$border_item = mysql_fetch_array($result);
		$border_item_ordernum = $border_item['ORDERNUM'];  //获取相邻记录的排序号
		$border_item_id = $border_item['ID'];  //获取相邻记录的ID值
		
		
		//更新相邻记录的排序号为要移动数据的排序号
		$query = sprintf('UPDATE %sbanner SET ' .
						'ORDERNUM = %d
						 WHERE
						 ID = %d',
						 
						 DB_TBL_PREFIX,
						 $item_ordernum,
						 $border_item_id);
		
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		
		//将要移动数据的排序号更新为相邻数据的排序号
		$query = sprintf('UPDATE %sbanner SET ' .
						'ORDERNUM = %d
						 WHERE
						 ID = %d',
						 
						 DB_TBL_PREFIX,
						 $border_item_ordernum,
						 $item_id);
				
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		
		//操作完成后跳转
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>location.href="banner_manage.php?list' . $redi_pars . '";</script>';
	}
}
?>