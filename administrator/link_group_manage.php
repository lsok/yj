<?php
include '401.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/Constant.php';
include 'includes/functions.php';

//此页面需包含 链接组列表 新增和编辑表单
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>链接组管理</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/tips.css" media="all"/>
<script type="text/javascript" src="js/formsubmit.js"></script>
<script type="text/javascript">
function check_form()
{
	//验证文章标题
	if (document.linkform.groupname.value == "" ) 
	{ 
		alert ("分组名称不能为空!"); 
		return false; 
	}		
}

//文章列表中用于实现全选的函数
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
　　if((document.delitems.sel_art.length+"")=="undefined") {tmp=1}else{tmp=document.delitems.sel_art.length}
　　if (tmp==1){
　　if (document.delitems.sel_art.checked){
　　flag=false;
　　temp=document.delitems.sel_art.value
　　}
　　}else{
　　for (i=0;i<document.delitems.sel_art.length;i++) {
　　if (document.delitems.sel_art[i].checked){
　　if (temp==""){
　　flag=false;
　　temp=document.delitems.sel_art[i].value
　　}else{
　　flag=false;
　　temp = temp +","+ document.delitems.sel_art[i].value
　　}
　　}
　　}
　　}
　　if (flag){ alert("请选择要删除的数据！")}
　　else{ name=document.delitems.name.value
　　//alert(name)
　　if (confirm("确实要删除吗？")){
　　window.location="link_process.php?delsome&del_ids=" + temp;
　　}
　　}
　　return !flag;
}

//删除确认函数
function checkdel()
{
	if (confirm("确实要删除吗？"))
	 {
		 return (true);
	 }
	 else
	 {
		 return (false);
	 }
}
</script>
</head>
<body>
<div id="wrap">
<h1>
<?php 
if (isset($_GET['edit'])) 
{
echo '编辑链接组'; 
}
else if (isset($_GET['add']))
{
echo '添加链接组';
}
else 
{
echo '链接组管理';
}
?>
<a href="link_group_manage.php?add" target="mainFrame">添加链接组</a>
<a href="link_group_manage.php?list" target="mainFrame">管理链接组</a>
<a href="link_manage.php?list" target="mainFrame">链接管理</a>
</h1>
<?php 
//如果是新增链接组
if(isset($_GET['add'])) 
{
?>
<!--添加链接组表单-->
<fieldset>
<legend>添加链接组:</legend>
<form method="post" action="link_process.php?addgroup" name="linkform" onsubmit="return check_form()">
<table border="0" class="formtable cates_form">

<tr>
<td class="label">
<label for="groupname">分组名称</label>
</td>
<td>
<input type="text" name="groupname" id="groupame" value="" />
</td>
</tr>

<tr>
<td colspan="2">
<input type="submit" name="submit" value="添加分组" id="submitbutton" class="submit" />
</td>
</tr>
</table>
</form>
</fieldset>
<?php 
}
//如果是编辑链接组
else if (isset($_GET['edit']))
{
	$link_id = $_GET['id'];
	$page = $_GET['page'];
	
	$query = sprintf('SELECT * FROM %slink_group WHERE ID = %d', DB_TBL_PREFIX, $link_id);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row = mysql_fetch_array($result);
?>
<!--编辑链接组表单-->
<fieldset>
<legend>编辑链接组:</legend>
<form method="post" action="link_process.php?editgroup" name="linkform" onsubmit="return check_form()">
<table border="0" class="formtable cates_form">

<tr>
<td class="label">
<label for="groupname">分组名称</label>
</td>
<td>
<input type="text" name="groupname" id="groupame" value="<?php echo $row['GROUPNAME']; ?>" />
</td>
</tr>

<tr>
<td class="label"><label for="textorimg">显示样式</label></td>
<td> 
<input type="radio" name="textorimg" value="0" id="istext" class="radio" <?php if($row['TEXT_OR_IMG'] == 0) { ?>checked="checked"<?php } ?> /> 文字

<input type="radio" name="textorimg" value="1" id="isimg" class="radio" <?php if($row['TEXT_OR_IMG'] == 1) { ?>checked="checked"<?php } ?> /> LOGO
</td>
</tr>

<tr>
<td colspan="2">
<input type="hidden" name="group_id" value="<?php echo $link_id; ?>"/>
<input type="hidden" name="page" value="<?php echo $page; ?>"/>
<input type="submit" name="submit" value="保存更改" id="submitbutton" class="submit" />
</td>
</tr>
</table>
</form>
</fieldset>
<?php
}
//如果是显示链接组列表
else if (isset($_GET['list']))
{
	$query = sprintf('SELECT
				ID,
				GROUPNAME, 
				TEXT_OR_IMG
				FROM 
				%slink_group 
				ORDER BY 
				ID DESC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$num = mysql_num_rows($result);  //记录总条数
	
	$max = 10;  //每页记录数
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
	
	// 根据第一次查询的记录数判断  如果存在数据则执行最终SQL语句并输出数据
	if ($num) 
	{
		$link_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<table border="0" cellspacing="0" class="datatable">
<tr><th width="8%">选择</th><th width="52%">链接组名称</th><th width="20%">显示样式</th><th width="10%">编辑</th><th width="10%">删除</th></tr>
<form method="post" action="link_process.php?delsome" name="delitems">
<?php
	$odd = true;

	// 循环输出数据行
	while($row = mysql_fetch_assoc($link_result)) 
	{
			echo ($odd == true) ? '<tr class="odd">' : '<tr class="even">';
			$odd = !$odd;
			
			//友情链接显示样式
			$link_style = ($row['TEXT_OR_IMG'] == 0) ? '文字' : 'LOGO';
			
			//定义编辑与删除链接参数  
			$edit_pars = '&id=' . $row['ID'] . '&page=' . $page;
		
			//输出核心数据行
			echo '<tr><td><input type="checkbox" name="sel_art" value="' . $row['ID'] . '"></td>';
			echo '<td><a href="link_group_manage.php?edit' . $edit_pars . '" class="a_man" title="ID:' . $row['ID'] . '">' . $row['GROUPNAME'] . '</a></td>';
			echo '<td>' . $link_style . '</td>';
			echo '<td><a href="link_group_manage.php?edit' . $edit_pars . '"><img src="images/edit.png" title="编辑"></a></td>';
			echo '<td><a href="link_process.php?delgroup' . $edit_pars . '" onclick="return checkdel()"><img src="images/detip.png" title="删除"></a></td>';
			echo '</tr>';		
	 }
 	
	mysql_free_result($link_result);
 ?>
</table>

 <div style="width:150px;float:left;margin:20px 30px 0px 25px;">
 <input type="checkbox" name="chkall" onClick="checkall(this)" class="chkall">全选
 <input type="button" name="btnDelete" value="批量删除" onClick="del()" ID="Button1" class="button">
 </div>
 
  </form>
  
  <div class="web_page">
 <?php
 //定义分页函数所需参数  如果按类别筛选
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