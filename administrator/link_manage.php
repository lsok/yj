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
<title>链接管理</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/Gensubselect.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
<script language="JavaScript"> 
//添加与编辑表单共用验证函数
function check_linkform()
{
	//验证链接名称
	if (document.linkform.link_name.value == "" ) 
	{ 
		alert ("网站名称不能为空!"); 
		return false; 
	}
	
	//验证链接组
	if (document.linkform.link_group.value == "-1" ) 
	{ 
		alert ("请选择所属链接组!"); 
		return false; 
	}
	
	//验证链接文字
	if (document.linkform.link_text.value == "" ) 
	{ 
		alert ("链接文字不能为空!"); 
		return false; 
	}
	
	//验证链接URL
	if (document.linkform.link_url.value == "" ) 
	{ 
		alert ("链接URL不能为空!"); 
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
　　window.location="link_process.php?dellinks&del_ids=" + temp;
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
<?php if (isset($_GET['edit'])) {echo '编辑链接'; }else if (isset($_GET['add'])){echo '添加链接';}else {echo '链接管理';}?>
<a href="link_manage.php?add" target="mainFrame">添加链接</a><a href="link_manage.php?list" target="mainFrame">管理链接</a><a href="link_group_manage.php?list" target="mainFrame">链接组管理</a>
</h1>
<?php 
//如果是新增链接
if(isset($_GET['add'])) {
?>
<!--新增链接表单开始-->
<form method="post" action="link_process.php?addlink" name="linkform" onsubmit="return check_linkform()">
<table border="0" class="formtable">
<tr><td class="label"><label for="link_name">网站名称</label></td><td><input type="text" name="link_name" id="link_name" class="textinput" /></td></tr>

<?php
	//检索链接组
	$query = sprintf('SELECT ID, GROUPNAME FROM %slink_group ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<tr><td class="label"><label>所属链接组</label></td>
<td>
<select name="link_group">
	<option value='-1'>选择链接组</option>
<?php
	//循环生成options选项
	if (mysql_num_rows($result)) 
	{
		while($row=mysql_fetch_array($result)) {
			echo '<option value="' . $row['ID'] .'">' . $row['GROUPNAME'] . '</option>';
		}
	}
	?>
</select>
</td>
</tr>

<tr><td class="label"><label for="link_text">链接文字</label></td><td><input type="text" name="link_text" id="link_text" class="textinput" /></td></tr>

<tr><td class="label"><label for="link_url">链接URL</label></td><td><input type="text" name="link_url" id="link_url" class="textinput" /></td></tr>

<tr>
<td class="label"><label for="author">链接LOGO<br>(88*31像素)</label></td>
<td>
<input type="text" name="item_img" id="item_img" style="display:none;">
<iframe src="link_upload_form.php" name="uploadimgform" frameborder="0" width="280" height="85" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</td>
</tr>

<tr><td colspan="2"><input type="submit" name="addnew" value="添加链接" id="submitbutton" class="submit" /></td></tr>
</table>
</form>
<!--新增链接表单结束-->
<?php 
}
 //如果是编辑链接  先获取链接类别id和包含链接各上级类别id的数组
else if (isset($_GET['edit'])) 
{
	$item_id = $_GET['id']; //接收链接id
	$page = $_GET['page']; //接收页码以便在编辑成功后跳转回当前页
	
	$query = sprintf('SELECT ID, GROUP_ID, LINK_NAME, LINK_TEXT, LINK_URL, LINK_LOGO FROM %slinks WHERE ID = %d', DB_TBL_PREFIX, $item_id);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row = mysql_fetch_assoc($result);
	
	mysql_free_result($result);
?>
<!--编辑链接表单开始-->
<form method="post" action="link_process.php?editlink" name="linkform" onsubmit="return check_linkform()">
<table border="0" class="formtable">
<tr><td class="label"><label for="link_name">网站名称</label></td><td><input type="text" name="link_name" value="<?php echo $row['LINK_NAME']; ?>" id="link_name" class="textinput" /></td></tr>

<?php
	//检索链接组
	$query = sprintf('SELECT ID, GROUPNAME FROM %slink_group ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<tr><td class="label"><label>所属链接组</label></td>
<td>
<select name="link_group">
	<option value='-1'>选择链接组</option>
<?php
	//循环生成options选项
	if (mysql_num_rows($result)) 
	{
		while($group_row=mysql_fetch_array($result)) {
			echo '<option value="' . $group_row['ID'] .'"';
			
			if ($group_row['ID'] == $row['GROUP_ID'])
			{
				echo 'selected = selected';
			}
			
			echo '>' . $group_row['GROUPNAME'] . '</option>';
		}
	}
	?>
</select>
</td>
</tr>

<tr><td class="label"><label for="link_text">链接文字</label></td><td><input type="text" name="link_text" value="<?php echo $row['LINK_TEXT']; ?>" id="link_text" class="textinput" /></td></tr>

<tr><td class="label"><label for="link_url">链接URL</label></td><td><input type="text" name="link_url" value="<?php echo $row['LINK_URL']; ?>" id="link_url" class="textinput" /></td></tr>

<tr>
<td class="label"><label for="author">链接LOGO<br>(88*31像素)</label></td>
<td>
<input type="text" name="item_img" id="item_img" value="<?php echo $row['LINK_LOGO']; ?>" style="display:none;">
<iframe src="link_upload_form.php?isedit=yes&id=<?php echo $item_id; ?>" name="uploadimgform" frameborder="0" width="280" height="85" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</td>
</tr>


<tr><td colspan="2">
<input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<?php if (isset($_GET['group'])) { ?>
<input type="hidden" name="group" value="<?php echo $_GET['group']; ?>">
<?php } ?>
<input type="submit" value="保存更改" id="submitbutton" class="submit" /></td></tr>
</table>
</form>
<!--编辑链接表单结束-->

<?php
 }
 //如果是显示全部链接列表
 else if (isset($_GET['list'])) 
 {
	$query = sprintf('SELECT ID, GROUP_ID, LINK_NAME, LINK_TEXT, LINK_URL, LINK_LOGO FROM %slinks', DB_TBL_PREFIX);
	
	
	//如果按链接组筛选 接收参数并定义SQL中的条件语句
	if (isset($_GET['group']))
	{
		$group_id = $_GET['group'];
		
		$query .= ' WHERE GROUP_ID = ' . $group_id;
	}
		
	$query .= ' ORDER BY ID ASC';
	
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
	
	// 根据第一次查询的记录数判断  如果有相应数据则执行最终SQL语句并输出链接列表
	if ($num) 
	{
	$link_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>

<!--按链接组筛选表单开始-->
<form method="post" action="" class="select_form">
<select name="group" onChange="javascript:location=this.options[this.selectedIndex].value;" >
<option value="?list">按链接组筛选</option>
<?php
	//检索链接组
	$query = sprintf('SELECT ID, GROUPNAME FROM %slink_group ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

	//循环生成options选项
	if (mysql_num_rows($result)) 
	{
		while($group_row=mysql_fetch_array($result)) {
			echo '<option value="?list&group=' . $group_row['ID'] .'"';
			
			if (isset($_GET['group']))
			{
				$group_id = $_GET['group'];
			}
			
			if (isset($group_id) && $group_row['ID'] == $group_id)
			{
				echo 'selected = selected';
			}
			
			echo '>' . $group_row['GROUPNAME'] . '</option>';
		}
	}
	
	mysql_free_result($result);
	?>
</select>
</form>
<div class="clear"></div>
<!--按链接组筛选表单结束-->

<!--显示链接列表表格-->
<table border="0" cellspacing="0" class="datatable">
<tr><th width="6%">选择</th><th width="30%">网站名称</th><th width="20%">链接文字</th><th width="12%">链接LOGO</th><th width="20%">所属链接组</th><th width="6%">编辑</th><th width="6%">删除</th></tr>
<form method="post" action="link_process.php?delsome" name="delarticles">
<?php
$odd = true;

// 循环输出链接数据行
while($row = mysql_fetch_assoc($link_result)) 
{
		echo ($odd == true) ? '<tr class="odd">' : '<tr class="even">';
		$odd = !$odd;
		
		//检索链接组名称
		$query = sprintf('SELECT GROUPNAME FROM %slink_group WHERE ID = %d', DB_TBL_PREFIX, $row['GROUP_ID']);
		
		mysql_query("set names 'utf8'");
		$group_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		$data = mysql_fetch_array($group_result);
		$link_groupname = $data['GROUPNAME'];
		
		mysql_free_result($group_result);
		
		//定义编辑与删除链接参数  
		$edit_pars = '&id=' . $row['ID'] . '&page=' . $page;
		
		
		//如果用户点击了按链接链接组筛选 则在链接中传递组ID参数以便在操作完成后返回该组列表
		if (isset($group_id))
		{
			$edit_pars .= '&group=' . $group_id; 
		}
		
		
		//输出核心数据行
		echo '<td><input type="checkbox" name="sel_art" value="' . $row['ID'] . '"></td><td><a href="?edit' . $edit_pars . '" class="a_man" title="点击编辑">' . $row['LINK_NAME'] . '</a></td><td>' . $row['LINK_TEXT'] . '</td><td><img src="' . $row['LINK_LOGO'] . '" width="88" height="31"></td><td>' . $link_groupname . '</td><td><a href="?edit' . $edit_pars . '"><img src="images/edit.png"></a></td><td><a href="link_process.php?del' . $edit_pars . '" onclick="return checkdel()"><img src="images/detip.png" title="删除"></a></td><tr>';		
 }
 	
		mysql_free_result($link_result);
 ?>
 </table>
 
 <div style="width:150px;float:left;margin:20px 30px 0px 17px;">
 <input type="checkbox" name="chkall" onClick="checkall(this)" class="chkall">全选
 <input type="button" name="btnDelete" value="批量删除" onClick="del()" ID="Button1" class="button">
 </div>
 
 </form>
  
 <div class="web_page">
 <?php
 //定义分页函数所需参数  如果按类别筛选
 $pars = (isset($_GET['group'])) ? '&list&group=' . $group_id : '&list';
 
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
