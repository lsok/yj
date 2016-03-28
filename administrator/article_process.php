<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//如果是新增文章
if (isset($_GET['add'])) 
{
	
	$art_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];	
	$art_title = (isset($_POST['art_title'])) ? trim($_POST['art_title']) : '';
	$art_content = (isset($_POST['art_content'])) ? trim($_POST['art_content']) : '';
	$author = (isset($_POST['author'])) ? trim($_POST['author']) : '';
	
	// 如果是中英文版则接收英文字段值
	if (IS_CH_EN == 1) 
	{
		$art_title_en = (isset($_POST['art_title_en'])) ? trim($_POST['art_title_en']) : '';
		$art_content_en = (isset($_POST['art_content_en'])) ? trim($_POST['art_content_en']) : '';
		$author_en = (isset($_POST['author_en'])) ? trim($_POST['author_en']) : '';
	}
	
	//如果只有中文版则写入新文章数据
	if (IS_CH_EN == 0) 
	{ 
		// 如果必填项不为空 则写入数据
		if ($art_title != '' && $art_cate_id  != '-1'  && $art_content != '' && $author != '')
		{ 
			$query = sprintf ('INSERT INTO %sarticles (CAT_ID, ART_TITLE, ART_CONTENT, AUTHOR, SUBMIT_DATE) ' .
					'VALUES (%d, "%s", "%s", "%s", "%s")',
					DB_TBL_PREFIX,
					$art_cate_id,
					mysql_real_escape_string($art_title, $GLOBALS['DB']),
					mysql_real_escape_string($art_content, $GLOBALS['DB']),
					mysql_real_escape_string($author, $GLOBALS['DB']),
					date('Y-m-d H:i:s'));                
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("添加文章成功!");location.href="articles_manage.php?list";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($art_title == '')
			{
				$notempty = '文章标题不能为空!';
			}
			else if ($art_cate_id  == '-1')
			{
				$notempty = '请选择文章所属类别!';
			}
			else if ($author == '')
			{
				$notempty = '文章作者不能为空!';
			}
			else if ($art_content == '')
			{
				$notempty = '文章内容不能为空!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
	else  //如果是中英文版则同时写入英文数据
	{
		// 如果必填项不为空 则写入数据
		if ($art_title != '' && $art_title_en != '' && $art_cate_id  != '-1' && $art_content != '' && $art_content_en != '' && $author != '' && $author_en != '')
		{ 
		
			$query = sprintf ('INSERT INTO %sarticles (CAT_ID, ART_TITLE, ART_TITLE_EN, ART_CONTENT, ART_CONTENT_EN, AUTHOR, AUTHOR_EN, SUBMIT_DATE) ' .
					' VALUES (%d, "%s", "%s", "%s", "%s", "%s", "%s", "%s")',
					DB_TBL_PREFIX,
					$art_cate_id,
					mysql_real_escape_string($art_title, $GLOBALS['DB']),
					mysql_real_escape_string($art_title_en, $GLOBALS['DB']),
					mysql_real_escape_string($art_content, $GLOBALS['DB']),
					mysql_real_escape_string($art_content_en, $GLOBALS['DB']),
					mysql_real_escape_string($author, $GLOBALS['DB']),
					mysql_real_escape_string($author_en, $GLOBALS['DB']),
					date('Y-m-d H:i:s')); 
		
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("添加文章成功!");location.href="articles_manage.php?list";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($art_title == '')
			{
				$notempty = '文章标题不能为空!';
			}
			else if ($art_title_en == '')
			{
				$notempty = '文章英文标题不能为空!';
			}
			else if ($art_cate_id  == '-1')
			{
				$notempty = '请选择文章所属类别!';
			}
			else if ($author == '')
			{
				$notempty = '文章作者不能为空!';
			}
			else if ($author_en == '')
			{
				$notempty = '文章英文作者不能为空!';
			}
			else if ($art_content == '')
			{
				$notempty = '文章内容不能为空!';
			}
			else if ($art_content_en == '')
			{
				$notempty = '文章英文内容不能为空!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
}

 //如果是编辑文章
if (isset($_GET['edit'])) 
{

	$art_id = $_POST['art_id']; // 接收要编辑的文章id
	$page = $_POST['page']; // 接收页码以便在编辑完成后跳转
	
	//定义编辑完成后跳转URL中的参数
	$redi_pars = '&page=' . $page;
	
	//如果用户在筛选类别后进行编辑操作 则接收类别ID 并在编辑完成后跳转回该类别列表
	if (isset($_POST['kind']))
	{
		$redi_pars .= '&kind=' . $_POST['kind'];
	}
	
	//如果用户在筛选状态后进行编辑操作 则接收状态值 并在编辑完成后跳转回该状态列表
	if (isset($_POST['item_stat']))
	{
		$redi_pars .= '&item_stat=' . $_POST['item_stat'];
	}
	
	//如果用户在执行搜索后进行编辑操作 则接收搜索关键词 并在编辑完成后跳转回该搜索结果列表
	if (isset($_POST['keyword']))
	{
		$redi_pars .= '&keyword=' . $_POST['keyword'];
	}
	
	//接收文章标题 内容 作者等字段值
	$art_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];	
	$art_title = (isset($_POST['art_title'])) ? trim($_POST['art_title']) : '';
	$art_content = (isset($_POST['art_content'])) ? trim($_POST['art_content']) : '';
	$author = (isset($_POST['author'])) ? trim($_POST['author']) : '';
	
	// 如果是中英文版则接收英文字段值
	if (IS_CH_EN == 1) 
	{
		$art_title_en = (isset($_POST['art_title_en'])) ? trim($_POST['art_title_en']) : '';
		$art_content_en = (isset($_POST['art_content_en'])) ? trim($_POST['art_content_en']) : '';
		$author_en = (isset($_POST['author_en'])) ? trim($_POST['author_en']) : '';
	}
	
	//如果只有中文版则仅更新中文文章数据
	if (IS_CH_EN == 0) 
	{ 
		// 如果必填项不为空 则更新数据
		if ($art_title != '' && $art_cate_id  != '-1'  && $art_content != '' && $author != '')
		{ 
			$query = sprintf ('UPDATE %sarticles SET ' .
							   'CAT_ID = %d, 
							   ART_TITLE = "%s",
							   ART_CONTENT = "%s",
							   AUTHOR = "%s"
							   WHERE 
							   ID = ' . $art_id,
							  
								DB_TBL_PREFIX,
								$art_cate_id,
								mysql_real_escape_string($art_title, $GLOBALS['DB']),
								mysql_real_escape_string($art_content, $GLOBALS['DB']),
								mysql_real_escape_string($author, $GLOBALS['DB']),
								date('Y-m-d H:i:s'));                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("编辑文章成功!");location.href="articles_manage.php?list' . $redi_pars . '";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($art_title == '')
			{
				$notempty = '文章标题不能为空!';
			}
			else if ($art_cate_id  == '-1')
			{
				$notempty = '请选择文章所属类别!';
			}
			else if ($author == '')
			{
				$notempty = '文章作者不能为空!';
			}
			else if ($art_content == '')
			{
				$notempty = '文章内容不能为空!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
	else  //如果是中英文版则同时更新英文数据
	{
		// 如果必填项不为空 则更新数据
		if ($art_title != '' && $art_title_en != '' && $art_cate_id  != '-1' && $art_content != '' && $art_content_en != '' && $author != '' && $author_en != '')
		{ 
		
			$query = sprintf ('UPDATE %sarticles SET ' .
							   'CAT_ID = %d, 
							   ART_TITLE = "%s",
							   ART_TITLE_EN = "%s",
							   ART_CONTENT = "%s",
							   ART_CONTENT_EN = "%s",
							   AUTHOR = "%s",
							   AUTHOR_EN = "%s"
							   WHERE 
							   ID = ' . $art_id,
							  
								DB_TBL_PREFIX,
								$art_cate_id,
								mysql_real_escape_string($art_title, $GLOBALS['DB']),
								mysql_real_escape_string($art_title_en, $GLOBALS['DB']),
								mysql_real_escape_string($art_content, $GLOBALS['DB']),
								mysql_real_escape_string($art_content_en, $GLOBALS['DB']),
								mysql_real_escape_string($author, $GLOBALS['DB']),
								mysql_real_escape_string($author_en, $GLOBALS['DB']),
								date('Y-m-d H:i:s')); 
		
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("编辑文章成功!");location.href="articles_manage.php?list' . $redi_pars . '";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($art_title == '')
			{
				$notempty = '文章标题不能为空!';
			}
			else if ($art_title_en == '')
			{
				$notempty = '文章英文标题不能为空!';
			}
			else if ($art_cate_id  == '-1')
			{
				$notempty = '请选择文章所属类别!';
			}
			else if ($author == '')
			{
				$notempty = '文章作者不能为空!';
			}
			else if ($author_en == '')
			{
				$notempty = '文章英文作者不能为空!';
			}
			else if ($art_content == '')
			{
				$notempty = '文章内容不能为空!';
			}
			else if ($art_content_en == '')
			{
				$notempty = '文章英文内容不能为空!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
}


 //切换发布状态
if (isset($_GET['isshow'])) 
{
	$art_id = $_GET['artid']; //接收文章id
	$isshowstat = $_GET['isshowstat']; //接收文章发布状态值
	$page = $_GET['page']; //接收页码以便执行操作后跳转
	
	//定义操作完成后跳转URL中的参数
	$redi_pars = '&page=' . $page; 
	
	if (isset($_GET['kind'])) //如果用户先筛选了类别然后再切换状态  则传递类别参数以便跳转回该类别列表
	{
		$redi_pars .= '&kind=' . $_GET['kind'];
	}

	if (isset($_GET['item_stat'])) //如果用户先筛选了状态然后再切换状态  则传递状态参数以便跳转回该状态列表
	{
		$redi_pars .= '&item_stat=' . $_GET['item_stat'];
	}

	if (isset($_GET['keyword'])) //如果用户执行了搜索然后再切换状态  则传递关键词参数以便跳转回该搜索结果列表
	{
		$redi_pars .= '&keyword=' . $_GET['keyword'];
	}
	
	$is_show_st = ($isshowstat == 0) ? 1 : 0; //切换发布状态值
	
	$query = sprintf ('UPDATE %sarticles SET ' .
							   'IS_SHOW = %d
							   WHERE 
							   ID = ' . $art_id,
							  
								DB_TBL_PREFIX,
								$is_show_st
								);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="articles_manage.php?list' . $redi_pars . '";</script>';
}

 //切换首页显示状态
if (isset($_GET['ishome'])) 
{
	$art_id = $_GET['artid']; //接收文章id
	$ishomestat = $_GET['ishomestat']; //接收文章是否首页显示值
	
	$page = $_GET['page']; //接收页码以便执行操作后跳转
	
	//定义操作完成后跳转URL中的参数
	$redi_pars = '&page=' . $page; 
	
	if (isset($_GET['kind'])) //如果用户先筛选了类别然后再切换状态  则传递类别参数以便跳转回该类别列表
	{
		$redi_pars .= '&kind=' . $_GET['kind'];
	}

	if (isset($_GET['item_stat'])) //如果用户先筛选了状态然后再切换状态  则传递状态参数以便跳转回该状态列表
	{
		$redi_pars .= '&item_stat=' . $_GET['item_stat'];
	}

	if (isset($_GET['keyword'])) //如果用户执行了搜索然后再切换状态  则传递关键词参数以便跳转回该搜索结果列表
	{
		$redi_pars .= '&keyword=' . $_GET['keyword'];
	}
	
	$is_home_st = ($ishomestat == 0) ? 1 : 0; //切换首页显示状态值
	
	$query = sprintf ('UPDATE %sarticles SET ' .
							   'IS_HOMESHOW = %d
							   WHERE 
							   ID = ' . $art_id,
							  
								DB_TBL_PREFIX,
								$is_home_st
								);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="articles_manage.php?list' . $redi_pars . '";</script>';
}

//切换置顶状态
if (isset($_GET['istop'])) 
{
	$art_id = $_GET['artid']; //接收文章id
	$istopstat = $_GET['istopstat']; //接收文章置顶状态值
	
	$page = $_GET['page']; //接收页码以便执行操作后跳转
	
	//定义操作完成后跳转URL中的参数
	$redi_pars = '&page=' . $page; 
	
	if (isset($_GET['kind'])) //如果用户先筛选了类别然后再切换状态  则传递类别参数以便跳转回该类别列表
	{
		$redi_pars .= '&kind=' . $_GET['kind'];
	}

	if (isset($_GET['item_stat'])) //如果用户先筛选了状态然后再切换状态  则传递状态参数以便跳转回该状态列表
	{
		$redi_pars .= '&item_stat=' . $_GET['item_stat'];
	}

	if (isset($_GET['keyword'])) //如果用户执行了搜索然后再切换状态  则传递关键词参数以便跳转回该搜索结果列表
	{
		$redi_pars .= '&keyword=' . $_GET['keyword'];
	}
	
	$is_top_st = ($istopstat == 0) ? 1 : 0; //切换置顶状态值
	
	$query = sprintf ('UPDATE %sarticles SET ' .
							   'IS_TOP = %d
							   WHERE 
							   ID = ' . $art_id,
							  
								DB_TBL_PREFIX,
								$is_top_st
								);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="articles_manage.php?list' . $redi_pars . '";</script>';
}

//删除一条记录  即进入回收站
if (isset($_GET['delone'])) 
{
	$art_id = $_GET['artid']; //接收文章id
	
	$page = $_GET['page']; //接收页码以便执行操作后跳转
	
	//定义操作完成后跳转URL中的参数
	$redi_pars = '&page=' . $page; 
	
	if (isset($_GET['kind'])) //如果用户先筛选了类别然后再执行删除操作  则传递类别参数以便跳转回该类别列表
	{
		$redi_pars .= '&kind=' . $_GET['kind'];
	}

	if (isset($_GET['item_stat'])) //如果用户先筛选了状态然后再执行删除操作  则传递状态参数以便跳转回该状态列表
	{
		$redi_pars .= '&item_stat=' . $_GET['item_stat'];
	}

	if (isset($_GET['keyword'])) //如果用户执行了搜索然后再执行删除操作  则传递关键词参数以便跳转回该搜索结果列表
	{
		$redi_pars .= '&keyword=' . $_GET['keyword'];
	}
	
	$query = sprintf ('UPDATE %sarticles SET ' .
								'IS_DELETE = 1
								WHERE
								ID = %d',
								
								DB_TBL_PREFIX,
								$art_id
								);          
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="articles_manage.php?list' . $redi_pars . '";</script>';
}

//批量删除多条记录  即进入回收站
if (isset($_GET['delsome'])) 
{
	$del_ids = $_GET['del_ids']; //接收要删除的文章id  格式如 2,5,6,8
		
	$query = sprintf ('UPDATE %sarticles SET ' .
							'IS_DELETE = 1
							WHERE
							ID IN  (%s)',
							
							DB_TBL_PREFIX,
							$del_ids
							);          
			
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
			echo '<script>location.href="articles_manage.php?list";</script>';
}

//从回收站中还原记录
if (isset($_GET['redu'])) 
{
	$art_id = $_GET['artid']; //接收文章id
	
	$page = $_GET['page']; //接收页码以便执行操作后跳转
	
	//定义操作完成后跳转URL中的参数
	$redi_pars = '&page=' . $page; 
	
	if (isset($_GET['kind'])) //如果用户先筛选了类别然后再执行还原操作  则传递类别参数以便跳转回该类别列表
	{
		$redi_pars .= '&kind=' . $_GET['kind'];
	}

	if (isset($_GET['keyword'])) //如果用户执行了搜索然后再执行还原操作  则传递关键词参数以便跳转回该搜索结果列表
	{
		$redi_pars .= '&keyword=' . $_GET['keyword'];
	}
	
	$query = sprintf ('UPDATE %sarticles SET ' .
								'IS_DELETE = 0
								WHERE
								ID = %d',
								
								DB_TBL_PREFIX,
								$art_id
								);          
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="articles_manage.php?isdel' . $redi_pars . '";</script>';
}

//从回收站中彻底删除一条记录
if (isset($_GET['truedel'])) 
{
	$art_id = $_GET['artid']; //接收文章id
	
	$page = $_GET['page']; //接收页码以便执行操作后跳转
	
	//定义操作完成后跳转URL中的参数
	$redi_pars = '&page=' . $page; 
	
	if (isset($_GET['kind'])) //如果用户先筛选了类别然后再彻底删除  则传递类别参数以便跳转回该类别列表
	{
		$redi_pars .= '&kind=' . $_GET['kind'];
	}

	if (isset($_GET['keyword'])) //如果用户执行了搜索然后再彻底删除  则传递关键词参数以便跳转回该搜索结果列表
	{
		$redi_pars .= '&keyword=' . $_GET['keyword'];
	}
	
	$query = sprintf ('DELETE FROM %sarticles ' .
								'WHERE
								ID = %d',
								
								DB_TBL_PREFIX,
								$art_id
								);          
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="articles_manage.php?isdel' . $redi_pars . '";</script>';
}

//从回收站中批量删除多条记录  彻底删除
if (isset($_GET['truedelsome'])) 
{
	$del_ids = $_GET['del_ids']; //接收要删除的文章id  格式如 2,5,6,8
		
	$query = sprintf ('DELETE FROM %sarticles ' .
							'WHERE
							ID IN  (%s)',
							
							DB_TBL_PREFIX,
							$del_ids
							);          
			
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
			echo '<script>location.href="articles_manage.php?isdel";</script>';
}

//清空回收站
if (isset($_GET['empty'])) 
{		
	$query = sprintf ('DELETE FROM %sarticles ' .
							'WHERE
							IS_DELETE =1',
							
							DB_TBL_PREFIX
							);          
			
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
			echo '<script>location.href="articles_manage.php?list";</script>';
}
?>