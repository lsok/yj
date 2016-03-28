<?php
// 包含文件:开发环境配置 数据库配置 数据库连接
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
session_start();

if (isset($_POST['submitted']))
{
	 if ($_POST['captcha'] != $_SESSION['captcha'])
	 {
		 $errors = array();
		 $errors[] = '验证码错误!';
	 }
	 else
	{
		$magname = (isset($_POST['magname'])) ? trim($_POST['magname']) : '';
		$magpass = (isset($_POST['magpass'])) ? trim($_POST['magpass']) : '';
		
		if ($magname && $magpass)
		{
			$query = sprintf('SELECT * FROM %sadmuser WHERE ADMNAME = "%s" AND ADMPASS = "%s"',
			DB_TBL_PREFIX, mysql_real_escape_string($magname, $GLOBALS['DB']), md5($magpass));
			
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			if (mysql_num_rows($result) > 0) 
			{
				$row = mysql_fetch_array($result);

				$_SESSION['admuser'] = $magname;
				$_SESSION['admpermission'] = $row['ADMPERMISSION']; 
				
				mysql_free_result($result);
				
				header('Location: index.php');
				die();
			}
			else
			{
				mysql_free_result($result);
				
				$errors = array();
			         $errors[] = '用户名或密码错误!';
			}
		}
		else
		{
			$errors = array();
			$errors[] = '用户名或密码不能为空!';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>网站管理登录</title>
<link rel="stylesheet" type="text/css" href="css/login.css" />
<script language="javascript" type="text/javascript">
//点击刷新验证码
function RefreshImage()
{
	var el =document.getElementById("imgchange");
	el.src=el.src+'?';
}
</script>
</head>
<body>
<div id="login-wrapper">
	<div id="logo"><img src="images/logo2.gif" /></div>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="login-form">
	<?php
	//如出错则显示错误提示
	if (isset($errors)) {
	echo '<ul id="error_ul">';
	foreach ($errors as $error) {
	echo '<li>' . $error . '</li>';
	}
	echo '</ul>';
	}
	
	//退出登录提示
	if (isset($_GET['logout']) && $_GET['logout'] == 'yesout') {
	echo '<ul id="error_ul">';
	echo '<li>您已成功退出登录!</li>';
	echo '</ul>';
	}
	?>
	<p>
	<label>管理员</label>
	<input class="text-input" type="text" name="magname" />
	</p>
	<div class="clear"></div>
	<p>
	<label>密码</label>
	<input class="text-input" type="password" name="magpass" />
	</p>
	<div class="clear"></div>
	<p>
	<label>验证码</label><input class="vericode" type="text" name="captcha" /><img src="images/captcha.php?nocache=<?php echo time(); ?>" id="imgchange" onmouseup="RefreshImage()"​​​​​​​​ class="vericodeimg" />
	</p>
	<p>
	<input type="submit" value="立即登录"  id="login-submit" />
	<input type="hidden" name="submitted" value="1" />
	</p>
	<div class="clear"></div>
	</form>
</div> <!-- End #login-wrapper -->
</body>
</html>
