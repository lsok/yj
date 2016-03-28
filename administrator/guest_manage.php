<?php
include '401.php';
include 'includes/Constant.php';
include 'includes/functions.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>留言管理</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/guestbook.css" media="all"/>
<script type="text/javascript" src="js/tips.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
<script type="text/javascript">
//删除确认函数
function checkdel()
{if (confirm("确实要删除吗？"))
     {return (true);}
     else
     {return (false);}
}
</script>
</head>
<body>
<div id="wrap">
<h1>留言管理<a href="guest_manage.php" target="mainFrame">留言列表</a>
<?php
//如存在未回复留言则显示“待回复”链接
$query = sprintf('SELECT * FROM %sguestbook WHERE CONTENT_REPLY IS NULL', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$noreplys = mysql_num_rows($result);

if($noreplys != 0)
{
	echo '<a href="guest_manage.php?noreply" class="alert">' . $noreplys . ' 条待回复</a>';
}

mysql_free_result($result);
?>
<a href="guest_param.php" target="mainFrame">参数设置</a></h1>

<?php
//检索留言表
$query = sprintf('SELECT ID, USER_NAME, EMAIL, PHONE, CON_TITLE, CONTENT, CONTENT_REPLY, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sguestbook', DB_TBL_PREFIX);

//如果用户点击了“待回复”链接则追加查询条件
if(isset($_GET['noreply']))
{
	$query .= ' WHERE CONTENT_REPLY IS NULL';
}

$query .= ' ORDER BY REPLY_DATE DESC';

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$num = mysql_num_rows($result);  //记录总数
$max = 2;  //每页记录数
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

// 根据第一次查询的记录数判断  如果有相应数据则执行最终SQL语句并输出数据
if ($num) 
{
	$guest_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

	while($row = mysql_fetch_assoc($guest_result)) 
	{
?>
<div class="guest">
	<div class="title">
		<h1><?php echo $row['CON_TITLE']; ?><span><?php echo date('Y-m-d H:i:s', $row['SUBMIT_DATE']); ?></span></h1>
	</div>
	<div class="content"><?php echo $row['CONTENT']; ?></div>
	<ul>
		<li class="user"><?php echo $row['USER_NAME']; ?></li>
		<li class="email"><?php echo $row['EMAIL']; ?></li>
		<li class="phone"><?php echo $row['PHONE']; ?></li>
	</ul>
	<?php
	if($row['CONTENT_REPLY'] != '')
	{
	?>
		<div class="reply"> 
			<div>
			<?php echo $row['CONTENT_REPLY']; ?>
			<a href="guest_reply.php?id=<?php echo $row['ID'] ?>" class="green">编辑此回复</a>
			<a href="../guest_process.php?del&id=<?php echo $row['ID'] ?>" class="red" onclick="return checkdel()">点此删除</a>
			</div>
		</div>
	<?php
	}
	else
	{
	?>
		<div class="noreply"> 
			<div>本条留言未回复 <a href="guest_reply.php?id=<?php echo $row['ID'] ?>" class="green">点此回复</a> <span>这是垃圾留言？</span><a href="../guest_process.php?del&id=<?php echo $row['ID'] ?>" class="red" onclick="return checkdel()">点此删除</a>
			</div>
		</div>
	<?php
	}
	?>
</div>
<?php
	}
//调用分页函数  先定义分页函数所需的参数  如果用户点击了“待回复”链接则在分页链接中传递noreply参数
$pars = (isset($_GET['noreply'])) ? '&noreply' : '';

echo '<div class="guest_page">';
web_page($pars);
echo '</div>';
}
else
{
	echo '<p class="tip_cates"><img src="images/alert.gif" />暂无相关数据!</p>';
}
?>
</div>
</body>
</html>
