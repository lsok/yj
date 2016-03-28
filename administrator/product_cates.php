<?php
include '401.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/Constant.php';
include 'includes/functions.php';

if (isset($_POST['submitted'])) {//如果用户提交了表单则处理用户请求
  
  if (isset($_POST['cateid']) && ($_POST['cateid'] == 0)) {//如果是添加分类
	
	if (IS_CH_EN == 0) { //如果仅为中文版
		$catename = (isset($_POST['catename'])) ? trim($_POST['catename']) : '';
		$fat_id = (isset($_POST['cate_fid'])) ? (int)$_POST['cate_fid'] : 0;
		
		if ($catename != '') {
			$query = sprintf('INSERT INTO %sproducts_cates (FAT_ID, CATENAME) VALUES'.
					'(%d, "%s")',
					DB_TBL_PREFIX,
					$fat_id,
					mysql_real_escape_string($catename, $GLOBALS['DB']));
	 
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			//获取新增类别的ID
			$new_cat_id = mysql_insert_id($GLOBALS['DB']);
			
			//更新新增类别的排序字段值 即:ORDERID
			$query = sprintf('UPDATE %sproducts_cates SET ' .
							'ORDERID = %d
							 WHERE
							 ID = %d',
							 
							 DB_TBL_PREFIX,
							 $new_cat_id,
							 $new_cat_id
							 );
							 
			 mysql_query("set names 'utf8'");
			 mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			//检查当前分类中是否已有数据 如有数据 将其移动到刚添加的子类别中
			$query = sprintf('SELECT * FROM %sproducts WHERE ' .
							'CAT_ID = %d',
							
							DB_TBL_PREFIX,
							$fat_id);
					
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
					
			if (mysql_num_rows($result))
			{
			   //定义分类中是否已有数据的变量
			   $have_data = true;
			   
			   
				//定义包含当前分类中原有数据的ID组成的数组  准备修改它们的类别属性
				$old_record_ids = array();
				
				while ($row = mysql_fetch_array($result))
				{
					$old_record_ids[] = $row['ID'];
				}
				
				mysql_free_result($result);
				
				//将ID数组转换为以逗号分割的字符串
				$old_ids_string = implode(',', $old_record_ids);
				
				//更新原数据的类别ID
				$query = sprintf ('UPDATE %sproducts SET ' .
							'CAT_ID = %d
							WHERE
							ID IN (%s)',
							
							DB_TBL_PREFIX,
							$new_cat_id,
							$old_ids_string
							);  
							
				mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			}
			
			if (isset($have_data) && $have_data = true)
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('子类添加成功! 原分类中的数据已自动移入新添加的子类中.');location.href='product_cates.php';</script>";
			}
			else
			{
				echo "<script>location.href='product_cates.php';</script>";
			}
		}
		else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo "<script>alert('分类名称不能为空!');location.href='product_cates.php';</script>";
		}
	}
	else 
	{  //如果是中英双语版，同时写入分类英文名称
		$catename = (isset($_POST['catename'])) ? trim($_POST['catename']) : '';
		$catename_en = (isset($_POST['catename_en'])) ? trim($_POST['catename_en']) : '';
		$fat_id = (isset($_POST['cate_fid'])) ? (int)$_POST['cate_fid'] : 0;
		
		if ($catename != '' && $catename_en != '') {
		$query = sprintf('INSERT INTO %sproducts_cates (FAT_ID, CATENAME, CATENAME_EN) VALUES'.
				'(%d, "%s", "%s")',
				DB_TBL_PREFIX,
				$fat_id,
				mysql_real_escape_string($catename, $GLOBALS['DB']),
				mysql_real_escape_string($catename_en, $GLOBALS['DB']));
		
		mysql_query("set names 'utf8'");
	   mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
			//获取新插入数据的ID
			$new_cat_id = mysql_insert_id($GLOBALS['DB']);
			
			//检查当前分类中是否已有数据 如有数据 将其移动到刚添加的子类别中
			$query = sprintf('SELECT * FROM %sproducts WHERE ' .
							'CAT_ID = %d',
							
							DB_TBL_PREFIX,
							$fat_id);
					
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
					
			if (mysql_num_rows($result))
			{
			   //定义分类中是否已有数据的变量
			   $have_data = true;
			   
				//定义包含当前分类中原有数据的ID组成的数组  准备修改它们的类别属性
				$old_record_ids = array();
				
				while ($row = mysql_fetch_array($result))
				{
					$old_record_ids[] = $row['ID'];
				}
				
				mysql_free_result($result);
				
				//将ID数组转换为以逗号分割的字符串
				$old_ids_string = implode(',', $old_record_ids);
				
				//更新原数据的类别ID
				$query = sprintf ('UPDATE %sproducts SET ' .
							'CAT_ID = %d
							WHERE
							ID IN (%s)',
							
							DB_TBL_PREFIX,
							$new_cat_id,
							$old_ids_string
							);  
							
				mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			}
			
			if (isset($have_data) && $have_data = true)
			{
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('子类添加成功! 原分类中的数据已自动移入新添加的子类中.');location.href='product_cates.php';</script>";
			}
			else
			{
				echo "<script>location.href='product_cates.php';</script>";
			}
		}
		else 
		{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo "<script>alert('分类名称不能为空!');location.href='product_cates.php';</script>";		
		}
	 }
  }
  else {//如果是编辑分类
  		if (IS_CH_EN == 0) { //如果仅为中文版
		$catename = (isset($_POST['catename'])) ? trim($_POST['catename']) : '';
		$fat_id = (isset($_POST['cate_fid'])) ? (int)$_POST['cate_fid'] : 0;
		$cateid = (isset($_POST['cateid'])) ? (int)$_POST['cateid'] : 0;
		
		if ($catename != '') {
			$query = sprintf('UPDATE %sproducts_cates SET '.
			         'CATENAME = "%s",
					  FAT_ID = %d
					  WHERE 
					  ID = %d',
					  
					  DB_TBL_PREFIX,
					  mysql_real_escape_string($catename, $GLOBALS['DB']),
					  $fat_id,
	 				  $cateid);
			 
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo "<script>location.href='product_cates.php';</script>";
		}
		else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo "<script>alert('分类名称不能为空!');location.href='product_cates.php";
			echo "?action=edit&catename=" . $catename . "&cateid=" . $cateid ."';</script>";
		}
	  }
	  else {//编辑分类 如果是中英双语版，同时写入分类英文名称
	  	$catename = (isset($_POST['catename'])) ? trim($_POST['catename']) : '';
		$catename_en = (isset($_POST['catename_en'])) ? trim($_POST['catename_en']) : '';
		$fat_id = (isset($_POST['cate_fid'])) ? (int)$_POST['cate_fid'] : 0;
		$cateid = (isset($_POST['cateid'])) ? (int)$_POST['cateid'] : 0;
		
		if ($catename != '' && $catename_en != '') {
		
			$query = sprintf('UPDATE %sproducts_cates SET '.
			         'CATENAME = "%s",
					  CATENAME_EN = "%s",
					  FAT_ID = %d
					  WHERE 
					  ID = %d',
					  
					  DB_TBL_PREFIX,
					  mysql_real_escape_string($catename, $GLOBALS['DB']),
					  mysql_real_escape_string($catename_en, $GLOBALS['DB']),
					  $fat_id,
	 				  $cateid);
			 
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			echo "<script>location.href='product_cates.php';</script>";
		}
		else {
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo "<script>alert('分类名称不能为空!');location.href='product_cates.php";
			echo "?action=edit&catename=" . $catename . "&catename_en=" . $catename_en . "&cateid=" . $cateid ."';</script>";
		}
	 }
  }
}

//如果是删除分类
if (isset($_GET['action']) && ($_GET['action'] == 'del')) {

	//调用递归方法删除分类函数
	del_all_cates($_GET['cateid'], 'products_cates', 'products', 'product_cates.php');
}

//如果是移动分类
if (isset($_GET['action']) && ($_GET['action'] == 'move'))
{
	//接收要移动类别的排序ID 即: ORDERID
	$moveCateID = $_GET['orderid'];
	
	//根据上移或下移动作确定比较运算符和查询结果排序规则
	switch ($_GET['to']) 
	{
		case 'up':
		$compare = '<';
		$result_order = 'DESC';
		break;
		
		case 'down':
		$compare = '>';
		$result_order = 'ASC';
		break;
	}
	
	//互换类别的排序ID以实现上下移动
	$query = sprintf('SELECT ID, ORDERID FROM %sproducts_cates WHERE FAT_ID = 0 AND ORDERID ' . $compare . ' %d ORDER BY ORDERID ' . $result_order, DB_TBL_PREFIX, $moveCateID); 
			
	mysql_query("set names 'utf8'");
	$ids_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$targetCate = mysql_fetch_array($ids_result);
	$targetCateID = $targetCate['ORDERID']; //要互换的类别的排序ID 即: ORDERID
	$targetCate_ID = $targetCate['ID']; //要互换的类别ID
	
	mysql_free_result($ids_result);
	
	//互换排序ID值
	$query = sprintf('UPDATE %sproducts_cates SET ' .
					'ORDERID = %d
					 WHERE
					 ORDERID = %d',
					 
					 DB_TBL_PREFIX,
					 $targetCateID,
					 $moveCateID
					 );
					 
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$query = sprintf('UPDATE %sproducts_cates SET ' .
					'ORDERID = %d
					 WHERE
					 ID = %d',
					 
					 DB_TBL_PREFIX,
					 $moveCateID,
					 $targetCate_ID
					 );
					 
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>location.href="product_cates.php";</script>';	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理文章分类</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script type="text/javascript" src="js/formsubmit.js"></script>
<script language="javascript">
function checkdel()
{if (confirm("删除分类将同时删除其分类下的数据,确实要删除吗？"))
     {return (true);}
     else
     {return (false);}
}

function check_cate_form()
{
	//验证类别中文名称
	if (document.cate_form.catename.value == "" ) 
	{ 
		alert ("类别名称不能为空!"); 
		return false; 
	}
	
	//如果为中英双语版则验证英文类别名称
	if("catename_en" in document.cate_form)
	{
		if (document.cate_form.catename_en.value == "" )
		{
		alert ("类别英文名称不能为空!"); 
		return false;
		}
	}
}
</script>
</head>
<body>
<div id="wrap">
<h1>产品分类管理</h1>
<?php
//检索所有分类
$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN, ORDERID FROM %sproducts_cates ORDER BY ORDERID ASC',
				 DB_TBL_PREFIX); 
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result)) {//如果存在分类

	$arr = array(); //定义储存所有分类的数组
	
	while ($row = mysql_fetch_assoc($result)) { 
	 
			//数组的每一项保存一个分类的id,父id和名称(英文名称)  
			$arr[] = array($row['ID'],$row['FAT_ID'],$row['CATENAME'],$row['CATENAME_EN'],$row['ORDERID']); 
	} 
	
	//输出所有分类
	$isen = IS_CH_EN; // IS_CH_EN为Constant.php中定义的常量 定义是否为中英双语版
	echo '<div class="cates">';
	show_cates($father_id = 0, 'product_cates.php', $isen); //调用递归方法输出分类函数
	echo '</div>';
}
else {
	echo '<p class="tip_cates"><img src="images/alert.gif" />暂无分类信息!</p>';
}
mysql_free_result($result);
?>
<?php
//如果是编辑分类 设置表单中的类别名称和类别id
$catename = (isset($_GET['action']) && ($_GET['action'] == 'edit')) ? $_GET['catename'] : '';
if (IS_CH_EN == 1) {
$catename_en = (isset($_GET['action']) && ($_GET['action'] == 'edit')) ? $_GET['catename_en'] : '';
}
$cateid = (isset($_GET['action']) && ($_GET['action'] == 'edit')) ? (int)$_GET['cateid'] : 0;
$catekind = (isset($_GET['action']) && ($_GET['action'] == 'edit')) ? '编辑分类' : '添加分类';
?>
<!--添加与编辑分类表单-->
<fieldset>
<legend>添加/编辑分类:</legend>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" name="cate_form" onsubmit="return check_cate_form()">
<table border="0" class="formtable cates_form">
<tr><td class="label"><label>类别名称</label></td><td><input type="text" name="catename" id="catename" value="<?php echo $catename; ?>" /></td></tr>
<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label>英文名称</label></td><td><input type="text" name="catename_en" id="catename_en" value="<?php echo $catename_en; ?>" /></td></tr>
<?php } ?>
<tr><td class="label"><label>上级分类</label></td><td>
<select name="cate_fid">
<option value="0">作为一级分类</option>
<?php
//取出所有类别生成选项
$query = sprintf('SELECT ID, CATENAME FROM %sproducts_cates ORDER BY ID ASC',
				  DB_TBL_PREFIX);
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result)) {
	while ($row = mysql_fetch_array($result))
	{
	echo '<option value="' . $row['ID'] . '">' . htmlspecialchars($row['CATENAME']) . '</option>';
	}
}
mysql_free_result($result);
?>
</select>
</td></tr>
<tr><td colspan="2">
<input type="submit" name="submit" value="<?php echo $catekind; ?>" id="submitbutton" class="submit" />
<input type="hidden" value="<?php echo $cateid; ?>" name="cateid" />
<input type="hidden" name="submitted" value="true" />
</td></tr>
</table>
</form>
</fieldset>
</div>
</body>
</html>