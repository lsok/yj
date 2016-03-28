<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>轮换图管理</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/Gensubselect.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
<script language="JavaScript"> 
//添加与编辑表单共用验证函数
function check_dataform()
{	
	//验证轮换图
	if (document.dataform.litem_img.value == "" ) 
	{ 
		alert ("请上传轮换图!"); 
		return false; 
	}
}

//数据列表中用于实现全选的函数
function checkall(all)
{
var a = document.getElementsByName("sel_art");
for (var i=0; i<a.length; i++) a[i].checked = all.checked;
}

//用于判断记录是否被选中(用于批量删除)
function del ()
{
　　var flag=true;
　　var temp="";
　　var tmp;
　　if((document.delarticles.sel_art.length+"")=="undefined") {tmp=1}else{tmp=document.delarticles.sel_art.length}
　　if (tmp==1){
　　if (document.delarticles.sel_art.checked){
　　flag=false;
　　temp=document.delarticles.sel_art.value
　　}
　　}else{
　　for (i=0;i<document.delarticles.sel_art.length;i++) {
　　if (document.delarticles.sel_art[i].checked){
　　if (temp==""){
　　flag=false;
　　temp=document.delarticles.sel_art[i].value
　　}else{
　　flag=false;
　　temp = temp +","+ document.delarticles.sel_art[i].value
　　}
　　}
　　}
　　}
　　if (flag){ alert("请选择要删除的数据！")}
　　else{ name=document.delarticles.name.value
　　//alert(name)
　　if (confirm("确实要删除吗？")){
　　window.location="banner_process.php?delitems&item_ids=" + temp;
　　}
　　}
　　return !flag;
}

//删除确认函数
function checkdel()
{
	if (confirm("确实要删除吗？")) {
		return (true);
	}
   else {
		return (false);
	}
}
</script>
</head>
<body>
<div id="wrap">
<h1>
<?php if (isset($_GET['edit'])) {echo '编辑轮换图'; }else if (isset($_GET['add'])){echo '添加轮换图';} else {echo '轮换图管理';}?>
<a href="banner_manage.php?add" target="mainFrame">添加轮换图</a><a href="banner_manage.php?list" target="mainFrame">管理轮换图</a>
</h1>
<?php 
//如果是新增轮换图
if(isset($_GET['add'])) {
?>
<!--新增轮换图表单开始-->
<form method="post" action="banner_process.php?add" name="dataform" onsubmit="return check_dataform()">
<table border="0" class="formtable">
<tr>
<td class="label"><label for="author">轮换图片<br>(1000*230像素)</label></td>
<td>
<input type="text" name="item_img" id="item_img" style="display:none;">
<iframe src="banner_upload_form.php" name="uploadimgform" frameborder="0" width="280" height="105" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</td>
</tr>

<tr><td class="label"><label for="img_link">图片链接<br>(可留空)</label></td><td><input type="text" name="img_link" id="img_link" class="textinput" /></td></tr>

<tr><td class="label"><label for="img_title">图片标题<br>(可留空)</label></td><td><input type="text" name="img_title" id="img_title" class="textinput" /></td></tr>

<tr><td class="label"><label for="img_desc">图片说明<br>(可留空)</label></td><td><textarea name="img_desc" rows="3" cols="10" id="img_desc" /></textarea></td></tr>

<tr><td colspan="2"><input type="submit" name="addnew" value="添加图片" id="submitbutton" class="submit" /></td></tr>
</table>
</form>
<!--新增轮换图表单结束-->
<?php 
}
 //如果是编辑轮换图
else if (isset($_GET['edit'])) 
{
	$item_id = $_GET['id']; //接收轮换图id
	$page = $_GET['page']; //接收页码以便在编辑成功后跳转回当前页
	
	$query = sprintf('SELECT ID, IMG_URL, IMG_LINK, IMG_TITLE, IMG_DESC, ORDERNUM FROM %sbanner WHERE ID = %d', DB_TBL_PREFIX, $item_id);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row = mysql_fetch_assoc($result);
	
	mysql_free_result($result);
?>
<!--编辑轮换图表单开始-->
<form method="post" action="banner_process.php?edit" name="dataform" onsubmit="return check_dataform()">
<table border="0" class="formtable">
<td class="label"><label for="author">轮换图片<br>(1000*230像素)</label></td>
<td>
<input type="text" name="item_img" id="item_img" value="<?php echo $row['IMG_URL']; ?>" style="display:none;">
<iframe src="banner_upload_form.php?isedit=yes&id=<?php echo $item_id; ?>" name="uploadimgform" frameborder="0" width="280" height="105" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</td>
</tr>

<tr><td class="label"><label for="img_link">图片链接<br>(可留空)</label></td><td><input type="text" name="img_link" id="img_link" class="textinput" value="<?php echo $row['IMG_LINK']; ?>" /></td></tr>

<tr><td class="label"><label for="img_title">图片标题<br>(可留空)</label></td><td><input type="text" name="img_title" id="img_title" class="textinput" value="<?php echo $row['IMG_TITLE']; ?>" /></td></tr>

<tr><td class="label"><label for="img_desc">图片说明<br>(可留空)</label></td><td><textarea name="img_desc" rows="3" cols="10" id="img_desc" /><?php echo $row['IMG_DESC']; ?></textarea></td></tr>

<tr><td colspan="2">
<input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="submit" value="保存更改" id="submitbutton" class="submit" /></td></tr>
</table>
</form>
<!--编辑轮换图表单结束-->

<?php
 }
 //如果是显示全部轮换图列表
 else if (isset($_GET['list'])) 
 {
	$query = sprintf('SELECT ID, IMG_URL, IMG_LINK, IMG_TITLE, IMG_DESC, ORDERNUM FROM %sbanner', DB_TBL_PREFIX);
		
	$query .= ' ORDER BY ORDERNUM ASC';
	
	//执行第一次查询
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	 //获取记录总条数
	$num = mysql_num_rows($result); 
	
	$max = 8;  //每页记录数
	$pagenum = ceil($num/$max);  //可分页数
	
	if(!isset($_GET['page']) or !intval($_GET['page']) or !is_numeric($_GET['page']) or $_GET['page'] > $pagenum)
	{
		$page = 1; //当页数不存在 不为十进制数 不是数字 大于可分页数时 为1
	}
	else
	{
		$page = $_GET['page'];  //当前页数
	}
	
	$min = ($page-1)*$max;  //当前页从第$min条记录开始
	
	mysql_free_result($result); //清除第一次查询的结果集
	
	$query .=  " limit $min,$max"; //定义最终的分页SQL语句
	
	// 根据第一次查询的记录数判断  如果有相应数据则执行最终SQL语句并输出轮换图列表
	if ($num) 
	{
	$banner_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>

<!--显示轮换图列表表格-->
<table border="0" cellspacing="0" class="datatable">
<tr><th width="6%">选择</th><th width="30%">轮换图片</th><th width="12%">图片标题</th><th width="20%">图片说明</th><th width="6%">编辑</th><th width="6%">删除</th><th width="20%">排序</th></tr>
<form method="post" action="banner_process.php?delsome" name="delarticles">
<?php
$odd = true;

// 循环输出轮换图数据行
while($row = mysql_fetch_assoc($banner_result)) 
{
		echo ($odd == true) ? '<tr class="odd">' : '<tr class="even">';
		$odd = !$odd;
				
		//定义编辑与删除轮换图参数  
		$edit_pars = '&id=' . $row['ID'] . '&page=' . $page;
		
		
		//定义当前记录是否有上一条数据的变量
		$query = sprintf('SELECT * FROM %sbanner WHERE ORDERNUM < %d', DB_TBL_PREFIX, $row['ORDERNUM']);
		mysql_query("set names 'utf8'");
		$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		$have_pre_item = mysql_num_rows($result);
		mysql_free_result($result);
		
		
		//定义当前记录是否有下一条数据的变量
		$query = sprintf('SELECT * FROM %sbanner WHERE ORDERNUM > %d', DB_TBL_PREFIX, $row['ORDERNUM']);
		mysql_query("set names 'utf8'");
		$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		$have_next_item = mysql_num_rows($result);
		mysql_free_result($result);
		
		
		//根据条件显示上移下移按钮
		if (!$have_pre_item && !$have_next_item)
		{
			$show_up_down = '<img src="images/icon_up_disabled.gif"> <img src="images/icon_down_disabled.gif">';
		}
		else if ($have_pre_item && $have_next_item)
		{
			$show_up_down = '<a href="banner_process.php?move=up' . $edit_pars . '"><img src="images/icon_up.gif"></a> <a href="banner_process.php?move=down' . $edit_pars . '"><img src="images/icon_down.gif"></a>';
		}
		else if (!$have_pre_item && $have_next_item)
		{
			$show_up_down = '<img src="images/icon_up_disabled.gif"> <a href="banner_process.php?move=down' . $edit_pars . '"><img src="images/icon_down.gif"></a>';
		}
		else if ($have_pre_item && !$have_next_item)
		{
			$show_up_down = '<a href="banner_process.php?move=up' . $edit_pars . '"><img src="images/icon_up.gif"></a> <img src="images/icon_down_disabled.gif"></a>';
		}
		
		
		//输出核心数据行
		echo '<td><input type="checkbox" name="sel_art" value="' . $row['ID'] . '"></td><td><a href="?edit' . $edit_pars . '" class="a_man" title="点击编辑"><img src="' . $row['IMG_URL'] . '" width="210" height="60"></a></td><td>' . $row['IMG_TITLE'] . '</td><td>' . $row['IMG_DESC'] . '</td><td><a href="?edit' . $edit_pars . '"><img src="images/edit.png"></a></td><td><a href="banner_process.php?del' . $edit_pars . '" onclick="return checkdel()"><img src="images/detip.png" title="删除"></a></td><td>' . $show_up_down . '</td><tr>';		
 }
 	
		mysql_free_result($banner_result);
 ?>
 </table>
 
 <div style="width:150px;float:left;margin:20px 30px 0px 17px;">
 <input type="checkbox" name="chkall" onClick="checkall(this)" class="chkall">全选
 <input type="button" name="btnDelete" value="批量删除" onClick="del()" ID="Button1" class="button">
 </div>
 
 </form>
  
 <div class="web_page">
 <?php
 //定义分页函数所需参数
 $pars = '&list';
 
 //调用分页函数
 web_page($pars);
 
 echo '</div>';
  }
 else
	{
		echo '<p class="tip_cates"><img src="images/alert.gif" />暂无相关数据!</p>';
	}
 }
?>
</div>
</body>
</html>
