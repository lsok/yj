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
<title>文章管理</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/Gensubselect.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#editor_zh');
        });
		 var editor_en;
        KindEditor.ready(function(K) {
                editor_en = K.create('#editor_en');
        });
</script>
<script language="JavaScript"> 
//添加与编辑文章表单共用验证函数
function check_article_form()
{
//验证文章标题
if (document.artcateform.art_title.value == "" ) 
{ 
	alert ("文章标题不能为空!"); 
	return false; 
}
//如果为中英双语版则验证英文文章标题 先判断英文标题字段是否存在，如存在则进行非空验证
if("art_title_en" in document.artcateform)
{
	if (document.artcateform.art_title_en.value == "" )
	{
	alert ("文章英文标题不能为空!"); 
	return false;
	}
}

//验证文章类别 
var cate_select = "ss";
if("des" in document.artcateform) {
	var des_array = document.getElementsByName("des");
	var des_last_value = des_array[des_array.length-1].value;
	cate_select = "ss" + des_last_value;
}
var cate_select_obj = document.getElementById(cate_select);
if (cate_select_obj.options[cate_select_obj.selectedIndex].value == "-1" ) 
{ 
	 alert ("请选择文章所属类别!"); 
	 return false; 
}

//验证文章作者
if (document.artcateform.author.value == '' ) 
{ 
	alert ("文章作者不能为空!"); 
	return false; 
}

//如果为中英双语版则验证英文文章作者 先判断英文作者字段是否存在，如存在则进行非空验证
if("author_en" in document.artcateform)
{
	if (document.artcateform.author_en.value == "" )
	{
	alert ("文章英文作者不能为空!"); 
	return false;
	}
}

//验证中文内容非空
if(editor.isEmpty())
{
	alert("文章内容不能为空！");
	return false;
}

//如果为中英双语版则验证英文文章内容
if("art_content_en" in document.artcateform)
{
	if(editor_en.isEmpty())
	{
		alert("文章英文内容不能为空！");
		return false;
	}
}
}

//文章列表中用于实现全选的函数
function checkall(all)
{
var a = document.getElementsByName("sel_art");
for (var i=0; i<a.length; i++) a[i].checked = all.checked;
}

//用于判断记录是否被选中(用于批量删除  进入回收站)
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
　　window.location="article_process.php?delsome&del_ids=" + temp;
　　}
　　}
　　return !flag;
}

//用于判断记录是否被选中(用于从回收站中彻底批量删除)
function truedelsome () 
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
　　window.location="article_process.php?truedelsome&del_ids=" + temp;
　　}
　　}
　　return !flag;
}

//删除确认函数
function checkdel()
{if (confirm("确实要删除吗？"))
     {return (true);}
     else
     {return (false);}
}

//清空回收站确认
function checkempty()
{if (confirm("确实要清空回收站吗？"))
     {return (true);}
     else
     {return (false);}
}
</script>
</head>
<body>
<div id="wrap">
<h1>
<?php if (isset($_GET['edit'])) {echo '编辑文章'; }else if (isset($_GET['add'])){echo '添加文章';}else if (isset($_GET['isdel'])){echo '回收站';}else {echo '文章管理';}?>
<a href="articles_manage.php?add" target="mainFrame">添加文章</a><a href="articles_manage.php?list" target="mainFrame">管理文章</a><a href="articles_manage.php?isdel" target="mainFrame">回收站</a><a href="article_process.php?empty" onclick="return checkempty()" target="mainFrame">清空回收站</a>
</h1>
<?php 
//如果是新增文章
if(isset($_GET['add'])) {
?>
<!--新增文章表单开始-->
<form method="post" action="article_process.php?add" name="artcateform" onsubmit="return check_article_form()">
<table border="0" class="formtable">
<tr><td class="label"><label for="art_title">标题</label></td><td><input type="text" name="art_title" id="art_title" class="textinput" /></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="art_title_en">英文标题</label></td><td><input type="text" name="art_title_en" id="art_title_en" class="textinput" /></td></tr>
<?php } ?>

<?php
	//检索一级分类生成一级类别select选单
	$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN FROM %sarticles_cates WHERE FAT_ID = 0 ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<tr><td class="label"><label>类别</label></td>
<td>
<!-- JS函数queryCity()的最后一个参数原来的值为'加载中',在加载下级选单时如服务器延迟就会出现"加载中"提示,但如果某个类别已无下级分类,此"加载中"提示仍会出现,故删除. -->
<select id="ss" name='ss' onchange="queryCity('get_cates_select.php?tabname=articles_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji','网络错误','')">
	<option value='-1' <?php if (!isset($_GET['action'])) {echo 'selected';} ?>>选择类别</option>
<?php
	//循环输出一级分类
	if (mysql_num_rows($result)) {
		while($row=mysql_fetch_array($result)) {
			echo "<option value='$row[0]' >$row[2]</option>";
		}
	}
	?>
	</select>
	<span id='yiji'></span>
</td></tr>

<tr><td class="label"><label for="author">作者</label></td><td><input type="text" name="author" value="管理员" id="author" class="textinput" /></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="author_en">英文作者</label></td><td><input type="text" name="author_en" id="author_en" class="textinput" /></td></tr>
<?php } ?>

<tr><td class="label"><label for="art_content">内容</label></td><td>
<textarea id="editor_zh" name="art_content" style="width:90%;height:300px;"></textarea>
</td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="art_content_en">英文内容</label></td><td>
<textarea id="editor_en" name="art_content_en" style="width:90%;height:300px;"></textarea>
</td></tr>
<?php } ?>

<tr><td colspan="2"><input type="submit" name="addnew" value="添加文章" id="submitbutton" class="submit" /></td></tr>
</table>
</form>
<!--新增文章表单结束-->
<?php 
}
 //如果是编辑文章  先获取文章类别id和包含文章各上级类别id的数组
else if (isset($_GET['edit'])) 
{
	$artid = $_GET['artid'];//接收文章id
	$page = $_GET['page']; //接收页码以便在编辑成功后跳转回当前页
	
	$query = sprintf('SELECT * FROM %sarticles WHERE ID ='.$artid, DB_TBL_PREFIX);//检索文章的类别id
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$art_row = mysql_fetch_array($result);
	
	mysql_free_result($result);
	
	$art_cat_id = $art_row['CAT_ID']; //获得文章类别id

	$cate_ids = array(); //定义包含文章各级类别id的数组
	$cate_ids[] = $art_cat_id; //先将文章类别id写入数组
	
	//执行get_catids_array函数返回包含文章各级类别id的数组$cate_ids
	get_catids_array('articles_cates', $art_cat_id);
 ?>
<!--编辑文章表单开始-->
<form method="post" action="article_process.php?edit" name="artcateform" onsubmit="return check_article_form()">
<table border="0" class="formtable">
<tr><td class="label"><label for="art_title">标题</label></td><td><input type="text" name="art_title" value="<?php echo $art_row['ART_TITLE']; ?>" id="art_title" class="textinput" /></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="art_title_en">英文标题</label></td><td><input type="text" name="art_title_en" value="<?php echo $art_row['ART_TITLE_EN']; ?>" id="art_title_en" class="textinput" /></td></tr>
<?php } ?>

<?php
	//检索一级分类生成一级类别select选单
	$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN FROM %sarticles_cates WHERE FAT_ID = 0 ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<tr><td class="label"><label>类别</label></td>
<td>
<!-- JS函数queryCity()的最后一个参数原来的值为'加载中',在加载下级选单时如服务器延迟就会出现"加载中"提示,但如果某个类别已无下级分类,此"加载中"提示仍会出现,故删除. -->
<!-- 注意:queryCity函数中的tabname参数为此模块的类别数据表名称，不同模块应分别设置 -->
<select id="ss" name='ss' onchange="queryCity('get_cates_select.php?tabname=articles_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji','网络错误','')">
	<option value='-1' <?php if (!isset($_GET['action'])) {echo 'selected';} ?>>选择类别</option>
<?php
	//循环输出一级分类
	if (mysql_num_rows($result)) {
		while($row=mysql_fetch_array($result)){
			if ($row[0] == $cate_ids['0']) {//将欲编辑文章的顶级类别设置为默认值
				echo "<option value='$row[0]' selected>$row[2]</option>";
			} 
			else {
				echo "<option value='$row[0]' >$row[2]</option>";
			}
		}
		mysql_free_result($result);
	}
	?>
	</select>
	<span id='yiji'>
	<?php 
		//执行get_cates_select函数显示文章的原有类别下拉选单
		get_cates_select('articles_cates', $cate_ids, 0);
	?>
	</span>
</td></tr>

<tr><td class="label"><label for="author">作者</label></td><td><input type="text" name="author" value="<?php echo $art_row['AUTHOR']; ?>" id="author" class="textinput" /></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="author_en">英文作者</label></td><td><input type="text" name="author_en" value="<?php echo $art_row['AUTHOR_EN']; ?>" id="author_en" class="textinput" /></td></tr>
<?php } ?>

<tr><td class="label"><label for="art_content">内容</label></td><td>
<textarea id="editor_zh" name="art_content" style="width:90%;height:300px;">
<? echo $art_row['ART_CONTENT']; ?>
</textarea>
</td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="art_content_en">英文内容</label></td><td>
<textarea id="editor_en" name="art_content_en" style="width:90%;height:300px;">
<? echo $art_row['ART_CONTENT_EN']; ?>
</textarea>
</td></tr>
<?php } ?>

<tr><td colspan="2">
<input type="hidden" name="art_id" value="<?php echo $artid; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<?php if (isset($_GET['kind'])) { ?>
<input type="hidden" name="kind" value="<?php echo $_GET['kind']; ?>">
<?php } ?>
<?php if (isset($_GET['item_stat'])) { ?>
<input type="hidden" name="item_stat" value="<?php echo $_GET['item_stat']; ?>">
<?php } ?>
<?php if (isset($_GET['keyword'])) { ?>
<input type="hidden" name="keyword" value="<?php echo $_GET['keyword']; ?>">
<?php } ?>
<input type="submit" name="addnew" value="保存更改" id="submitbutton" class="submit" /></td></tr>
</table>
</form>
<!--编辑文章表单结束-->
<?php
 }
 //如果是显示全部文章列表
 else if (isset($_GET['list'])) {
	$query = sprintf('SELECT ID, CAT_ID, ART_TITLE, AUTHOR, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE, READCOUNT, IS_SHOW, IS_HOMESHOW, IS_TOP FROM %sarticles WHERE IS_DELETE = 0', DB_TBL_PREFIX);
	
	//如果按类别筛选 获取类别表单或URL传递的类别ID 并定义SQL条件语句
	if (isset($_POST['selectkind']))
	{
		$kind = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];
		
		if ($kind != '-1') 
		{
			$query .= ' AND CAT_ID =' . $kind;
		}
		else
		{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("请选择文章类别!");history.back(-1);</script>';
		}
	}
	else if (isset($_GET['kind']))
	{
		$kind = $_GET['kind'];
		$query .= ' AND CAT_ID =' . $kind;
	}
	
	//如果按状态筛选 接收状态参数并定义SQL中的条件语句
	if (isset($_GET['item_stat']))
	{
		$item_stat = $_GET['item_stat'];
		
			if ($item_stat == 'isshow')
			{
				$query .= ' AND IS_SHOW = 0';
			}
			else if ($item_stat == 'noshow')
			{
				$query .= ' AND IS_SHOW = 1';
			}
			else if ($item_stat == 'istop')
			{
				$query .= ' AND IS_TOP = 1';
			}
			else if ($item_stat == 'ishome')
			{
				$query .= ' AND IS_HOMESHOW = 0';
			}
			else if ($item_stat == 'nohome')
			{
				$query .= ' AND IS_HOMESHOW = 1';
			}
	}
	
	//如果搜索文章 接收搜索关键词并定义SQL中的条件语句
	if (isset($_REQUEST['keyword']))
	{
		$keyword = mysql_real_escape_string($_REQUEST['keyword'], $GLOBALS['DB']);
		
			if (IS_CH_EN == 0) //如果系统为中文版
			{
				if ($keyword != '')
				{
					$query .= ' AND (ART_TITLE LIKE "%' . $keyword . '%" OR ART_CONTENT LIKE "%' . $keyword . '%")';
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
					echo '<script>alert("请输入搜索关键字!");history.back(-1);</script>';
				}
			}
			else if (IS_CH_EN == 1) //如果系统为中英双语版
			{
				if ($keyword != '')
				{
					$query .= ' AND (ART_TITLE LIKE "%' . $keyword . '%" OR ART_CONTENT LIKE "%' . $keyword . '%" OR  ART_TITLE_EN LIKE "%' . $keyword . '%" OR ART_CONTENT_EN LIKE "%' . $keyword . '%")';
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
					echo '<script>alert("请输入搜索关键字!");history.back(-1);</script>';
				}
			}
		
	}
		
	$query .= ' ORDER BY SUBMIT_DATE DESC';
	
	//执行第一次查询
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$num = mysql_num_rows($result);  //记录总条数
	
	$max = ART_PAGENUM;  //每页记录数
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
	
	// 根据第一次查询的记录数判断  如果有相应数据则执行最终SQL语句并输出文章列表
	if ($num) 
	{
	$art_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>

<!--按类别筛选文章 类别选择表单开始-->
<form method="post" action="articles_manage.php?list" class="select_form">
<?php
//检索一级分类生成一级类别select选单
	$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN FROM %sarticles_cates WHERE FAT_ID = 0 ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<select id="ss" name='ss' onchange="queryCity('get_cates_select.php?tabname=articles_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji','网络错误','')">
<option value='-1' <?php if (!isset($_GET['action'])) {echo 'selected';} ?>>选择类别</option>
<?php
	//循环输出一级分类
	if (mysql_num_rows($result)) {
		while($row=mysql_fetch_array($result)) {
			echo "<option value='$row[0]' >$row[2]</option>";
		}
	}
?>
</select>
<span id='yiji'></span>
<input type="hidden" name="selectkind" value="true">
<input type="submit" value="按类别筛选" class="button">
</form>
<!--按类别筛选文章 类别选择表单结束-->

<!--按发布状态/首页显示/置顶状态筛选表单开始-->
<form method="post" action="" class="status_form">
<select name="item_stat" onChange="javascript:location=this.options[this.selectedIndex].value;" >
<option value="?list">按状态筛选</option>
<option value="?list&item_stat=isshow" <?php if (isset($_GET['item_stat']) && $_GET['item_stat'] == 'isshow') { ?>"selected"<?php } ?>>已发布</option>
<option value="?list&item_stat=noshow" <?php if (isset($_GET['item_stat']) && $_GET['item_stat'] == 'noshow') { ?>"selected"<?php } ?>>未发布</option>
<option value="?list&item_stat=istop" <?php if (isset($_GET['item_stat']) && $_GET['item_stat'] == 'istop') { ?>"selected"<?php } ?>>已置顶</option>
<option value="?list&item_stat=ishome" <?php if (isset($_GET['item_stat']) && $_GET['item_stat'] == 'ishome') { ?>"selected"<?php } ?>>首页显示</option>
<option value="?list&item_stat=nohome" <?php if (isset($_GET['item_stat']) && $_GET['item_stat'] == 'nohome') { ?>"selected"<?php } ?>>首页未显示</option>
</select>
</form>
<!--按发布状态/首页显示/置顶状态筛选表单结束-->

<!--搜索表单开始-->
<form method="post" action="articles_manage.php?list" class="search_form">
<input type="text" name="keyword" size="12">
<input type="submit" value="文章搜索" class="button">
</form>
<div class="clear"></div>
<!--搜索表单结束-->

<!--显示文章列表表格-->
<table border="0" cellspacing="0" class="datatable">
<tr><th width="6%">选择</th><th width="30%">标题</th><th width="7%">已发布</th><th width="6%">首页</th><th width="6%">置顶</th><th width="12%">类别</th><th width="8%">作者</th><th width="13%">发布日期</th><th width="6%">点击数</th><th width="6%">删除</th></tr>
<form method="post" action="article_process.php?delsome" name="delarticles">
<?php
$odd = true;

// 循环输出文章数据行
while($row = mysql_fetch_assoc($art_result)) 
{
		echo ($odd == true) ? '<tr class="odd">' : '<tr class="even">';
		$odd = !$odd;
		
		//截取文章标题 如需截取15个中文字符则设置为30
		$art_title = utf_substr($row['ART_TITLE'], 30);
		
		//检索文章类别名称
		$query = sprintf('SELECT CATENAME FROM %sarticles_cates WHERE ID = %d', DB_TBL_PREFIX, $row['CAT_ID']);
			mysql_query("set names 'utf8'");
			$cate_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			$cate_result_array = mysql_fetch_array($cate_result);
			$art_catename = $cate_result_array['CATENAME'];
		
		mysql_free_result($cate_result);
		
		//定义文章状态图标
		$img_isshow = ($row['IS_SHOW'] == 0) ? 'images/isshow_yes.png' : 'images/isshow_no.png';
		$img_ishome = ($row['IS_HOMESHOW'] == 0) ? 'images/ishome_yes.png' : 'images/isshow_no.png';
		$img_istop = ($row['IS_TOP'] == 0) ? 'images/isshow_no.png' : 'images/ishome_yes.png';
	
		//定义编辑链接参数  
		$edit_pars = '&artid=' . $row['ID'] . '&page=' . $page;
		
		//定义切换发布状态链接参数   
		$show_stat_pars = '&artid=' . $row['ID'] . '&isshowstat=' . $row['IS_SHOW'] . '&page=' . $page;
		
		//定义切换首页显示状态链接参数
		$ishome_stat_pars = '&artid=' . $row['ID'] . '&ishomestat=' . $row['IS_HOMESHOW'] . '&page=' . $page;
		
		//定义切换置顶状态链接参数
		$istop_stat_pars = '&artid=' . $row['ID'] . '&istopstat=' . $row['IS_TOP'] . '&page=' . $page;
		
		//定义删除链接参数
		$del_stat_pars = '&artid=' . $row['ID'] . '&page=' . $page;
		
		
		//如果用户点击了按类别筛选 则在链接中传递类别参数以便在操作完成后返回该类别列表
		if (isset($kind)) 
		{
			$edit_pars .= '&kind=' . $kind;
			$show_stat_pars .= '&kind=' . $kind;
			$ishome_stat_pars .= '&kind=' . $kind;
			$istop_stat_pars .= '&kind=' . $kind;
			$del_stat_pars .= '&kind=' . $kind;
		}
		
		//如果用户点击了按状态筛选 则在链接中传递状态参数以便在操作完成后返回该状态列表
		if (isset($item_stat) )
		{
			$edit_pars .= '&item_stat=' . $item_stat; 
			$show_stat_pars .= '&item_stat=' . $item_stat;
			$ishome_stat_pars .= '&item_stat=' . $item_stat; 
			$istop_stat_pars .= '&item_stat=' . $item_stat;
			$del_stat_pars .= '&item_stat=' . $item_stat;
		}
		
		//如果用户执行了搜索 则在链接中传递关键词参数以便在操作完成后返回该关键词搜索结果列表
		if (isset($keyword) )
		{
			$edit_pars .= '&keyword=' . $keyword; 
			$show_stat_pars .= '&keyword=' . $keyword;
			$ishome_stat_pars .= '&keyword=' . $keyword;
			$istop_stat_pars .= '&keyword=' . $keyword;
			$del_stat_pars .= '&keyword=' . $keyword;
		}
		
		
		//输出核心数据行
		echo '<td><input type="checkbox" name="sel_art" value="' . $row['ID'] . '"></td><td class="align_left"><a href="?edit' . $edit_pars . '" class="a_man" title="点击编辑">' . $art_title . '</a></td><td><a href="article_process.php?isshow' . $show_stat_pars . '"><img src="' . $img_isshow . '" class="iconimg" title="切换发布状态"></a></td><td><a href="article_process.php?ishome' . $ishome_stat_pars . '"><img src="' . $img_ishome . '" class="iconimg" title="是否首页显示"></a></td><td><a href="article_process.php?istop' . $istop_stat_pars . '" title="是否置顶"><img src="' . $img_istop . '"></a></td><td>' . $art_catename . '</td><td>' . $row['AUTHOR'] . '</td><td>' . date('Y-m-d', $row['SUBMIT_DATE']) . '</td><td>' . $row['READCOUNT'] . '</td><td><a href="article_process.php?delone' . $del_stat_pars . '" onclick="return checkdel()"><img src="images/detip.png" title="删除"></a></td><tr>';		
 }
 	
		mysql_free_result($art_result);
 ?>
 </table>
 
 <div style="width:150px;float:left;margin:20px 30px 20px 17px;">
 <input type="checkbox" name="chkall" onClick="checkall(this)" class="chkall"> 全选
 <input type="button" name="btnDelete" value="批量删除" onClick="del()" ID="Button1" class="button">
 </div>
 
 </form>
 
 <div class="web_page">
 <?php
 //定义分页函数所需参数  如果按类别筛选
 $pars = (isset($kind)) ? '&list&kind=' . $kind : '&list';
 
 //定义分页函数所需参数  如果按状态筛选
 if (isset($item_stat))
 {
	switch ($item_stat)
	{
		case 'isshow':
		$pars = '&list&item_stat=isshow';
		break;
		
		case 'noshow':
		$pars = '&list&item_stat=noshow';
		break;
		
		case 'istop':
		$pars = '&list&item_stat=istop';
		break;
		
		case 'ishome':
		$pars = '&list&item_stat=ishome';
		break;
		
		case 'nohome':
		$pars = '&list&item_stat=nohome';
		break;
		
		default:
		$pars = '&list';
	}
 }
 
//定义分页函数所需参数  如果执行了搜索
if (isset($keyword))
{
	$pars = '&list&keyword=' . $keyword;
}
 
 //调用分页函数
 web_page($pars);
 
 echo '</div>';
  }
 else
	{
		echo '<p class="tip_cates"><img src="images/alert.gif" />暂无相关数据!</p>';
	}
 }
 //如果是显示回收站文章列表
 else  if (isset($_GET['isdel'])) {
 
 $query = sprintf('SELECT ID, CAT_ID, ART_TITLE, AUTHOR, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE, READCOUNT, IS_SHOW, IS_HOMESHOW, IS_TOP FROM %sarticles WHERE IS_DELETE = 1', DB_TBL_PREFIX);
	
	//如果按类别筛选 获取类别表单或URL传递的类别ID 并定义SQL条件语句
	if (isset($_POST['selectkind']))
	{
		$kind = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];
		
		if ($kind != '-1') 
		{
			$query .= ' AND CAT_ID =' . $kind;
		}
		else
		{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("请选择文章类别!");history.back(-1);</script>';
		}
	}
	else if (isset($_GET['kind']))
	{
		$kind = $_GET['kind'];
		$query .= ' AND CAT_ID =' . $kind;
	}
	
	//如果搜索文章 接收搜索关键词并定义SQL中的条件语句
	if (isset($_REQUEST['keyword']))
	{
		$keyword = mysql_real_escape_string($_REQUEST['keyword'], $GLOBALS['DB']);
		
			if (IS_CH_EN == 0) //如果系统为中文版
			{
				if ($keyword != '')
				{
					$query .= ' AND (ART_TITLE LIKE "%' . $keyword . '%" OR ART_CONTENT LIKE "%' . $keyword . '%")';
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
					echo '<script>alert("请输入搜索关键字!");history.back(-1);</script>';
				}
			}
			else if (IS_CH_EN == 1) //如果系统为中英双语版
			{
				if ($keyword != '')
				{
					$query .= ' AND (ART_TITLE LIKE "%' . $keyword . '%" OR ART_CONTENT LIKE "%' . $keyword . '%" OR  ART_TITLE_EN LIKE "%' . $keyword . '%" OR ART_CONTENT_EN LIKE "%' . $keyword . '%")';
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
					echo '<script>alert("请输入搜索关键字!");history.back(-1);</script>';
				}
			}
		
	}
	
	$query .= ' ORDER BY SUBMIT_DATE DESC';
	
	//执行第一次查询
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$num = mysql_num_rows($result);  //记录总条数
	
	$max = ART_PAGENUM;  //每页记录数
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
	
	// 根据第一次查询的记录数判断  如果有相应数据则执行最终SQL语句并输出文章列表
	if ($num) 
	{
	$art_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
 ?>
 
<!--按类别筛选文章 类别选择表单开始-->
<form method="post" action="articles_manage.php?isdel" class="select_form">
<?php
//检索一级分类生成一级类别select选单
	$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN FROM %sarticles_cates WHERE FAT_ID = 0 ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<select id="ss" name='ss' onchange="queryCity('get_cates_select.php?tabname=articles_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji','网络错误','')">
<option value='-1' <?php if (!isset($_GET['action'])) {echo 'selected';} ?>>选择类别</option>
<?php
	//循环输出一级分类
	if (mysql_num_rows($result)) {
		while($row=mysql_fetch_array($result)) {
			echo "<option value='$row[0]' >$row[2]</option>";
		}
	}
?>
</select>
<span id='yiji'></span>
<input type="hidden" name="selectkind" value="true">
<input type="submit" value="按类别筛选" class="button">
</form>

<!--搜索表单开始-->
<form method="post" action="articles_manage.php?isdel" class="search_form">
<input type="text" name="keyword" size="12">
<input type="submit" value="文章搜索" class="button">
</form>
<div class="clear"></div>
<!--搜索表单结束-->

<!--显示文章列表表格-->
<table border="0" cellspacing="0" class="datatable">
<tr><th width="6%">选择</th><th width="30%">标题</th><th width="8%">还原</th><th width="8%">彻底删除</th><th width="6%">已发布</th><th width="6%">首页</th><th width="6%">置顶</th><th width="11%">类别</th><th width="8%">作者</th><th width="11%">发布日期</th></tr>
<form method="post" action="article_process.php?delsome" name="delarticles">
<?php
$odd = true;

// 循环输出文章数据行
while($row = mysql_fetch_assoc($art_result)) 
{
		echo ($odd == true) ? '<tr class="odd">' : '<tr class="even">';
		$odd = !$odd;
		
		//截取文章标题 如需截取15个中文字符则设置为30
		$art_title = utf_substr($row['ART_TITLE'], 30);
		
		//检索文章类别名称
		$query = sprintf('SELECT CATENAME FROM %sarticles_cates WHERE ID = %d', DB_TBL_PREFIX, $row['CAT_ID']);
			mysql_query("set names 'utf8'");
			$cate_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			$cate_result_array = mysql_fetch_array($cate_result);
			$art_catename = $cate_result_array['CATENAME'];
		
		mysql_free_result($cate_result);
		
		//定义文章状态图标
		$img_isshow = ($row['IS_SHOW'] == 0) ? 'images/isshow_yes_no.png' : 'images/isshow_no_no.png';
		$img_ishome = ($row['IS_HOMESHOW'] == 0) ? 'images/ishome_yes_no.png' : 'images/isshow_no_no.png';
		$img_istop = ($row['IS_TOP'] == 0) ? 'images/isshow_no_no.png' : 'images/ishome_yes_no.png';
		
		//定义还原链接参数
		$redu_pars = '&artid=' . $row['ID'] . '&page=' . $page;
		
		//定义彻底删除链接参数
		$del_stat_pars = '&artid=' . $row['ID'] . '&page=' . $page;
		
		//如果用户点击了按类别筛选 则在链接中传递类别参数以便在操作完成后返回该类别列表
		if (isset($kind)) 
		{
			$del_stat_pars .= '&kind=' . $kind;
			$redu_pars .= '&kind=' . $kind;
		}
		
		//如果用户执行了搜索 则在链接中传递关键词参数以便在操作完成后返回该关键词搜索结果列表
		if (isset($keyword) )
		{
			$del_stat_pars .= '&keyword=' . $keyword;
			$redu_pars .= '&keyword=' . $keyword;
		}
		
		
		//输出核心数据行    回收站
		echo '<td><input type="checkbox" name="sel_art" value="' . $row['ID'] . '"></td><td class="align_left">' . $art_title . '</td><td><a href="article_process.php?redu' . $redu_pars . '"  title="还原"><img src="images/redu.gif"></a></td><td><a href="article_process.php?truedel' . $del_stat_pars . '" onclick="return checkdel()" title="彻底删除"><img src="images/del.gif"></a></td><td><img src="' . $img_isshow . '" class="iconimg"></td><td><img src="' . $img_ishome . '" class="iconimg" ></td><td><img src="' . $img_istop . '"></td><td>' . $art_catename . '</td><td>' . $row['AUTHOR'] . '</td><td>' . date('Y-m-d', $row['SUBMIT_DATE']) . '</td><tr>';		
 }
 	
		mysql_free_result($art_result);
 ?>
 </table>
 
 <div style="width:150px;float:left;margin:20px 30px 20px 17px;">
 <input type="checkbox" name="chkall" onClick="checkall(this)" class="chkall">全选
 <input type="button" name="btnDelete" value="彻底删除" onClick="truedelsome()" ID="Button1" class="button">
 </div>
 
 </form>
 
 <div class="web_page">
 <?php
 //定义分页函数所需参数  如果按类别筛选
 $pars = (isset($kind)) ? '&isdel&kind=' . $kind : '&isdel';
 
//定义分页函数所需参数  如果执行了搜索
if (isset($keyword))
{
	$pars = '&isdel&keyword=' . $keyword;
}
 
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
