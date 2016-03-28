<?php
//包含数据库配置与连接
include '../ss-config.php';
include '../includes/ss-db.php';


批量修改数据表中的某个字段值
$query = sprintf('UPDATE %simages SET ' .
				'CAT_ID = 1
				 WHERE
				 IMG_NAME <> ""',
				 
				 DB_TBL_PREFIX);
				 
mysql_query("set names 'utf8'");
mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

?>