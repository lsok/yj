<?php
/*
|
|--------------------------------------------------------------------------
| 各页面共用数据
|--------------------------------------------------------------------------
|
*/

//基本信息
$query = sprintf('SELECT SITENAME, SITE_TITLE, SITE_DESCRIPTION, SITE_KEYWORDS, EMAIL, PHONE, FOX, QQ, MSN, ADDRESS, LINKMAN FROM %sconfig WHERE ID = 1', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$baseinfo = mysql_fetch_array($result);

$phone = explode(',', $baseinfo['PHONE']); //将多个电话或手机号转换为数组
$address = explode(',', $baseinfo['ADDRESS']); //将多个地址转换为数组

mysql_free_result($result);


//轮换图片
$query = sprintf('SELECT IMG_URL, IMG_LINK, IMG_TITLE, IMG_DESC FROM %sbanner ORDER BY ORDERNUM ASC', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$banner_num = mysql_num_rows($result);

$banner = array();

if ($banner_num)
{
	while ($row = mysql_fetch_array($result))
	{
		$banner[] = $row;
	}
}

mysql_free_result($result);


//浮动QQ客服
$query = sprintf('SELECT * FROM %sqqkefu', DB_TBL_PREFIX);
					
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

$qqkf = mysql_fetch_array($result);

mysql_free_result($result);


//友情链接 (按链接组显示，如某个页面需显示友情链接，先确定显示哪个链接组)
$query = sprintf('SELECT ID, GROUP_ID, LINK_NAME, LINK_TEXT, LINK_URL, LINK_LOGO FROM %slinks WHERE GROUP_ID = 1 ORDER BY ID ASC', DB_TBL_PREFIX);
					
mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	//检索链接组ID
	$row = mysql_fetch_array($result);
	$group_id = $row['GROUP_ID'];
	
	//检索该链接组设置的展示形式 (文字或LOGO)
	$query = sprintf('SELECT TEXT_OR_IMG FROM %slink_group WHERE ID = %d', DB_TBL_PREFIX, $group_id);
	mysql_query("set names 'utf8'");
	$show_type_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$show_type_row =  mysql_fetch_array($show_type_result);
	$show_type = $show_type_row['TEXT_OR_IMG'];
	
	mysql_free_result($show_type_result);
	
	//将数据指针移回结果列表顶端
	mysql_data_seek($result, 0);
	
	$links = '';
	
	while ($row = mysql_fetch_array($result))
   {
		if ($show_type == 0)  //文字链接
		{
			$links .= '<li><a href="' . $row['LINK_URL'] . '" target="_blank">' . $row['LINK_TEXT'] . '</a></li>';
		}
		else  							 //LOGO链接
		{
		   $links .= '<li><a href="' . $row['LINK_URL'] . '" target="_blank"><img src="' . $row['LINK_LOGO'] . '" width="88" height="31"></a></li>';
		}
   }
}
else
{
	$links = '';
}

mysql_free_result($result);
?>