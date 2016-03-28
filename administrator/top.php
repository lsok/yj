<?php
include '401.php';
include '../includes/common.php';

//星期显示
$weekday = date('w', time());
switch ($weekday)
{
case 1:
$weekday = '星期一';
break;
case 2:
$weekday = '星期二';
break;
case 3:
$weekday = '星期三';
break;
case 4:
$weekday = '星期四';
break;
case 5:
$weekday = '星期五';
break;
case 6:
$weekday = '星期六';
break;
case 0:
$weekday = '星期日';
break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>顶部区域</title>
<link rel="stylesheet" href="css/top.css" type="text/css" />
</head>
<body>
<div id="toparea">
<p><span class="home"><a href="main.php" target="mainFrame">系统首页</a></span><span class="user"><?php echo $_SESSION['admuser']; ?></span><span class="date"><?php echo date('Y-m-d', time()) . ' ' . $weekday; ?></span></p>
</div>
</body>
</html>
