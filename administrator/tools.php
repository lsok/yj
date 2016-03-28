<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//批量添加测试文章
if (isset($_POST['add_article_form'])) {
	
	$art_num = $_POST['add_article'];
	
	$art_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];
	$art_title = '这是一条测试文章的标题';
	$art_content = '<p>这是测试文章的内容</p>';
	$author = '管理员';
	
	if ($art_cate_id  != '-1') 
	{
		for ($i=0; $i < $art_num; $i++) 
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
		}
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("添加成功!");location.href="tools.php";</script>';
	}
	else 
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("文章类别错误!");history.back(-1);</script>';
	}
}

//删除全部测试文章
if (isset($_GET['delart'])) {
	// 删除表中所有数据使用 TRUNCATE TABLE tablename 语句
	$query = sprintf ('TRUNCATE TABLE %sarticles', DB_TBL_PREFIX); 
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("删除成功!");location.href="tools.php";</script>';
}

//批量添加测试产品
if (isset($_POST['add_product_form'])) {
	
	$pro_num = $_POST['add_product'];
	
	$pro_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];
	$pro_name = '产品名称';
	$pro_img = '../userupload/product/demo.jpg';
	$pro_content = '<p>这是产品说明内容。</p>';
	
	if ($pro_cate_id  != '-1') 
	{
		for ($i=0; $i < $pro_num; $i++) 
		{
		$query = sprintf ('INSERT INTO %sproducts (CAT_ID, PRO_NAME, PRO_IMAGE, PRO_CONTENT, SUBMIT_DATE) ' .
						'VALUES (%d, "%s", "%s", "%s", "%s")',
						DB_TBL_PREFIX,
						$pro_cate_id,
						mysql_real_escape_string($pro_name, $GLOBALS['DB']),
						mysql_real_escape_string($pro_img, $GLOBALS['DB']),
						mysql_real_escape_string($pro_content, $GLOBALS['DB']),
						date('Y-m-d H:i:s'));                
			
			mysql_query("set names 'utf8'");
			mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB'])); 
		}
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("添加成功!");location.href="tools.php";</script>';
	}
	else 
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("产品类别错误!");history.back(-1);</script>';
	}
}

//删除全部测试产品
if (isset($_GET['delpro'])) {
	// 删除表中所有数据使用 TRUNCATE TABLE tablename 语句
	$query = sprintf ('TRUNCATE TABLE %sproducts', DB_TBL_PREFIX); 
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("删除成功!");location.href="tools.php";</script>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统测试工具</title>
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/Gensubselect.js"></script>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" href="css/tools.css" type="text/css" />
</head>
<body>
<div id="wrap">
<h1>测试工具</h1>

<h2>文章模块</h2>
<div class="tools">
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<p>
1. 批量添加：添加 <input type="text" name="add_article" size="3" value="30"> 条文章 
类别 
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
<input type="submit" value="添加文章">
<input type="hidden" name="add_article_form" value="true">
</p>
<p>
2. <input type="button" value="删除全部文章" onclick="location.href='tools.php?delart'">
</p>
</form>
</div>

<h2>产品模块</h2>
<div class="tools">
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<p>
1. 批量添加：添加 <input type="text" name="add_product" size="3" value="30"> 条产品 
类别 
<?php
//检索一级分类生成一级类别select选单
	$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN FROM %sproducts_cates WHERE FAT_ID = 0 ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>
<select id="ss" name='ss' onchange="queryCity('get_cates_select.php?tabname=products_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji_pro','网络错误','')">
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
<span id='yiji_pro'></span>
<input type="submit" value="添加产品">
<input type="hidden" name="add_product_form" value="true">
</p>
<p>
2. <input type="button" value="删除全部产品" onclick="location.href='tools.php?delpro'">
</p>
</form>
</div>

</div>
</body>
</html>
