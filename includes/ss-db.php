<?php
// establish a connection to the database server
if (!$GLOBALS['DB'] = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD))
{
    die('Error: �޷��������ݿ�,����MySQL��������.');
}
if (!mysql_select_db(DB_NAME, $GLOBALS['DB']))
{
    mysql_close($GLOBALS['DB']);
    die('Error: �޷�ѡ�����ݿ�.');
}
?>