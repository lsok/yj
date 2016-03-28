<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

if (isset($_POST['submitted'])) {
	if($_POST['action_kind'] == 'adduser') { //如果是添加新用户
		$username = (isset($_POST['username'])) ? trim($_POST['username']) : '';
		$userps = (isset($_POST['userps'])) ? trim($_POST['userps']) : '';
		
		if ($username == '' || $userps == '') { //如果未填写用户名或密码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo "<script>alert('用户名或密码不能为空!');location.href='user.php?action=add';</script>";
		}
		else { 
			//检查是否有同名用户
			$query = sprintf('SELECT ADMNAME FROM %sadmuser '.
			'WHERE ADMNAME = "' . $username . '"', DB_TBL_PREFIX);
			
			mysql_query("set names 'utf8'");
			$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
			if (mysql_num_rows($result) > 0) { //如果有同名用户提示错误
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('系统中存在同名用户!');location.href='user.php?action=add';</script>";
			}
			else { // 写入新用户数据
				$query = sprintf('INSERT INTO %sadmuser (ADMNAME, ADMPASS, ADMPERMISSION) VALUES '.
				'("%s", "%s", %d)', DB_TBL_PREFIX,
				mysql_real_escape_string($username, $GLOBALS['DB']),
				mysql_real_escape_string(md5($userps), $GLOBALS['DB']),	
				0);
				
				mysql_query("set names 'utf8'");
				mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('添加用户成功!');location.href='user.php';</script>";	
			}
			mysql_free_result($result);
		}
	}
	else if ($_POST['action_kind'] == 'edituser') { //如果是编辑用户信息
		if ($_SESSION['admpermission'] == 0) { // 如果是普通管理员修改自己的密码
			$userps = (isset($_POST['userps'])) ? trim($_POST['userps']) : ''; 
			
			if ($userps == '') { // 如果密码为空
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('密码不能为空!');location.href='user.php?action=edit&userid=". $_POST['userid'] ."';</script>";	
			}
			else { // 如果密码不为空则更新用户密码
				$query = sprintf('UPDATE %sadmuser SET ADMPASS = "%s" WHERE ID = ' . $_POST['userid'], DB_TBL_PREFIX, md5($_POST['userps']));
				
				mysql_query("set names 'utf8'");
				mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('密码修改成功!');location.href='user.php';</script>";	
			}
		}
		else if ($_SESSION['admpermission'] == 1) { //如果是超级管理员编辑用户信息
			$username = (isset($_POST['username'])) ? trim($_POST['username']) : ''; 
			$userps = (isset($_POST['userps'])) ? trim($_POST['userps']) : ''; 
			
			if ($username == '' || $userps == '') { //如果用户名或密码为空
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('用户名或密码不能为空!');location.href='user.php?action=edit&userid=". $_POST['userid'] ."';</script>";
			}
			else { //如果用户名与密码不为空则更新用户信息
				$query = sprintf('SELECT ADMNAME FROM %sadmuser '.
			'WHERE ADMNAME = "%s" AND ID != %d', DB_TBL_PREFIX, mysql_real_escape_string($username, $GLOBALS['DB']), $_POST['userid']);
			
				mysql_query("set names 'utf8'");
				$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
			
				if (mysql_num_rows($result) > 0) { //如果有同名用户提示错误
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
					echo "<script>alert('系统中存在同名用户!');location.href='user.php?action=edit&userid=". $_POST['userid'] ."';</script>";
				}
				else { // 更新用户数据
					$query = sprintf('UPDATE %sadmuser SET ADMNAME = "%s", ADMPASS = "%s" WHERE ID = ' . $_POST['userid'], DB_TBL_PREFIX, mysql_real_escape_string($username, $GLOBALS['DB']), md5($_POST['userps']));
				
				mysql_query("set names 'utf8'");
				mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
				
				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<script>alert('编辑用户成功!');location.href='user.php';</script>";	
				}
				mysql_free_result($result);
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员设置</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script type="text/javascript" src="js/formsubmit.js"></script>
<script language="javascript">
function checkdel()
{if (confirm("确实要删除吗？"))
     {return (true);}
     else
     {return (false);}
}
</script>
</head>
<body>
<div id="wrap">
<h1>管理员设置<?php if ($_SESSION['admpermission'] == 1) { ?><a href="user.php?action=add" class="adduser">添加用户</a><a href="user.php" class="adduser">用户列表</a><?php } ?></h1>
<?php
if (isset($_GET['action']) && $_GET['action'] == 'add') { //如果是添加新用户,显示添加用户表单
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<table border="0" class="formtable">
<tr><td class="label"><label for="username">用户名</label></td><td><input type="text" name="username"  id="username" class="textinput" /></td></tr>
<tr><td class="label"><label for="userps">密码</label></td><td><input type="password" name="userps"  id="userps" class="textinput" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="添加管理员" id="submitbutton" class="submit" /><input type="hidden" name="action_kind" value="adduser" /><input type="hidden" name="submitted" value="true" /></td></tr>
</table>
</form>
<?php 
} 
else if (isset($_GET['action']) && $_GET['action'] == 'edit') { //如果是编辑用户,显示编辑表单
	$query = sprintf('SELECT * FROM %sadmuser WHERE ID ="' . $_GET['userid'] . '"', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row = mysql_fetch_assoc($result);
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<table border="0" class="formtable">
<tr><td class="label"><label for="username">用户名</label></td><td>
<?php
if($_SESSION['admpermission'] == 0) { // 如果是普通管理员 用户名显示为文本
echo htmlspecialchars($row['ADMNAME']);
}
else { // 如果是超级管理员编辑用户信息，用户名设置为表单值
?>
<input type="text" name="username" value="<?php echo htmlspecialchars($row['ADMNAME']); ?>" id="username" class="textinput" />
<?php } ?>
</td></tr>
<tr><td class="label"><label for="userps">密码</label></td><td><input type="password" name="userps" id="userps" class="textinput" /></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="保存更改" id="submitbutton" class="submit" />
<input type="hidden" name="userid" value="<?php echo $row['ID']; ?>">
<input type="hidden" name="action_kind" value="edituser" />
<input type="hidden" name="submitted" value="true" />
</td></tr>
</table>
</form>
<?php 
}
else if (isset($_GET['action']) && $_GET['action'] == 'del') { //如果是删除用户
	$query = sprintf('DELETE FROM %sadmuser WHERE ID = ' . $_GET['userid'], DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	echo "<script>location.href='user.php';</script>";
}
else { //正常载入页面时显示用户列表
?>
<table border="0" cellspacing="0" class="datatable">
<tr><th width="30%">用户名</th><th width="30%">身份</th><th width="38%">操作</th></tr>
<?php
if ($_SESSION['admpermission'] == 0) { //如果是普通管理员,只显示该管理员信息
	$query = sprintf('SELECT * FROM %sadmuser WHERE ADMNAME ="' . $_SESSION['admuser'] . '"', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
			
		echo '<tr class="even">';
		echo '<td>' . $row['ADMNAME'] . '</td><td>管理员</td>';
		echo '<td>' . '<a href="user.php?action=edit&userid=' . $row['ID'] . '">修改密码</a></td>';
		echo '</tr>';
		}
	mysql_free_result($result);
}
else //如果是超级管理员,显示全部管理员列表
{
	$query = sprintf('SELECT * FROM %sadmuser', DB_TBL_PREFIX);
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	$odd = true;
	
	while ($row = mysql_fetch_assoc($result)) {
		$userkind = ($row['ADMPERMISSION'] == 0) ? '管理员' : '超级管理员';
		
		echo ($odd == true) ? '<tr class="odd">' : '<tr class="even">';
		$odd = !$odd;
		echo '<td>' . $row['ADMNAME'] . '</td>' . '<td>' . $userkind . '</td>';
		echo '<td>';
		echo '<a href="user.php?action=edit&userid=' . $row['ID'] . '">编辑</a>';
		if ($userkind == '管理员') {
			echo ' <a href="user.php?action=del&userid=' . $row['ID'] . '" onclick="return checkdel()">删除</a>';
		}
		echo '</td>';
		echo '</tr>';
		}
	mysql_free_result($result);
}
?>
</table>
<p class="tip"><img src="images/tip.gif" />超级管理员可添加,编辑普通管理员;普通管理员仅可修改自己的登录密码.</p>
<?php } ?>
</div>
</body>
</html>
