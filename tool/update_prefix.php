<?php
//包含数据库配置与连接
include '../ss-config.php';
include '../includes/ss-db.php';

//手工设置表前缀
$old_prefix = 'sb_';  //数据表现在的前缀
$new_prefix = 'ss_';  //数据表的新前缀

//定义获取数据库中所有表名的函数
function list_tables($database)  
{   
	$query = 'SHOW TABLES FROM ' . $database;
	
	mysql_query("set names 'utf8'");
	$rs = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
	$tables = array();

	//将tables定义为全局数组,以便在函数外使用
	global $tables;
	 
	while ($row = mysql_fetch_row($rs)) 
	{  
		$tables[] = $row[0];  
	}  
	 
	mysql_free_result($rs);  
	 
	return $tables;  
} 

//执行list_tables函数获取数据库所有表名
list_tables(DB_NAME);

//过滤要修改前缀的表名,即去掉旧前缀
foreach ($tables as $k => $v)
{
   $preg = preg_match("/^($old_prefix{1})([a-zA-Z0-9_-]+)/i", $v, $v1);
   
   if ($preg)
   {
		//将无前缀的表名写入$tab_name数组
	   $tab_name[$k] = $v1[2];
   }
}
			      		   
//批量将旧表名更新为新表名
foreach ($tab_name as $k => $v)
{
	$sql = 'RENAME TABLE `'.$old_prefix.$v.'` TO `'.$new_prefix.$v.'`';
	mysql_query($sql);
}

//改为弹出消息
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
echo '<script>alert("表前缀设置成功!");</script>';
?>