<?php
/*
|--------------------------------------------------------------------------
| 截取指定长度字符串
|--------------------------------------------------------------------------
|
| 支持中文  如截取10个中文字符 参数$len应设置为20
|
*/
function utf_substr($str,$len)
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
| 生成随机字符串
|--------------------------------------------------------------------------
*/
function random_text($count, $rm_similar = true)
{
    // create list of characters 设置a z 为小写字母,A Z为大写字母
    $chars = array_flip(array_merge(range(0, 9), range('a', 'z')));

    // remove similar looking characters that might cause confusion
    if ($rm_similar)
    {
        unset($chars[0], $chars[1], $chars[2], $chars[5], $chars[8],
            $chars['B'], $chars['l'], $chars['O'], $chars['Q'],
            $chars['S'], $chars['U'], $chars['V'], $chars['Z']);
    }

    // generate the string of random text
    for ($i = 0, $text = ''; $i < $count; $i++)
    {
        $text .= array_rand($chars);
    }

    return $text;
}

/*
|--------------------------------------------------------------------------
| 分页
|--------------------------------------------------------------------------
*/
function web_page($pageurl="", $pageselect = true)
{

	global $page,$num,$pagenum; //当前页数 总页数 可分页数

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

	//输出分页
	if($page != 1){
		echo " 共" . $pagenum . "页  <a title=\"首页\" href=\"".$_SERVER['PHP_SELF']."?$pageurl\">首页</a> <a title=\"上页\" href=\"".$_SERVER['PHP_SELF']."?page=$uppage$pageurl\">上页</a> ";
	}else{
		echo " 共" . $pagenum . "页  <span class='disabled'>首页</span>";
	}

	for($pages = $leftpage; $pages <= $rightpage; $pages++){
		if($pages == $page){
			echo "<span class='current'>$pages</span> ";
		}else{
			echo "<a href=\"?page=$pages$pageurl\">$pages</a> ";
		}
	}

	if($page != $pagenum){
		echo "<a title=\"下页\" href=\"".$_SERVER['PHP_SELF']."?page=$downpage$pageurl\">下页</a> <a title=\"末页\" href=\"".$_SERVER['PHP_SELF']."?page=$pagenum$pageurl\">末页</a>";
	}else{
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
		for($pages = 1; $pages <= $pagenum; $pages++){
			$selected = ($pages == $page)?" selected=\"selected\"":"";
			echo "<option value=\"".$_SERVER['PHP_SELF']."?page=$pages$pageurl\"$selected> $pages</option>";
		}
		echo "</select> 页";
	}
}

/*
|--------------------------------------------------------------------------
| 提取文章内容中第一幅插图作为缩略图 
|--------------------------------------------------------------------------
|
| 可用于前台文章列表页面
|
*/
function getimg($data) 
{
	$first_img = '';	
	
	$first_img = preg_match("/<img[^>]+(src=\"([^\"<>\']+)\"|src=\'([^\"<>\']+)\')[^<>]*>/", $data, $matches);
	
	//如果文章内没有插图则返回"not exist"提示
	if($first_img == false)
	{
		$first_img = "not exist";
	}
	else
	{
		$first_img = $matches[0];;
	}
	
	return $first_img;
} 


/*
|--------------------------------------------------------------------------
| 输出分类  用于显示后台分类管理目录树
|--------------------------------------------------------------------------
|
| 各参数依次为: 分类的父ID，调用此函数的页面名称，是否为中英双语版
| 
*/
function show_cates($father_id = 0, $currentpage, $isen) {
	
	global $arr; //声明$arr为全局变量才可在函数里引用 $arr为保存了所有分类的数组
			
	//开始显示分类列表
	echo '<ul class="cates_ul">';
	
	for($i=0;$i<count($arr);$i++) { //对每个分类进行循环
		
		if($arr[$i][1] == $father_id) {//$arr[$i][1]表示分类的父id的值，默认值为0就是把父id为0的一级分类先输出
			
		   if ($isen == 0)
		   { //判断是否为中英双语版 0为否 1为是   
			   echo '<li>';
			   echo $arr[$i][2]; //$arr[$i][2]表示分类的中文名称
			   echo '<a href="'.$currentpage.'?action=edit&catename='. $arr[$i][2] .'&cateid='. $arr[$i][0] .'">编辑</a>|';
			   echo '<a href="'.$currentpage.'?action=del&cateid='. $arr[$i][0] .'" onclick="return checkdel()">删除</a>';
			   
			   //如显示产品分类，增加“上移下移”功能
			   if ($currentpage == 'product_cates.php')
			   {
					echo '|';
			   
				   /**
					* 按情况显示上移与下移链接(实现分类显示的自由排序)
					*/
				   $handleCateID = $arr[$i][4]; //要移动的分类的排序ID 即:ORDERID
				   
				   //检查是否可上移(即检查是否有比要移动类别ID更小的一级分类)
				   $query = sprintf('SELECT ORDERID FROM %sproducts_cates WHERE FAT_ID = 0 AND ORDERID < %d ORDER BY ORDERID DESC', DB_TBL_PREFIX, $handleCateID); 
					
					mysql_query("set names 'utf8'");
					$ids_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
					
					if (mysql_num_rows($ids_result))
					{
					   echo '<a href="'.$currentpage.'?action=move&to=up&orderid='. $arr[$i][4] .'">上移</a>';
					}
				
					mysql_free_result($ids_result);
					
				   //检查是否可下移(即检查是否有比要移动类别ID更大的一级分类)
				   $query = sprintf('SELECT ORDERID FROM %sproducts_cates WHERE FAT_ID = 0 AND ORDERID > %d ORDER BY ORDERID ASC', DB_TBL_PREFIX, $handleCateID); 
					
					mysql_query("set names 'utf8'");
					$ids_result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
					
					if (mysql_num_rows($ids_result))
					{
					   echo '<a href="'.$currentpage.'?action=move&to=down&orderid='. $arr[$i][4] .'">下移</a>';
					}
					
					mysql_free_result($ids_result);
				}

			   echo '</li>';
		   }
		   else
		   {
			   echo '<li>' . $arr[$i][2]. '(' . $arr[$i][3] . ')<a href="'.$currentpage.'?action=edit&catename='. $arr[$i][2] .'&catename_en='. $arr[$i][3] .'&cateid='. $arr[$i][0] .'">编辑</a>';
			   echo '<a href="'.$currentpage.'?action=del&cateid='. $arr[$i][0] .'" onclick="return checkdel()">删除</a></li>'; //$arr[$i][2]分类的中文名称
		   }
		   
		   //$arr[$i][0]表示分类的id值，进行递归，也就是把
		   //自己的id作为父id参数，把自己的子类再循环输出
		   show_cates($arr[$i][0], $currentpage, $isen);                    
		}  
	}  
	echo '</ul>';
}

/*
|--------------------------------------------------------------------------
| 删除分类
|--------------------------------------------------------------------------
|
| 各参数依次为: 类别ID, 分类数据表名, 隶属于分类下的数据的表名, 执行删除操作后的转向页面
| 删除分类的同时也删除其分类下的数据  如文章 产品等
|
*/
function del_all_cates($cateid, $tablename, $its_tbl_cate, $redirectpage) 
{
	//删除当前分类
	$query = sprintf('DELETE FROM %s'.$tablename.' WHERE ID = %d',
					 DB_TBL_PREFIX,
					 $cateid);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//删除隶属于当前分类下的数据 (文章、产品等)
	//如果是删除产品 先删除产品图片
	if($its_tbl_cate == 'products')
	{
		$query = sprintf('SELECT PRO_IMAGE FROM %sproducts WHERE CAT_ID = %d',
					DB_TBL_PREFIX,
					$cateid);
					
		$result = mysql_query($query, $GLOBALS['DB']);
		
		while($row = mysql_fetch_array($result))
		{
			unlink($row['PRO_IMAGE']);
		}
		
		mysql_free_result($result);
	}
	
	//删除隶属于当前分类下的数据 (文章、产品等)
	$query = sprintf('DELETE FROM %s'.$its_tbl_cate.' WHERE CAT_ID = %d',
				 DB_TBL_PREFIX,
				 $cateid);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//得到当前分类的所有子分类
	$query = sprintf('SELECT ID FROM %s'.$tablename.' WHERE FAT_ID = %d',
					DB_TBL_PREFIX,
					$cateid);
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($result)) { //如果当前分类有子分类
		
		//将所有子分类id写入数组
		$child_cates = array();
		while ($row = mysql_fetch_array($result)) { 
			$child_cates[] = $row['ID'];
		}
		
		foreach ($child_cates as $child_id) {
			del_all_cates($child_id, $tablename, $its_tbl_cate, $redirectpage); //将每个子分类的id作为参数再次调用递归函数
		}
	}
	
	mysql_free_result($result);
	
	echo "<script>location.href='".$redirectpage."';</script>";	
}

/*
|--------------------------------------------------------------------------
| 获取包含项目各级类别id的数组　用于在编辑项目表单中显示类别选单
|--------------------------------------------------------------------------
|
| 参数$tabname为项目类别数据表名称，$catid为当前项目的类别id
|
*/
function get_catids_array($tabname, $catid) 
{
		global $cate_ids;
		
		//检索项目的父类别id
	  	$query = sprintf('SELECT FAT_ID FROM %s' . $tabname . ' WHERE ID = '. $catid, DB_TBL_PREFIX);
		$result = mysql_query($query, $GLOBALS['DB']);
		
		if (mysql_num_rows($result)) {
			$row = mysql_fetch_array($result);
			
			if ($row['FAT_ID'] != 0) {
			$cate_ids[] = $row['FAT_ID'];
			}
						
			get_catids_array($tabname, $row['FAT_ID']);
		}
			
		mysql_free_result($result);
		
		//将数组按升序排序 排序后的元素顺序如 1 3 4
		return sort($cate_ids);
}

/*
|--------------------------------------------------------------------------
| 显示编辑项目表单中的类别下拉选单
|--------------------------------------------------------------------------
|
| 即在编辑项目表单中显示项目的原有类别
| 参数$tabname为项目类别数据表名称，$array为包含项目各级类别id的数组
|
*/
function get_cates_select($tabname, $array, $i)
{
	
	$query = sprintf('SELECT * FROM %s' . $tabname . ' WHERE FAT_ID = %d', DB_TBL_PREFIX, $array[$i]);
	
	mysql_query("set names 'utf8'");
	$result=mysql_query($query, $GLOBALS['DB']);

	if (mysql_num_rows($result))
	{				
		echo '<input type="hidden" value="'.$array[$i].'" name="des">';

		echo '<select name="ss' . $array[$i] . '" ';
		echo 'onchange="queryCity(\'get_cates_select.php?tabname=' . $tabname;
		echo '&fid=\'+this.options[this.selectedIndex].value+\'&n=\'+Math.random(),' . $array[$i] . ',\'网络传输错误\',\'\')">';

		echo '<option value="-1" selected>请选择分类</option>';
		
		while($row=mysql_fetch_row($result))
		{
			//循环输出父id为art_ids[$i]的子分类
			echo '<option value="'.$row[0].'"';
			
			if ($row[0] == $array[$i+1]) { echo 'selected'; }
			echo '>'.$row[2].'</option>';
		}
		
		echo '</select>';
		
		mysql_free_result($result);
		
		echo '<span id="'. $array[$i] .'">';
		if ($i < count($array)) 
		{
			get_cates_select($tabname, $array, $i+1);
		}
		echo '</span>';
	}
}

/*
|--------------------------------------------------------------------------
| 输出单页  用于显示后台单页管理目录树
|--------------------------------------------------------------------------
|
| 各参数依次为: 单页的父ID，调用此函数的页面名称
| 
*/
function show_pages($father_id = 0) {
	
	global $arr; //声明$arr为全局变量才可在函数里引用 $arr是保存了所有单页的数组
			
	//开始显示单页列表
	echo '<ul class="cates_ul">';
	
	for($i=0;$i<count($arr);$i++) { //对每个单页进行循环
		
		if($arr[$i][1] == $father_id) {//$arr[$i][1]表示单页的父id的值，默认值为0就是把父id为0的一级页面先输出
			
		   echo '<li>' . $arr[$i][2]. '<a href="page_edit.php?pageid='. $arr[$i][0] .'" title="单页ID:' . $arr[$i][0] . '">编辑内容</a>|';
		   echo '<a href="page_manage.php?pagename='.$arr[$i][2].'&pageid='. $arr[$i][0] .'">编辑名称或级别</a>|';
		   echo '<a href="page_process.php?action=del&pageid='. $arr[$i][0] .'" onclick="return checkdel()">删除</a></li>'; //$arr[$i][2]表示单页名称
		   
		   //$arr[$i][0]表示单页的id值，把当前页面的id作为父id，再循环输出子页面
		   show_pages($arr[$i][0]);                    
		}  
	}  
	echo '</ul>';
}

/*
|--------------------------------------------------------------------------
| 删除单页  删除单页及其可能存在的所有子页面
|--------------------------------------------------------------------------
|
| 各参数依次为: 单页ID
|
*/
function del_pages($pageid) 
{
	//删除当前单页
	$query = sprintf('DELETE FROM %spages WHERE ID = %d',
					 DB_TBL_PREFIX,
					 $pageid);
					 
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//检索当前单页是否存在子页面
	$query = sprintf('SELECT ID FROM %spages WHERE FAT_ID = %d',
					DB_TBL_PREFIX,
					$pageid);
					
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($result)) 
	{
		//如果当前单页有子页面 将所有子页面id写入数组
		$child_pages = array();
		
		while ($row = mysql_fetch_array($result)) 
		{ 
			$child_pages[] = $row['ID'];
		}
		
		foreach ($child_pages as $child_id) 
		{
			del_pages($child_id); //将每个子页面的id作为参数再次调用递归函数
		}
	}
	
	mysql_free_result($result);
	
	echo "<script>location.href='page_manage.php';</script>";	
}
?>
