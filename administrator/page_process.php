<?php
include '401.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/Constant.php';
include 'includes/functions.php';

//如果是新增单页
if (isset($_POST['pageid']) && ($_POST['pageid'] == 0)) 
{
	$pagename = (isset($_POST['pagename'])) ? trim($_POST['pagename']) : '';
	$fat_id = (isset($_POST['page_fid'])) ? (int)$_POST['page_fid'] : 0;
	
	if ($pagename != '') 
	{
		//检查是否存在同名单页
		$query = sprintf('SELECT * FROM %spages WHERE PAGE_NAME = "%s"',
					
					DB_TBL_PREFIX,
					mysql_real_escape_string($pagename, $GLOBALS['DB'])); 
					
		mysql_query("set names 'utf8'");
		$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		$rows = mysql_num_rows($result);
		mysql_free_result($result);
		
		//如果不存在同名单页则新增单页
		if($rows == 0)
		{
			$query = sprintf('INSERT INTO %spages (FAT_ID, PAGE_NAME) VALUES'.
					'(%d, "%s")',
					
					DB_TBL_PREFIX,
					$fat_id,
					mysql_real_escape_string($pagename, $GLOBALS['DB']));

			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo "<script>location.href='page_manage.php';</script>";
		}
		else
		{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo "<script>alert('系统中已有同名单页!');location.href='page_manage.php';</script>";
		}
	}
	else 
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo "<script>alert('单页名称不能为空!');location.href='page_manage.php';</script>";
	}
}
//如果是编辑单页名称或级别
else if (isset($_POST['pageid']) && ($_POST['pageid'] != 0))  
{
	$pagename = (isset($_POST['pagename'])) ? trim($_POST['pagename']) : '';
	$fat_id = (isset($_POST['page_fid'])) ? (int)$_POST['page_fid'] : 0;
	$pageid = $_POST['pageid'];

	if ($pagename != '') 
	{
		//检查是否存在同名单页
		$query = sprintf('SELECT * FROM %spages WHERE PAGE_NAME = "%s" AND ID !=%d',
					
					DB_TBL_PREFIX,
					mysql_real_escape_string($pagename, $GLOBALS['DB']),
					$pageid); 
					
		mysql_query("set names 'utf8'");
		$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		$rows = mysql_num_rows($result);
		mysql_free_result($result);
	
		//如果不存在同名单页则更新单页
		if($rows == 0)
		{
			$query = sprintf('UPDATE %spages SET '.
					 'PAGE_NAME = "%s",
					  FAT_ID = %d
					  WHERE 
					  ID = %d',
					  
					  DB_TBL_PREFIX,
					  mysql_real_escape_string($pagename, $GLOBALS['DB']),
					  $fat_id,
					  $pageid);
			 
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo "<script>location.href='page_manage.php';</script>";
		}
		else
		{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo "<script>alert('系统中已有同名单页!');location.href='page_manage.php';</script>";
		}
	}
	else 
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo "<script>alert('单页名称不能为空!');location.href='page_manage.php";
		echo "?pagename=" . $pagename . "&pageid=" . $pageid ."';</script>";
	}
}
	  
//如果是编辑单页内容	  
if (isset($_GET['action']) && ($_GET['action'] == 'edit'))
{
	$title = (isset($_POST['title'])) ? trim($_POST['title']) : '';
	$desc = (isset($_POST['desc'])) ? trim($_POST['desc']) : '';
	$keys = (isset($_POST['keys'])) ? trim($_POST['keys']) : '';
	$content = (isset($_POST['page_content'])) ? trim($_POST['page_content']) : '';
	$page_id = $_POST['page_id'];
	
	//如果是中英双语版则接收英文字段值
	if (IS_CH_EN == 1)
	{
		$title_en = (isset($_POST['title_en'])) ? trim($_POST['title_en']) : '';
		$desc_en = (isset($_POST['desc_en'])) ? trim($_POST['desc_en']) : '';
		$keys_en = (isset($_POST['keys_en'])) ? trim($_POST['keys_en']) : '';
		$content_en = (isset($_POST['page_content_en'])) ? trim($_POST['page_content_en']) : '';
	}
	
	//如果是中文版则只更新中文字段  否则同时更新英文字段内容
	if (IS_CH_EN == 0)
	{
		$query = sprintf('UPDATE %spages SET '.
					 'PAGE_TITLE = "%s",
					  PAGE_DESCRIPTION = "%s",
					  PAGE_KEYWORDS = "%s",
					  PAGE_CONTENT = "%s"
					  WHERE
					  ID = %d',
					  
					  DB_TBL_PREFIX,
					  mysql_real_escape_string($title, $GLOBALS['DB']),
					  mysql_real_escape_string($desc, $GLOBALS['DB']),
					  mysql_real_escape_string($keys, $GLOBALS['DB']),
					  mysql_real_escape_string($content, $GLOBALS['DB']),
					  $page_id);
			 
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	}
	else
	{
		$query = sprintf('UPDATE %spages SET '.
					 'PAGE_TITLE = "%s",
					  PAGE_TITLE_EN = "%s",
					  PAGE_DESCRIPTION = "%s",
					  PAGE_DESCRIPTION_EN = "%s",
					  PAGE_KEYWORDS = "%s",
					  PAGE_KEYWORDS_EN = "%s",
					  PAGE_CONTENT = "%s",
					  PAGE_CONTENT_EN = "%s"
					  WHERE 
					  ID = %d',
					  
					  DB_TBL_PREFIX,
					  mysql_real_escape_string($title, $GLOBALS['DB']),
					  mysql_real_escape_string($title_en, $GLOBALS['DB']),
					  mysql_real_escape_string($desc, $GLOBALS['DB']),
					  mysql_real_escape_string($desc_en, $GLOBALS['DB']),
					  mysql_real_escape_string($keys, $GLOBALS['DB']),
					  mysql_real_escape_string($keys_en, $GLOBALS['DB']),
					  mysql_real_escape_string($content, $GLOBALS['DB']),
					  mysql_real_escape_string($content_en, $GLOBALS['DB']),
					  $page_id);
			 
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	}
	
		echo "<script>alert('编辑内容成功!');location.href='page_manage.php';</script>";
}
	  
//如果是删除单页
if (isset($_GET['action']) && ($_GET['action'] == 'del'))
{
	//调用递归方法删除分类函数
	del_pages($_GET['pageid']);
}
?>