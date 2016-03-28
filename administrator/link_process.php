<?php
include '401.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/Constant.php';
include 'includes/functions.php';

//添加链接组
if (isset($_GET['addgroup'])) 
{

	 $groupname = (isset($_POST['groupname'])) ? trim($_POST['groupname']) : '';
	 
	 if (!$groupname) 
	 {
	     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	     echo '<script>alert("分组名称不能为空!");history.back(-1);</script>';
	 }
	  else 
	 {
		$query = sprintf('INSERT INTO %slink_group (GROUPNAME) ' .
    					'VALUES ("%s")',
    					
    					DB_TBL_PREFIX,
    					mysql_real_escape_string($groupname, $GLOBALS['DB'])
						);
						
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));									
	
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("添加分组成功!");location.href="link_group_manage.php?list";</script>';						
	 }
}

//编辑链接组
if (isset($_GET['editgroup'])) 
{ 
	 $group_id = $_POST['group_id'];
	 $groupname = (isset($_POST['groupname'])) ? trim($_POST['groupname']) : '';
	 $textorimg = $_POST['textorimg'];
	 $page = $_POST['page']; //接收页码以便在编辑完成后跳转回原页面

	 if (!$groupname) 
	 {
	     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	     echo '<script>alert("分组名称不能为空!");history.back(-1);</script>';
	 }
	  else 
	 {
		$query = sprintf('UPDATE %slink_group SET ' .
						'GROUPNAME = "%s",
						 TEXT_OR_IMG = %d
						 WHERE
						 ID = %d',
						 
						 DB_TBL_PREFIX,
						 mysql_real_escape_string($groupname, $GLOBALS['DB']),
						 $textorimg,
						 $group_id);
						 
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));									
	
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("编辑分组成功!");location.href="link_group_manage.php?list&page=' . $page .'";</script>';						
	 }
}

//删除链接组
if (isset($_GET['delgroup'])) 
{ 
	$group_id = $_GET['id'];
	$page = $_GET['page'];
	
	$query = sprintf('DELETE FROM %slink_group WHERE ID = %d', DB_TBL_PREFIX, $group_id);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("删除链接组成功!");location.href="link_group_manage.php?list&page=' . $page . '";</script>';
}

//批量删除链接组
if (isset($_GET['delsome'])) 
{
	$del_ids = $_GET['del_ids']; //接收要删除数据的id集合  格式如 2,5,6,8
	
	$query = sprintf('DELETE FROM %slink_group WHERE ID IN  (%s)', DB_TBL_PREFIX, $del_ids);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	echo '<script>location.href="link_group_manage.php?list";</script>';
}

//添加新链接
if (isset($_GET['addlink'])) 
{
	$link_name = (isset($_POST['link_name'])) ? trim($_POST['link_name']) : '';
	$link_group = (isset($_POST['link_group'])) ? trim($_POST['link_group']) : 0;
	$link_text = (isset($_POST['link_text'])) ? trim($_POST['link_text']) : '';
	$link_url = (isset($_POST['link_url'])) ? trim($_POST['link_url']) : '';
	
	//链接LOGO路径
	$item_img = (isset($_POST['item_img'])) ? trim($_POST['item_img']) : ''; 
	
	//如未上传LOGO则使用默认空白占位LOGO
	if ($item_img == '')
	{
		$item_img = '../administrator/images/link_logo.gif';
	}
	
	//如必填项为空则提示
	if ($link_name == '' || $link_group == '-1' || $link_text == '' || $link_url == '') 
	 {
	     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	     echo '<script>alert("链接信息不完整!");history.back(-1);</script>';
	 }
	  else 
	 {
		$query = sprintf('INSERT INTO %slinks (GROUP_ID, LINK_NAME, LINK_TEXT, LINK_URL, LINK_LOGO) ' .
							'VALUES (%d, "%s", "%s", "%s", "%s")',
							
							DB_TBL_PREFIX,
							$link_group,
							mysql_real_escape_string($link_name, $GLOBALS['DB']),
							mysql_real_escape_string($link_text, $GLOBALS['DB']),
							mysql_real_escape_string($link_url, $GLOBALS['DB']),
							mysql_real_escape_string($item_img, $GLOBALS['DB'])
							);
							
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("添加链接成功!");location.href="link_manage.php?list";</script>';
	 }
}

//编辑链接
if (isset($_GET['editlink'])) 
{
	$item_id = $_POST['item_id']; // 接收要编辑的数据id
	$page = $_POST['page']; // 接收页码以便在编辑完成后跳转回原页面
	
	//定义编辑完成后跳转URL中的参数
	$redi_pars = '&page=' . $page;
	
	//如果用户在筛选类别后进行编辑操作 则接收类别ID 并在编辑完成后跳转回该类别列表
	if (isset($_POST['group']))
	{
		$redi_pars .= '&group=' . $_POST['group'];
	}
	
	$link_name = (isset($_POST['link_name'])) ? trim($_POST['link_name']) : '';
	$link_group = (isset($_POST['link_group'])) ? trim($_POST['link_group']) : 0;
	$link_text = (isset($_POST['link_text'])) ? trim($_POST['link_text']) : '';
	$link_url = (isset($_POST['link_url'])) ? trim($_POST['link_url']) : '';
	
	//链接LOGO路径
	$item_img = (isset($_POST['item_img'])) ? trim($_POST['item_img']) : ''; 
	
	//如未上传LOGO则使用默认空白占位LOGO
	if ($item_img == '')
	{
		$item_img = '../administrator/images/link_logo.gif';
	}
	
	//如必填项为空则提示
	if ($link_name == '' || $link_group == '-1' || $link_text == '' || $link_url == '') 
	 {
	     echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	     echo '<script>alert("链接信息不完整!");history.back(-1);</script>';
	 }
	  else 
	 {
		$query = sprintf('UPDATE %slinks SET ' .
						'GROUP_ID = %d,
						 LINK_NAME = "%s",
						 LINK_TEXT = "%s",
						 LINK_URL = "%s",
						 LINK_LOGO = "%s"
						 WHERE
						 ID = %d',
						 
						 DB_TBL_PREFIX,
						 $link_group,
						 mysql_real_escape_string($link_name, $GLOBALS['DB']),
						 mysql_real_escape_string($link_text, $GLOBALS['DB']),
						 mysql_real_escape_string($link_url, $GLOBALS['DB']),
						 mysql_real_escape_string($item_img, $GLOBALS['DB']),
						 $item_id);
							
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("编辑链接成功!");location.href="link_manage.php?list' . $redi_pars . '";</script>';
	 }
}

//删除链接
if (isset($_GET['del'])) 
{
	$item_id = $_GET['id']; // 接收要删除的数据id
	$page = $_GET['page']; // 接收页码以便在编辑完成后跳转回原页面
	
	//定义编辑完成后跳转URL中的参数
	$redi_pars = '&page=' . $page;
	
	//如果用户在筛选类别后进行编辑操作 则接收类别ID 并在编辑完成后跳转回该类别列表
	if (isset($_GET['group']))
	{
		$redi_pars .= '&group=' . $_GET['group'];
	}
	
	//检索该条记录
	$query = sprintf('SELECT * FROM %slinks WHERE ID = %d', DB_TBL_PREFIX, $item_id);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//如果记录存在则删除它 同时删除LOGO图片
	if (mysql_num_rows($result))
	{
		$row = mysql_fetch_array($result);
		$link_logo = $row['LINK_LOGO'];
		
		//删除链接记录
		$query = sprintf('DELETE FROM %slinks WHERE ID = %d', DB_TBL_PREFIX, $item_id);
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
		//删除LOGO图片
		unlink($link_logo);
	}
	
	mysql_free_result($result);
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("删除链接成功!");location.href="link_manage.php?list' . $redi_pars . '";</script>';
}

//批量删除链接
if (isset($_GET['dellinks'])) 
{
	$del_ids = $_GET['del_ids']; //接收要删除数据的id集合  格式如 2,5,6,8
	
	//获取多个链接LOGO图片的路径　准备删除
	$query = sprintf ('SELECT LINK_LOGO FROM %slinks ' .
	                       'WHERE 
						        ID IN  (%s)',
								
								DB_TBL_PREFIX,
								$del_ids
								);
								
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//批量删除链接记录
	$query = sprintf('DELETE FROM %slinks WHERE ID IN  (%s)', DB_TBL_PREFIX, $del_ids);
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//批量删除链接LOGO图片
	while($row = mysql_fetch_array($result))
	{
		unlink($row['LINK_LOGO']);
	}

	mysql_free_result($result);
	
	echo '<script>location.href="link_manage.php?list";</script>';
}
?>