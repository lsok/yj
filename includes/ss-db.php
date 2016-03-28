<?php
// establish a connection to the database server
if (!$GLOBALS['DB'] = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD))
{
    die('Error: 无法连接数据库,请检查MySQL参数设置.');
}
if (!mysql_select_db(DB_NAME, $GLOBALS['DB']))
{
    mysql_close($GLOBALS['DB']);
    die('Error: 无法选择数据库.');
}
?>