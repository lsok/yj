<?php
//包含数据库配置与连接
include '../ss-config.php';
include '../includes/ss-db.php';

//批量添加测试文章
if (isset($_POST['add_article_form'])) {
	
	$art_num = $_POST['add_article'];
	
	$art_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];
	$art_title = '这是一条测试文章的标题';
	$art_content = '<p>这是测试文章的内容</p>';
	$author = 'testdata';  //定义测试产品标志
	
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
if (isset($_GET['delart'])) 
{
	// 删除所有测试数据  即AUTHOR为"testdata"的文章
	$query = sprintf('DELETE FROM %sarticles WHERE AUTHOR = "testdata"', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("删除成功!");location.href="tools.php";</script>';
}


//批量添加测试产品或批量更新产品说明
if (isset($_POST['add_product_form'])) {
	
	$pro_num = $_POST['add_product']; //产品数量
	$pro_des = $_POST['edit_prodes']; //产品说明
	$pro_cate_id = (isset($_POST['des'])) ? $_POST['ss'.$_POST['des']] : $_POST['ss'];
	
	$pro_name = '产品名称';
	$pro_type = 'testdata';  //定义测试产品标志
	$pro_img = '../userupload/product/demo.jpg';
	$pro_content = '<p>这是产品说明内容。</p>';
	
	if ($pro_cate_id  != '-1') 
	{
		if ($pro_num != '' && $pro_des == '')
		{
			//添加演示产品
			for ($i=0; $i < $pro_num; $i++) 
			{
			$query = sprintf ('INSERT INTO %sproducts (CAT_ID, PRO_NAME, PRO_TYPE, PRO_IMAGE, PRO_CONTENT, SUBMIT_DATE) ' .
							'VALUES (%d, "%s", "%s", "%s", "%s", "%s")',
							DB_TBL_PREFIX,
							$pro_cate_id,
							mysql_real_escape_string($pro_name, $GLOBALS['DB']),
							mysql_real_escape_string($pro_type, $GLOBALS['DB']),
							mysql_real_escape_string($pro_img, $GLOBALS['DB']),
							mysql_real_escape_string($pro_content, $GLOBALS['DB']),
							date('Y-m-d H:i:s'));                
				
				mysql_query("set names 'utf8'");
				mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB'])); 
			}
			
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("添加演示产品成功!");location.href="tools.php";</script>';
		}
		else if ($pro_des != '' && $pro_num == '')
		{
			//批量修改某个类别的产品说明
			$query = sprintf('UPDATE %sproducts SET ' .
							'PRO_CONTENT = "%s"
							 WHERE
							 CAT_ID = %d',
							 
							 DB_TBL_PREFIX,
							 mysql_real_escape_string($pro_des, $GLOBALS['DB']),
							 $pro_cate_id
							 );
							 
			 mysql_query("set names 'utf8'");
			 mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			 
			 echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("产品说明修改成功!");location.href="tools.php";</script>';
		}
	}
	else 
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("产品类别错误!");history.back(-1);</script>';
	}
}

//删除全部测试产品
if (isset($_GET['delpro'])) {
	// 删除所有测试数据  即PRO_TYPE为"testdata"的产品
	$query = sprintf('DELETE FROM %sproducts WHERE PRO_TYPE = "testdata"', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("删除成功!");location.href="tools.php";</script>';
}

//重置管理员密码为 admin
if (isset($_GET['resetadmin']))
{
	$admname = "admin";
	$password = md5("admin");
	
	$query = sprintf('UPDATE %sadmuser SET ' .
					'ADMNAME = "%s",
					 ADMPASS = "%s",
					 ADMPERMISSION = "%d"
					 WHERE
					 ID = %d',
					 
					 DB_TBL_PREFIX,
					 mysql_real_escape_string($admname, $GLOBALS['DB']),
					 $password,
					 1,
					 1);
					 
	 mysql_query("set names 'utf8'");
	 mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
					 
	 echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	 echo '<script>alert("设置成功!");location.href="tools.php";</script>';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Simsite系统工具</title>
<script language="javascript" src="../administrator/js/ajax.js"></script>
<script language="javascript" src="../administrator/js/Gensubselect.js"></script>
<link rel="stylesheet" href="../administrator/css/admin.css" type="text/css" />
<link rel="stylesheet" href="../administrator/css/tools.css" type="text/css" />
</head>
<body>
<div id="wrap">
<h1>Simsite系统工具</h1>

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
<select id="ss" name='ss' onchange="queryCity('../administrator/get_cates_select.php?tabname=articles_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji','网络错误','')">
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
2. <input type="button" value="删除全部测试文章" onclick="location.href='tools.php?delart'">
</p>
</form>
</div>

<h2>产品模块</h2>
<div class="tools">
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<p>
1. 添加演示产品：添加 <input type="text" name="add_product" size="3"> 个演示产品 
<br/>
2. 批量修改产品说明：内容 <input type="text" name="edit_prodes" size="10">
<?php
//检索一级分类生成一级类别select选单
	$query = sprintf('SELECT ID, FAT_ID, CATENAME, CATENAME_EN FROM %sproducts_cates WHERE FAT_ID = 0 ORDER BY ID ASC', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
?>

<select id="ss" name='ss' onchange="queryCity('../administrator/get_cates_select.php?tabname=products_cates&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),'yiji_pro','网络错误','')">
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
<input type="submit" value="添加演示产品/批量修改产品说明">
<input type="hidden" name="add_product_form" value="true">
</p>

<p>
2. <input type="button" value="删除全部演示产品" onclick="location.href='tools.php?delpro'">
</p>
</form>
</div>

<h2>管理员密码重置</h2>
<div class="tools">
<p>
<input type="button" value="用户名与密码重置为admin" onclick="location.href='tools.php?resetadmin'">
</p>
</form>
</div>

<h2>数据表前缀修改</h2>
<div class="tools">
<p>
1. 说明：用于在不同项目间转移数据时, 即:新项目前缀与当前项目前缀不同，需将当前项目数据转移到新项目中用于测试。<br>
2. 操作步骤：在update_prefix.php中设置前缀并手工执行文件－登录管理后台备份数据－登录新项目管理后台恢复数据
</p>
</form>
</div>

</div>
</body>
</html>
