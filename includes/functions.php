<?php
/*
|--------------------------------------------------------------------------
| 截取指定长度字符串
|--------------------------------------------------------------------------
|
| 支持中文  如截取10个中文字符 参数$len应设置为20
|
*/
function str_intercept($str,$len)
{
	for($i=0;$i<$len;$i++)
	{
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127)
		{
		$i++;
		if($i<$len)
		{
		$new_str[]=substr($str,0,3);
		$str=substr($str,3);
		}
		}
		else
		{
		$new_str[]=substr($str,0,1);
		$str=substr($str,1);
		}
	}
	return join($new_str);
}


/*
|--------------------------------------------------------------------------
| 文章分页函数
|--------------------------------------------------------------------------
*/
function news_page($pageurl="", $pageselect = true)
{

	global $page,$num,$pagenum,$catid,$keyword; //当前页数 总页数 可分页数

	echo "当前页$page ";

	$uppage = $page - 1;  //上一页
	$downpage = $page + 1;  //下一页
	$lr = 5;  //显示多少个页数连接
	$left = floor(($lr-1)/2);  //左显示多少个页数连接
	$right = floor($lr/2);  //右显示多少个页数连接

	//下面求开始页和结束页
	if($page <= $left){  //如果当前页左不足以显示页数
		$leftpage = 1;
		$rightpage = (($lr<$pagenum)?$lr:$pagenum);
	}elseif(($pagenum-$page) < $right){  //如果当前页右不足以显示页数
		$leftpage = (($pagenum<$lr)?1:($pagenum-$lr+1));
		$rightpage = $pagenum;
	}else{  //左右可以显示页数
		$leftpage = $page - $left;
		$rightpage = $page + $right;
	}

	//前$lr页和后$lr页
	$qianpage = (($page-$lr) < 1?1:($page-$lr));
	$houpage = (($page+$lr) > $pagenum?$pagenum:($page+$lr));

	//根据传递进函数的$pageurl参数定义伪静态分页链接中所需变量
	if ($pageurl == ('&catid=' . $catid))
	{
		$news_cat = $catid;
	}
	else if ($pageurl == ('&catid=' . $catid. '&search=' . $keyword))
	{
		$news_cat = $catid;
		$sea_key = 'search-' . $keyword;
	}
	
	//输出分页
	if($page != 1)
	{
		//原动态URL: echo " 共" . $pagenum . "页  <a title=\"首页\" href=\"".$_SERVER['PHP_SELF']."?$pageurl\">首页</a>";
		//原动态URL: echo "<a title=\"上页\" href=\"".$_SERVER['PHP_SELF']."?page=$uppage$pageurl\">上页</a> ";
		
		//伪静态URL
		if (!isset($sea_key))  //如果未执行搜索
		{
			echo '共' . $pagenum . '页 <a title="首页" href="news-' . $news_cat . '-1.html">首页</a>';
			echo '<a title="上页" href="news-' . $news_cat . '-' . $uppage . '.html">上页</a>';
		}
		else  							//如果执行了搜索
		{
			echo '共' . $pagenum . '页 <a title="首页" href="news-' . $news_cat . '-' . $sea_key . '-1.html">首页</a>';
			echo '<a title="上页" href="news-' . $news_cat . '-' . $sea_key . '-' . $uppage . '.html">上页</a>';
		}
	}
	else
	{
		echo " 共" . $pagenum . "页  <span class='disabled'>首页</span>";
	}

	for($pages = $leftpage; $pages <= $rightpage; $pages++)
	{
		if($pages == $page)
		{
			echo "<span class='current'>$pages</span>";
		}
		else
		{
			//原动态URL: echo "<a href=\"?page=$pages$pageurl\">$pages</a> ";
			
			//伪静态URL
			if (!isset($sea_key))  //如果未执行搜索
			{
				echo '<a href="news-' . $news_cat . '-' . $pages . '.html">' . $pages . '</a>';
			}
			else  							//如果执行了搜索
			{
				echo '<a href="news-' . $news_cat . '-' . $sea_key . '-' . $pages . '.html">' . $pages . '</a>';
			}
		}
	}

	if($page != $pagenum)
	{	
		//原动态URL: echo "<a title=\"下页\" href=\"".$_SERVER['PHP_SELF']."?page=$downpage$pageurl\">下页</a>";
		//原动态URL: echo "<a title=\"末页\" href=\"".$_SERVER['PHP_SELF']."?page=$pagenum$pageurl\">末页</a>";
		
		//伪静态URL
		if (!isset($sea_key))  //如果未执行搜索
		{
			echo '<a title="下页" href="news-' . $news_cat . '-' . $downpage . '.html">下页</a>';
			echo '<a title="末页" href="news-' . $news_cat . '-' . $pagenum . '.html">末页</a>';
		}
		else  							//如果执行了搜索
		{
			echo '<a title="下页" href="news-' . $news_cat . '-' . $sea_key . '-' . $downpage . '.html">下页</a>';
			echo '<a title="末页" href="news-' . $news_cat . '-' . $sea_key . '-' . $pagenum . '.html">末页</a>';
		}
	}
	else
	{
		echo "</span><span class='disabled'> 末页</span> ";
	}

	//跳转
	$javapage = <<<EOM
<script language="javascript">
function web_page(targ,selObj,restore){
	eval("self"+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}
</script>
EOM;
	echo $javapage;
	if ($pageselect){
		echo " 跳至 <select onchange=\"web_page('parent',this,0)\" name=\"menu1\">";
		for($pages = 1; $pages <= $pagenum; $pages++)
		{
			$selected = ($pages == $page)?" selected=\"selected\"":"";
			//原动态URL: echo "<option value=\"".$_SERVER['PHP_SELF']."?page=$pages$pageurl\"$selected> $pages</option>";
			
			//伪静态URL
			if (!isset($sea_key))  //如果未执行搜索
			{
				echo '<option value="news-' . $news_cat . '-' . $pages . '.html" ' . $selected . '>' . $pages . '</option>';
			}
			else  							//如果执行了搜索
			{
				echo '<option value="news-' . $news_cat . '-' . $sea_key . '-' . $pages . '.html" ' . $selected . '>' . $pages . '</option>';
			}
		}
		echo "</select> 页";
	}
}


/*
|--------------------------------------------------------------------------
| 产品分页函数
|--------------------------------------------------------------------------
*/
function product_page($pageurl="", $pageselect = true)
{

	global $page,$num,$pagenum,$catid,$keyword; //当前页数 总页数 可分页数

	echo "当前页$page ";

	$uppage = $page - 1;  //上一页
	$downpage = $page + 1;  //下一页
	$lr = 5;  //显示多少个页数连接
	$left = floor(($lr-1)/2);  //左显示多少个页数连接
	$right = floor($lr/2);  //右显示多少个页数连接

	//下面求开始页和结束页
	if($page <= $left){  //如果当前页左不足以显示页数
		$leftpage = 1;
		$rightpage = (($lr<$pagenum)?$lr:$pagenum);
	}elseif(($pagenum-$page) < $right){  //如果当前页右不足以显示页数
		$leftpage = (($pagenum<$lr)?1:($pagenum-$lr+1));
		$rightpage = $pagenum;
	}else{  //左右可以显示页数
		$leftpage = $page - $left;
		$rightpage = $page + $right;
	}

	//前$lr页和后$lr页
	$qianpage = (($page-$lr) < 1?1:($page-$lr));
	$houpage = (($page+$lr) > $pagenum?$pagenum:($page+$lr));

	//根据传递进函数的$pageurl参数定义伪静态分页链接中所需变量
	if ($pageurl == '')
	{
		$default_status = TRUE;
	}
	else if ($pageurl == ('&catid=' . $catid))
	{
		$pro_cat = $catid;
	}
	else if ($pageurl == ('&search=' . $keyword))
	{
		$sea_key = 'search-' . $keyword;
	}
	
	//输出分页
	if($page != 1)
	{
		//原动态URL: echo " 共" . $pagenum . "页  <a title=\"首页\" href=\"".$_SERVER['PHP_SELF']."?$pageurl\">首页</a>";
		//原动态URL: echo "<a title=\"上页\" href=\"".$_SERVER['PHP_SELF']."?page=$uppage$pageurl\">上页</a> ";
		
		//伪静态URL
		if (isset($default_status))  //如果是默认状态(即显示全部类别产品)
		{
			echo '共' . $pagenum . '页 <a title="首页" href="product.html">首页</a>';
			echo '<a title="上页" href="product-' . $uppage . '.html">上页</a>';
		}
		else if (isset($pro_cat)) 		//如果是按类别显示产品
		{
			echo '共' . $pagenum . '页 <a title="首页" href="product-' . $pro_cat . '-1.html">首页</a>';
			echo '<a title="上页" href="product-' . $pro_cat . '-' . $uppage . '.html">上页</a>';
		}
		else if ($sea_key) 	//如果是搜索产品
		{
			echo '共' . $pagenum . '页 <a title="首页" href="product-' . $sea_key . '-1.html">首页</a>';
			echo '<a title="上页" href="product-' . $sea_key . '-' . $uppage . '.html">上页</a>';
		}
	}
	else
	{
		echo " 共" . $pagenum . "页  <span class='disabled'>首页</span>";
	}

	for($pages = $leftpage; $pages <= $rightpage; $pages++)
	{
		if($pages == $page)
		{
			echo "<span class='current'>$pages</span>";
		}
		else
		{
			//原动态URL: echo "<a href=\"?page=$pages$pageurl\">$pages</a> ";
			
			//伪静态URL
			if (isset($default_status))  //如果是默认状态(即显示全部类别产品)
			{
				echo '<a href="product-' . $pages . '.html">' . $pages . '</a>';
			}
			else if ($pageurl == ('&catid=' . $catid))  //如果是按类别显示产品
			{
				echo '<a href="product-' . $pro_cat . '-' . $pages . '.html">' . $pages . '</a>';
			}
			else if ($pageurl == ('&search=' . $keyword))  //如果是搜索产品
			{
				echo '<a href="product-' . $sea_key . '-' . $pages . '.html">' . $pages . '</a>';
			}
		}
	}

	if($page != $pagenum)
	{	
		//原动态URL: echo "<a title=\"下页\" href=\"".$_SERVER['PHP_SELF']."?page=$downpage$pageurl\">下页</a>";
		//原动态URL: echo "<a title=\"末页\" href=\"".$_SERVER['PHP_SELF']."?page=$pagenum$pageurl\">末页</a>";
		
		//伪静态URL
		if (isset($default_status))  //如果是默认状态(即显示全部类别产品)
		{
			echo '<a title="下页" href="product-' . $downpage . '.html">下页</a>';
			echo '<a title="末页" href="product-' . $pagenum . '.html">末页</a>';		
		}
		else if ($pageurl == ('&catid=' . $catid))  //如果是按类别显示产品
		{
			echo '<a title="下页" href="product-' . $pro_cat . '-' . $downpage . '.html">下页</a>';
			echo '<a title="末页" href="product-' . $pro_cat . '-' . $pagenum . '.html">末页</a>';
		}
		else if ($pageurl == ('&search=' . $keyword))  //如果是搜索产品
		{
			echo '<a title="下页" href="product-' . $sea_key . '-' . $downpage . '.html">下页</a>';
			echo '<a title="末页" href="product-' . $sea_key . '-' . $pagenum . '.html">末页</a>';
		}
	}
	else
	{
		echo "</span><span class='disabled'>末页</span> ";
	}

	//跳转
	$javapage = <<<EOM
<script language="javascript">
function web_page(targ,selObj,restore){
	eval("self"+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}
</script>
EOM;
	echo $javapage;
	if ($pageselect){
		echo " 跳至 <select onchange=\"web_page('parent',this,0)\" name=\"menu1\">";
		for($pages = 1; $pages <= $pagenum; $pages++)
		{
			$selected = ($pages == $page)?" selected=\"selected\"":"";
			//原动态URL: echo "<option value=\"".$_SERVER['PHP_SELF']."?page=$pages$pageurl\"$selected> $pages</option>";
			
			//伪静态URL
			if ($default_status)  //如果是默认状态(即显示全部类别产品)
			{
				echo '<option value="product-' . $pages . '.html" ' . $selected . '>' . $pages . '</option>';
			}
			else if ($pageurl == ('&catid=' . $catid))  //如果是按类别显示产品
			{
				echo '<option value="product-' . $pro_cat . '-' . $pages . '.html" ' . $selected . '>' . $pages . '</option>';
			}
			else if ($pageurl == ('&search=' . $keyword))  //如果是搜索产品
			{
				echo '<option value="product-' . $sea_key . '-' . $pages . '.html" ' . $selected . '>' . $pages . '</option>';
			}
		}
		echo "</select> 页";
	}
}


/*
|--------------------------------------------------------------------------
| 图片分页函数
|--------------------------------------------------------------------------
*/
function picture_page($pageurl="", $pageselect = true)
{

	global $page,$num,$pagenum,$catid; //当前页数 总页数 可分页数

	echo "当前页$page ";

	$uppage = $page - 1;  //上一页
	$downpage = $page + 1;  //下一页
	$lr = 5;  //显示多少个页数连接
	$left = floor(($lr-1)/2);  //左显示多少个页数连接
	$right = floor($lr/2);  //右显示多少个页数连接

	//下面求开始页和结束页
	if($page <= $left){  //如果当前页左不足以显示页数
		$leftpage = 1;
		$rightpage = (($lr<$pagenum)?$lr:$pagenum);
	}elseif(($pagenum-$page) < $right){  //如果当前页右不足以显示页数
		$leftpage = (($pagenum<$lr)?1:($pagenum-$lr+1));
		$rightpage = $pagenum;
	}else{  //左右可以显示页数
		$leftpage = $page - $left;
		$rightpage = $page + $right;
	}

	//前$lr页和后$lr页
	$qianpage = (($page-$lr) < 1?1:($page-$lr));
	$houpage = (($page+$lr) > $pagenum?$pagenum:($page+$lr));

	//根据传递进函数的$pageurl参数定义伪静态分页链接中所需变量
	if ($pageurl == ('&catid=' . $catid))
	{
		$pic_cat = $catid;
	}
	
	//输出分页
	if($page != 1)
	{
		//原动态URL: echo " 共" . $pagenum . "页  <a title=\"首页\" href=\"".$_SERVER['PHP_SELF']."?$pageurl\">首页</a>";
		//原动态URL: echo "<a title=\"上页\" href=\"".$_SERVER['PHP_SELF']."?page=$uppage$pageurl\">上页</a> ";
		
		//伪静态URL
		if (isset($pic_cat)) 		//如果是按类别显示图片
		{
			echo '共' . $pagenum . '页 <a title="首页" href="picture-' . $pic_cat . '-1.html">首页</a>';
			echo '<a title="上页" href="picture-' . $pic_cat . '-' . $uppage . '.html">上页</a>';
		}
	}
	else
	{
		echo " 共" . $pagenum . "页  <span class='disabled'>首页</span>";
	}

	for($pages = $leftpage; $pages <= $rightpage; $pages++)
	{
		if($pages == $page)
		{
			echo "<span class='current'>$pages</span>";
		}
		else
		{
			//原动态URL: echo "<a href=\"?page=$pages$pageurl\">$pages</a> ";
			
			//伪静态URL
			if ($pageurl == ('&catid=' . $catid))  //如果是按类别显示产品
			{
				echo '<a href="picture-' . $pic_cat . '-' . $pages . '.html">' . $pages . '</a>';
			}
		}
	}

	if($page != $pagenum)
	{	
		//原动态URL: echo "<a title=\"下页\" href=\"".$_SERVER['PHP_SELF']."?page=$downpage$pageurl\">下页</a>";
		//原动态URL: echo "<a title=\"末页\" href=\"".$_SERVER['PHP_SELF']."?page=$pagenum$pageurl\">末页</a>";
		
		//伪静态URL
		if ($pageurl == ('&catid=' . $catid))  //如果是按类别显示产品
		{
			echo '<a title="下页" href="picture-' . $pic_cat . '-' . $downpage . '.html">下页</a>';
			echo '<a title="末页" href="picture-' . $pic_cat . '-' . $pagenum . '.html">末页</a>';
		}
	}
	else
	{
		echo "</span><span class='disabled'>末页</span> ";
	}

	//跳转
	$javapage = <<<EOM
<script language="javascript">
function web_page(targ,selObj,restore){
	eval("self"+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}
</script>
EOM;
	echo $javapage;
	if ($pageselect){
		echo " 跳至 <select onchange=\"web_page('parent',this,0)\" name=\"menu1\">";
		for($pages = 1; $pages <= $pagenum; $pages++)
		{
			$selected = ($pages == $page)?" selected=\"selected\"":"";
			//原动态URL: echo "<option value=\"".$_SERVER['PHP_SELF']."?page=$pages$pageurl\"$selected> $pages</option>";
			
			//伪静态URL
			if ($pageurl == ('&catid=' . $catid))  //如果是按类别显示产品
			{
				echo '<option value="picture-' . $pic_cat . '-' . $pages . '.html" ' . $selected . '>' . $pages . '</option>';
			}
		}
		echo "</select> 页";
	}
}


/*
|--------------------------------------------------------------------------
| 获取当前页完整URL
|--------------------------------------------------------------------------
*/
function curPageURL()
{
    $pageURL = 'http://';
	
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
	
    return $pageURL;
}
?>