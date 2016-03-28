<?php
session_start();

if (!isset($_SESSION['admuser']) || $_SESSION['admuser'] == '')
{
// 防止提示框中文在IE下乱码
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
echo '<script>alert("登录超时,请重新登录系统!");top.location="login.php";</script>'; 
exit();
}
?>