<?php
/*
|
|--------------------------------------------------------------------------
| 文章列表页数据
|--------------------------------------------------------------------------
|
*/
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
include 'includes/share.php';
include 'includes/functions.php';

//包含左栏数据(产品导航 最新文章 联系方式)
include 'includes/leftdata.php';

//接收文章类别ID
$catid = $_GET['catid'];

//检索类别名称
$query = sprintf('SELECT CATENAME FROM %sarticles_cates WHERE ID = %d', DB_TBL_PREFIX, $catid);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$cate_row = mysql_fetch_assoc($result);
$catename = $cate_row['CATENAME'];

//定义页面元标签(title)
$unique_title = '三通_'.$catename;

//定义面包屑中类别名称
$breadcrumb = ' - ' . $catename;

mysql_free_result($result);

//按类别检索文章
$query = sprintf('SELECT ID, CAT_ID, ART_TITLE, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sarticles WHERE CAT_ID = %d AND IS_DELETE = 0', DB_TBL_PREFIX, $catid);

//如果执行了搜索
if (isset($_REQUEST['search']))
{
	$keyword = mysql_real_escape_string($_REQUEST['search'], $GLOBALS['DB']);
	
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

$query .= ' ORDER BY IS_TOP DESC, SUBMIT_DATE DESC';

//执行第一次查询
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$num = mysql_num_rows($result);  //获取记录总条数

$max = 20;  //设置每页记录数
$pagenum = ceil($num/$max);  //计算可分页数

if(!isset($_GET['page']) or !intval($_GET['page']) or !is_numeric($_GET['page']) or $_GET['page'] > $pagenum)
{
	$page = 1; //当页数不存在、不为十进制数、不是数字或大于可分页数时 值为1
}
else
{
	$page = $_GET['page'];  //接收当前页码数
}

$min = ($page-1)*$max;  //当前页从第$min条记录开始

mysql_free_result($result); //清除第一次查询结果集

$query .=  " limit $min,$max"; //定义显示当前页数据所需的查询语句

//根据第一次查询的记录数判断，如有数据则输出
if ($num) 
{
	$news_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	ob_start();
	
	//搜索表单
	//echo '<div class="searchform">';
	//此静态action: news-$catid-1.html 相当于 news.php?catid=1(即news.php?catid=类别ID) 参见.htaccess中伪静态规则　 
	// echo '<form action="news-' . $catid . '-1.html" method="post">';
	// echo '<input type="text" name="search" />';
	// echo '<input type="submit" value="搜索" class="button" />';
	// echo '</form>';
	// echo '</div>';
	
	//文章列表
	echo '<ul class="news_ul">';
	
   while ($news_row = mysql_fetch_array($news_result))
   {
	   //原动态URL: echo '<li><a href="news_view.php?id=' . $news_row['ID'] . '">' . $news_row['ART_TITLE'] . '</a><span>' . date('Y-m-d', $news_row['SUBMIT_DATE']) . '</span></li>';
		//伪静态URL: 格式如news-view-15.html 即 news-view-文章ID.html
	   echo '<li><a href="news-view-' . $news_row['ID'] . '.html">' . $news_row['ART_TITLE'] . '</a><span>' . date('Y-m-d', $news_row['SUBMIT_DATE']) . '</span></li>';
   }

   echo '</ul>';
	
	mysql_free_result($news_result);
		
	//如果执行了搜索，在分页URL中传递搜索参数  
	$pars = (isset($keyword)) ? '&catid=' . $catid . '&search=' . $keyword : '&catid=' . $catid;
	
	//分页代码
	echo '<div class="page">';
	news_page($pars);
	echo '</div>';
	
	$newslist = ob_get_clean();
}
else
{
	//搜索表单
	// $newslist = '<div class="searchform">';
	// $newslist .= '<form action="news-' . $catid . '-1.html" method="post">';
	// $newslist .= '<input type="text" name="search" />';
	// $newslist .= '<input type="submit" value="搜索" class="button" />';
	// $newslist .= '</form></div>';
	
	$newslist = '<p class="nodata">无相关数据!</p>';
}


//调用文章列表页模板
include 'templates/news.php';
?>