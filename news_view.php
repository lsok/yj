<?php
/*
|
|--------------------------------------------------------------------------
| 文章详情页数据
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

//接收文章ID
$item_id = $_GET['id'];

//更新点击数
$query = sprintf('UPDATE %sarticles SET ' .
				'READCOUNT = READCOUNT + 1
				WHERE
				ID = %d',

				DB_TBL_PREFIX,
				$item_id);

mysql_query("set names 'utf8'");
mysql_query($query, $GLOBALS['DB']);

//检索文章内容
$query = sprintf('SELECT ID, CAT_ID, ART_TITLE, ART_CONTENT, AUTHOR, READCOUNT, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sarticles WHERE ID = %d', DB_TBL_PREFIX, $item_id);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$row = mysql_fetch_array($result);
	
	//将文章标题与内容摘要定义为页面标题和描述
	$unique_title = '三通_'.$row['ART_TITLE'];
	$unique_description = str_intercept(strip_tags($row['ART_CONTENT']), 160);
	
	//检索文章类别名称并定义面包屑
	$item_catid = $row['CAT_ID'];
	$cate_query = sprintf('SELECT ID, CATENAME FROM %sarticles_cates WHERE ID = %d', DB_TBL_PREFIX, $item_catid);

	mysql_query("set names 'utf8'");
	$cate_result = mysql_query($cate_query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

	$cate_row = mysql_fetch_assoc($cate_result);
	
	$breadcrumb = ' - <a href="news-' . $cate_row['ID'] . '-1.html">' . $cate_row['CATENAME'] . '</a>';
	
	mysql_free_result($cate_result);
	
	ob_start();
	
	echo '<h2 class="art_title">'. $row['ART_TITLE'] . '</h2>';
	
	echo '<p class="art_info">';
	echo '<span>作者:' . $row['AUTHOR'] . '</span>';
	echo '<span>发布日期:' . date('Y-m-d', $row['SUBMIT_DATE']) . '</span>';
	echo '<span>阅读数:' . $row['READCOUNT'] . '</span>';
	echo '</p>';
	
	echo '<div class="art_content">';
	echo $row['ART_CONTENT'];
	echo '<br /><br />';
	echo '本文链接地址: ' . '<a href="http://www.tysbg.com/news-view-' . $item_id . '.html">http://www.tysbg.com/news-view-' . $item_id . '.html</a><br />';
	echo '转载请注明: 转自<a href="http://www.tysbg.com">天源水泵管</a>(本厂产品: <a href="http://www.tysbg.com/product-13-1.html">水泵管</a> <a href="http://www.tysbg.com/product-13-1.html">潜水泵管</a>)<br /><br />';
	
	//上一篇链接
   $query = sprintf('SELECT ID, ART_TITLE FROM %sarticles WHERE IS_DELETE = 0 AND ID < %d ORDER BY ID DESC LIMIT 0, 1', DB_TBL_PREFIX, $item_id);
	
	mysql_query("set names 'utf8'");
	$pre_result = mysql_query($query, $GLOBALS['DB']);

	if(mysql_num_rows($pre_result))
	{
		$pre_row = mysql_fetch_assoc($pre_result);
		echo	'上一篇文章：<a href="news-view-' . $pre_row['ID'] . '.html" >' . $pre_row['ART_TITLE'] . '</a>';
	}

	mysql_free_result($pre_result);
	
	echo '<br />';
	
	//下一篇链接
	$query = sprintf('SELECT ID, ART_TITLE FROM %sarticles WHERE IS_DELETE = 0 AND ID > %d ORDER BY ID ASC LIMIT 0, 1', DB_TBL_PREFIX, $item_id);
	
	mysql_query("set names 'utf8'");
	$next_result = mysql_query($query, $GLOBALS['DB']);

	if(mysql_num_rows($next_result))
	{
		$next_row = mysql_fetch_assoc($next_result);
		echo	'下一篇文章：<a href="news-view-' . $next_row['ID'] . '.html" >' . $next_row['ART_TITLE'] . '</a>';
	}

	mysql_free_result($next_result);
	
	echo '</div>';
	
	//定义文章内容变量
	$article = ob_get_clean();
}
else
{
	$article = '';
}

mysql_free_result($result);


//定义相关文章数据
$query = sprintf('SELECT ID, ART_TITLE, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sarticles WHERE ID != %d AND IS_DELETE = 0 LIMIT 0, 10', DB_TBL_PREFIX, $item_id);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$related_news = '<h3 class="related_title" style="clear:left;margin:0px 0 10px 0">相关文章</h3>';
	$related_news .= '<ul class="related_news">';
	
	$i = 0;
	
	while ($row = mysql_fetch_array($result))
	{
		//如果左侧列表达到限制数量就输出右侧列表
		if ($i == 5)
		{
			$related_news .= '</ul><ul class="related_news" style="margin-left:35px">';
		}
		
		//原动态URL: $related_news .= '<li><a href="news_view.php?id=' . $row['ID'] . '">' . $row['ART_TITLE'] . '</a><span>' . date('Y-m-d', $row['SUBMIT_DATE']) . '</span></li>';
		$related_news .= '<li><a href="news-view-' . $row['ID'] . '.html">' . $row['ART_TITLE'] . '</a><span>' . date('Y-m-d', $row['SUBMIT_DATE']) . '</span></li>';

		$i = $i + 1;
	}
	
	 $related_news .= '</ul>';
}
else
{
	$related_news = '';
}

mysql_free_result($result);


//调用文章详情页模板
include 'templates/news_view.php';
?>