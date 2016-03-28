<?php
include '401.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/Constant.php';
include 'includes/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理单页</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script type="text/javascript" src="js/formsubmit.js"></script>
<script language="javascript">
function checkdel()
{if (confirm("删除单页将同时删除其可能存在的所有子页面,确实要删除吗?"))
     {return (true);}
     else
     {return (false);}
}

function check_page_form()
{
	//验证类别中文名称
	if (document.page_form.pagename.value == "" ) 
	{ 
		alert ("单页名称不能为空!"); 
		return false; 
	}
}
</script>
</head>
<body>
<div id="wrap">
<h1>单页管理</h1>
<?php
//检索所有单页
$query = sprintf('SELECT ID, FAT_ID, PAGE_NAME FROM %spages ORDER BY ID ASC',
				 DB_TBL_PREFIX); 
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result)) {

	$arr = array(); //定义储存所有单页的数组
	
	while ($row = mysql_fetch_assoc($result)) { 
	 
			//数组的每一项保存一个分类的id,父id和名称(英文名称)  
			$arr[] = array($row['ID'],$row['FAT_ID'],$row['PAGE_NAME']); 
	} 
		
	//输出所有单页
	echo '<div class="cates">';
	show_pages($father_id = 0);
	echo '</div>';
}
else 
{
	echo '<p class="tip_cates"><img src="images/alert.gif" />暂无单页数据!</p>';
}
mysql_free_result($result);
?>
<?php
//如果是编辑单页级别 设置表单中的单页名称和单页id  注意,如是新增单页,隐藏域pageid被设置为0
$pagename = (isset($_GET['pagename'])) ? $_GET['pagename'] : '';
$pageid = (isset($_GET['pageid'])) ? $_GET['pageid'] : 0;
$tiptext = (isset($_GET['pagename']) && isset($_GET['pageid'])) ? '编辑级别' : '添加单页';
?>
<!--添加单页表单-->
<fieldset>
<legend><?php echo $tiptext; ?>:</legend>
<form method="post" action="page_process.php" name="page_form" onsubmit="return check_page_form()">
<table border="0" class="formtable cates_form">
<tr><td class="label"><label>单页名称</label></td><td><input type="text" name="pagename" value="<?php echo $pagename; ?>" id="pagename" /></td></tr>
<tr><td class="label"><label>上级页面</label></td><td>
<select name="page_fid">
<option value="0">作为一级页面</option>
<?php
//取出所有类别生成选项
$query = sprintf('SELECT ID, PAGE_NAME FROM %spages ORDER BY ID ASC',
				  DB_TBL_PREFIX);
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result)) {
	while ($row = mysql_fetch_array($result))
	{
	echo '<option value="' . $row['ID'] . '">' . htmlspecialchars($row['PAGE_NAME']) . '</option>';
	}
}
mysql_free_result($result);
?>
</select>
</td></tr>
<tr><td colspan="2">
<input type="submit" value="<?php echo $tiptext; ?>" id="submitbutton" class="submit" />
<input type="hidden" name="pageid" value="<?php echo $pageid; ?>">
</td></tr>
</table>
</form>
</fieldset>
</div>
</body>
</html>