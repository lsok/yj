<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//如果是新增产品
if (isset($_GET['add'])) 
{
	$pro_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];	
	$pro_name = (isset($_POST['pro_name'])) ? trim($_POST['pro_name']) : '';
	$pro_type = (isset($_POST['pro_type'])) ? trim($_POST['pro_type']) : '';
	$pro_price = (isset($_POST['pro_price']) && trim($_POST['pro_price']) != '') ? trim($_POST['pro_price']) : '0.00';
	$pro_img = (isset($_POST['pro_img'])) ? trim($_POST['pro_img']) : '';
	$pro_content = (isset($_POST['pro_content'])) ? trim($_POST['pro_content']) : '';
	
	
	// 如果是中英文版则接收英文字段值
	if (IS_CH_EN == 1) 
	{
		$pro_name_en = (isset($_POST['pro_name_en'])) ? trim($_POST['pro_name_en']) : '';
		$pro_content_en = (isset($_POST['pro_content_en'])) ? trim($_POST['pro_content_en']) : '';
	}
	
	//如果只有中文版则写入新产品数据
	if (IS_CH_EN == 0) 
	{ 
		// 如果必填项不为空 则写入数据
		if ($pro_name != '' && $pro_cate_id  != '-1'  && $pro_img != '')
		{ 
			$query = sprintf ('INSERT INTO %sproducts (CAT_ID, PRO_NAME, PRO_TYPE, PRO_PRICE, PRO_IMAGE, PRO_CONTENT, SUBMIT_DATE) ' .
					'VALUES (%d, "%s", "%s", "%s", "%s", "%s", "%s")',
					DB_TBL_PREFIX,
					$pro_cate_id,
					mysql_real_escape_string($pro_name, $GLOBALS['DB']),
					mysql_real_escape_string($pro_type, $GLOBALS['DB']),
					mysql_real_escape_string($pro_price, $GLOBALS['DB']),
					mysql_real_escape_string($pro_img, $GLOBALS['DB']),
					mysql_real_escape_string($pro_content, $GLOBALS['DB']),
					date('Y-m-d H:i:s'));                
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("添加产品成功!");location.href="products_manage.php?list";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($pro_name == '')
			{
				$notempty = '产品名称不能为空!';
			}
			else if ($pro_cate_id  == '-1')
			{
				$notempty = '请选择产品类别!';
			}
			else if ($pro_img == '')
			{
				$notempty = '请上传产品图片!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
	else  //如果是中英文版则同时写入英文数据
	{
		// 如果必填项不为空 则写入数据
		if ($pro_name != '' && $pro_name_en != '' && $pro_cate_id  != '-1' && $pro_img != '')
		{ 
		
			$query = sprintf ('INSERT INTO %sproducts (CAT_ID, PRO_NAME, PRO_NAME_EN, PRO_TYPE, PRO_PRICE, PRO_IMAGE, PRO_CONTENT, PRO_CONTENT_EN, SUBMIT_DATE) ' .
					' VALUES (%d, "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s")',
					DB_TBL_PREFIX,
					$pro_cate_id,
					mysql_real_escape_string($pro_name, $GLOBALS['DB']),
					mysql_real_escape_string($pro_name_en, $GLOBALS['DB']),
					mysql_real_escape_string($pro_type, $GLOBALS['DB']),
					mysql_real_escape_string($pro_price, $GLOBALS['DB']),
					mysql_real_escape_string($pro_img, $GLOBALS['DB']),
					mysql_real_escape_string($pro_content, $GLOBALS['DB']),
					mysql_real_escape_string($pro_content_en, $GLOBALS['DB']),
					date('Y-m-d H:i:s')); 
		
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("添加产品成功!");location.href="products_manage.php?list";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($pro_name == '')
			{
				$notempty = '产品名称不能为空!';
			}
			else if ($pro_name_en == '')
			{
				$notempty = '产品英文名称不能为空!';
			}
			else if ($pro_cate_id  == '-1')
			{
				$notempty = '请选择产品类别!';
			}
			else if ($pro_img == '')
			{
				$notempty = '请上传产品图片!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
}

 //如果是编辑产品
if (isset($_GET['edit'])) 
{

	$pro_id = $_POST['pro_id']; // 接收要编辑的产品id
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
	
	//接收产品标题 内容 作者等字段值
	$pro_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];	
	$pro_name = (isset($_POST['pro_name'])) ? trim($_POST['pro_name']) : '';
	$pro_type = (isset($_POST['pro_type'])) ? trim($_POST['pro_type']) : '';
	$pro_price = (isset($_POST['pro_price']) && trim($_POST['pro_price']) != '') ? trim($_POST['pro_price']) : '0.00';
	$pro_img = (isset($_POST['pro_img'])) ? trim($_POST['pro_img']) : '';
	$pro_content = (isset($_POST['pro_content'])) ? trim($_POST['pro_content']) : '';

	
	
	// 如果是中英文版则接收英文字段值
	if (IS_CH_EN == 1) 
	{
		$pro_name_en = (isset($_POST['pro_name_en'])) ? trim($_POST['pro_name_en']) : '';
		$pro_content_en = (isset($_POST['pro_content_en'])) ? trim($_POST['pro_content_en']) : '';
	}
	
	//如果只有中文版则仅更新中文产品数据
	if (IS_CH_EN == 0) 
	{ 
		// 如果必填项不为空 则更新数据
		if ($pro_name != '' && $pro_cate_id  != '-1' && $pro_img != '')
		{ 
			$query = sprintf ('UPDATE %sproducts SET ' .
							   'CAT_ID = %d, 
							   PRO_NAME = "%s",
							   PRO_TYPE = "%s",
							   PRO_PRICE = "%s",
							   PRO_IMAGE = "%s",
							   PRO_CONTENT = "%s"
							   WHERE 
							   ID = %d',
							  
								DB_TBL_PREFIX,
								$pro_cate_id,
								mysql_real_escape_string($pro_name, $GLOBALS['DB']),
								mysql_real_escape_string($pro_type, $GLOBALS['DB']),
								mysql_real_escape_string($pro_price, $GLOBALS['DB']),
								mysql_real_escape_string($pro_img, $GLOBALS['DB']),
								mysql_real_escape_string($pro_content, $GLOBALS['DB']),
								$pro_id);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("编辑产品成功!");location.href="products_manage.php?list' . $redi_pars . '";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($pro_name == '')
			{
				$notempty = '产品名称不能为空!';
			}
			else if ($pro_cate_id  == '-1')
			{
				$notempty = '请选择产品类别!';
			}
			else if ($pro_img == '')
			{
				$notempty = '请上传产品图片!';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $notempty . '");history.back(-1);</script>';
		}
	}
	else  //如果是中英文版则同时更新英文数据
	{
		// 如果必填项不为空 则更新数据
		if ($pro_name != '' && $pro_name_en != '' && $pro_cate_id  != '-1' && $pro_img != '')
		{ 
		
			$query = sprintf ('UPDATE %sproducts SET ' .
							   'CAT_ID = %d, 
							   PRO_NAME = "%s",
							   PRO_NAME_EN = "%s",
							   PRO_TYPE = "%s",
							   PRO_PRICE = "%s",
							   PRO_IMAGE = "%s",
							   PRO_CONTENT = "%s",
							   PRO_CONTENT_EN = "%s"
							   WHERE 
							   ID = %d',
							  
								DB_TBL_PREFIX,
								$pro_cate_id,
								mysql_real_escape_string($pro_name, $GLOBALS['DB']),
								mysql_real_escape_string($pro_name_en, $GLOBALS['DB']),
								mysql_real_escape_string($pro_type, $GLOBALS['DB']),
								mysql_real_escape_string($pro_price, $GLOBALS['DB']),
								mysql_real_escape_string($pro_img, $GLOBALS['DB']),
								mysql_real_escape_string($pro_content, $GLOBALS['DB']),
								mysql_real_escape_string($pro_content_en, $GLOBALS['DB']),
								$pro_id); 
		
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("编辑产品成功!");location.href="products_manage.php?list' . $redi_pars . '";</script>';
		}
		else //如果必填项为空则提示错误
		{
			if ($pro_name == '')
			{
				$notempty = '产品名称不能为空!';
			}
			else if ($pro_name_en  == '')
			{
				$notempty = '产品英文名称不能为空!';
			}
			else if ($pro_cate_id  == '-1')
			{
				$notempty = '请选择产品类别!';
			}
			else if ($pro_img == '')
			{
				$notempty = '请上传产品图片!';
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
	$pro_id = $_GET['proid']; //接收产品id
	$isshowstat = $_GET['isshowstat']; //接收产品发布状态值
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
	
	$query = sprintf ('UPDATE %sproducts SET ' .
							   'IS_SHOW = %d
							   WHERE 
							   ID = ' . $pro_id,
							  
								DB_TBL_PREFIX,
								$is_show_st
								);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="products_manage.php?list' . $redi_pars . '";</script>';
}

 //切换首页显示状态
if (isset($_GET['ishome'])) 
{
	$pro_id = $_GET['proid']; //接收产品id
	$ishomestat = $_GET['ishomestat']; //接收产品是否首页显示值
	
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
	
	$query = sprintf ('UPDATE %sproducts SET ' .
							   'IS_HOMESHOW = %d
							   WHERE 
							   ID = ' . $pro_id,
							  
								DB_TBL_PREFIX,
								$is_home_st
								);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="products_manage.php?list' . $redi_pars . '";</script>';
}

//切换置顶状态
if (isset($_GET['istop'])) 
{
	$pro_id = $_GET['proid']; //接收产品id
	$istopstat = $_GET['istopstat']; //接收产品置顶状态值
	
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
	
	$query = sprintf ('UPDATE %sproducts SET ' .
							   'IS_TOP = %d
							   WHERE 
							   ID = ' . $pro_id,
							  
								DB_TBL_PREFIX,
								$is_top_st
								);                  
			
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="products_manage.php?list' . $redi_pars . '";</script>';
}

//删除一条记录  即进入回收站
if (isset($_GET['delone'])) 
{
	$pro_id = $_GET['proid']; //接收产品id
	
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
	
	$query = sprintf ('UPDATE %sproducts SET ' .
								'IS_DELETE = 1
								WHERE
								ID = %d',
								
								DB_TBL_PREFIX,
								$pro_id
								);          
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="products_manage.php?list' . $redi_pars . '";</script>';
}

//批量删除多条记录  即进入回收站
if (isset($_GET['delsome'])) 
{
	$del_ids = $_GET['del_ids']; //接收要删除的产品id  格式如 2,5,6,8
		
	$query = sprintf ('UPDATE %sproducts SET ' .
							'IS_DELETE = 1
							WHERE
							ID IN  (%s)',
							
							DB_TBL_PREFIX,
							$del_ids
							);          
			
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
			echo '<script>location.href="products_manage.php?list";</script>';
}

//从回收站中还原记录
if (isset($_GET['redu'])) 
{
	$pro_id = $_GET['proid']; //接收产品id
	
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
	
	$query = sprintf ('UPDATE %sproducts SET ' .
								'IS_DELETE = 0
								WHERE
								ID = %d',
								
								DB_TBL_PREFIX,
								$pro_id
								);          
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo '<script>location.href="products_manage.php?isdel' . $redi_pars . '";</script>';
}

//从回收站中彻底删除一条记录
if (isset($_GET['truedel'])) 
{
	$pro_id = $_GET['proid']; //接收产品id
	
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
	
	//获取产品图片路径　准备删除
	$query = sprintf ('SELECT PRO_IMAGE FROM %sproducts ' .
	                       'WHERE 
						        ID = %d',
								
								DB_TBL_PREFIX,
								$pro_id
								);
								
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));	
			$row = mysql_fetch_array($result);
			$pro_image = $row['PRO_IMAGE'];
			mysql_free_result($result);
	
	//删除产品数据
	$query = sprintf ('DELETE FROM %sproducts ' .
								'WHERE
								ID = %d',
								
								DB_TBL_PREFIX,
								$pro_id
								);          
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
	//删除产品图片
	unlink($pro_image);
	
			echo '<script>location.href="products_manage.php?isdel' . $redi_pars . '";</script>';
}

//从回收站中批量删除多条记录  彻底删除
if (isset($_GET['truedelsome'])) 
{
	$del_ids = $_GET['del_ids']; //接收要删除的产品id  格式如 2,5,6,8
	
	//获取多个产品图片的路径　准备删除
	$query = sprintf ('SELECT PRO_IMAGE FROM %sproducts ' .
	                       'WHERE 
						        ID IN  (%s)',
								
								DB_TBL_PREFIX,
								$del_ids
								);
								
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));	
	
	//删除多个产品数据
	$query = sprintf ('DELETE FROM %sproducts ' .
							'WHERE
							ID IN  (%s)',
							
							DB_TBL_PREFIX,
							$del_ids
							);          
			
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//删除多个产品图片
	while($row = mysql_fetch_array($result))
	{
		unlink($row['PRO_IMAGE']);
	}

	mysql_free_result($result);
	
			echo '<script>location.href="products_manage.php?isdel";</script>';
}

//清空回收站
if (isset($_GET['empty'])) 
{	
	//获取回收站中记录的产品图片路径　准备删除
	$query = sprintf ('SELECT PRO_IMAGE FROM %sproducts ' .
	                       'WHERE 
						        IS_DELETE = 1',
								
								DB_TBL_PREFIX
								);
								
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));	
	
	//删除多个图片数据
	$query = sprintf ('DELETE FROM %sproducts ' .
							'WHERE
							IS_DELETE = 1',
							
							DB_TBL_PREFIX
							);          
			
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//删除多个图片
	while($row = mysql_fetch_array($result))
	{
		unlink($row['PRO_IMAGE']);
	}

	mysql_free_result($result);
	
			echo '<script>location.href="products_manage.php?list";</script>';
}
?>