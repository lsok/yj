<?php
//产品导航定义开始
$pro_nav  = '<div class="my_left_category"><div class="my_left_cat_list"><h1><span>全部产品分类</span></h1>';

//检索一级分类
$query = sprintf('SELECT ID, FAT_ID, CATENAME FROM %sproducts_cates WHERE FAT_ID = 0 ORDER BY ORDERID ASC', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$first_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

//如存在一级分类则定义产品导航
if (mysql_num_rows($first_result))
{
   while ($first_row = mysql_fetch_array($first_result))
   {		
		//如无二级分类则定义一级分类块
		$pro_nav .= '<h2><a href="product-' . $first_row['ID'] . '-1.html">' . $first_row['CATENAME'] . '</a></h2>';
   }
}

mysql_free_result($first_result);

//定义产品搜索表单
//此静态action: product.html 相当于 product.php
$pro_nav .= '<form action="product.html" method="post" class="searchform">';
$pro_nav .= '<input type="text" name="search" class="text" /><input type="submit" value="产品搜索" class="button" />';
$pro_nav .= '</form>';

//产品导航定义结束
$pro_nav .= '</div></div>';


//左栏最新文章
$query = sprintf('SELECT ID, CAT_ID, ART_TITLE, UNIX_TIMESTAMP(SUBMIT_DATE) AS SUBMIT_DATE FROM %sarticles WHERE IS_DELETE = 0 ORDER BY SUBMIT_DATE DESC LIMIT 0, 6', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$left_news_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($left_news_result))
{
	$left_news = '<div class="inner_contact"><h1><span>新闻动态</span></h1>';
	$left_news .= '<ul class="left_news">';
	
	while ($row = mysql_fetch_array($left_news_result))
	{
		$left_news .= '<li><a href="../news-view-' . $row['ID'] . '.html">' . str_intercept($row['ART_TITLE'], 22) . '</a></li>';
	}
	
	$left_news .= '</ul></div>';
}
else
{
	$left_news = '';
}

mysql_free_result($left_news_result);
?>