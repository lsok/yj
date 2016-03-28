<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//检索基本信息表
$query = sprintf('SELECT * FROM %sconfig', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
$row = mysql_fetch_array($result);

// 设置表单各字段默认值
$sitename = $row['SITENAME'];
$sitename_en = $row['SITENAME_EN'];
$site_title = $row['SITE_TITLE'];
$site_title_en = $row['SITE_TITLE_EN'];
$site_description = $row['SITE_DESCRIPTION'];
$site_description_en = $row['SITE_DESCRIPTION_EN'];
$site_keywords = $row['SITE_KEYWORDS'];
$site_keywords_en = $row['SITE_KEYWORDS_EN'];
$email = $row['EMAIL'];
$phone = $row['PHONE'];
$fox = $row['FOX'];
$qq = $row['QQ'];
$msn = $row['MSN'];
$addr = $row['ADDRESS'];
$addr_en = $row['ADDRESS_EN'];
$linkman = $row['LINKMAN'];
$linkman_en = $row['LINKMAN_EN'];


//如果提交了表单则更新数据
if (isset($_POST['submitted'])) { 
    // 接收表单值
	$sitename = (isset($_POST['sitename'])) ? trim($_POST['sitename']) : '';
	$site_title = (isset($_POST['site_title'])) ? trim($_POST['site_title']) : '';
	$site_description = (isset($_POST['site_desc'])) ? trim($_POST['site_desc']) : '';
	$site_keywords = (isset($_POST['site_keywds'])) ? trim($_POST['site_keywds']) : '';
	$email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
	$phone = (isset($_POST['phone'])) ? trim($_POST['phone']) : '';
	$fox = (isset($_POST['fox'])) ? trim($_POST['fox']) : '';
	$qq = (isset($_POST['qq'])) ? trim($_POST['qq']) : '';
	$msn = (isset($_POST['msn'])) ? trim($_POST['msn']) : '';
	$addr = (isset($_POST['addr'])) ? trim($_POST['addr']) : '';
	$linkman = (isset($_POST['linkman'])) ? trim($_POST['linkman']) : '';
	
	// 如果有英文版本则接收各英文字段值
	if (IS_CH_EN == 1) {
		$sitename_en = (isset($_POST['sitename_en'])) ? trim($_POST['sitename_en']) : '';
		$site_title_en = (isset($_POST['site_title_en'])) ? trim($_POST['site_title_en']) : '';
		$site_description_en = (isset($_POST['site_desc_en'])) ? trim($_POST['site_desc_en']) : '';
		$site_keywords_en = (isset($_POST['site_keywd_en'])) ? trim($_POST['site_keywd_en']) : '';
		$addr_en = (isset($_POST['addr_en'])) ? trim($_POST['addr_en']) : '';
		$linkman_en = (isset($_POST['linkman_en'])) ? trim($_POST['linkman_en']) : '';
	}
	
	if (IS_CH_EN == 0) { // 如果只有中文版
		if ($sitename != '' && $site_title != '' && $site_description != '' && $site_keywords != '' && $email != '' && $phone != '') { // 如果必填字段不为空 则更新数据
		
		$query = sprintf('UPDATE %sconfig SET ' .
				'SITENAME = "%s",
				 SITE_TITLE = "%s",
				 SITE_DESCRIPTION = "%s",
				 SITE_KEYWORDS = "%s",
				 EMAIL = "%s",
				 PHONE = "%s",
				 FOX = "%s",
				 QQ = "%s",
				 MSN = "%s",
				 ADDRESS = "%s",
				 LINKMAN = "%s"
				 WHERE
				 ID = 1',
				 
				 DB_TBL_PREFIX,
				 mysql_real_escape_string($sitename, $GLOBALS['DB']),
				 mysql_real_escape_string($site_title, $GLOBALS['DB']),
				 mysql_real_escape_string($site_description, $GLOBALS['DB']),
				 mysql_real_escape_string($site_keywords, $GLOBALS['DB']),
				 mysql_real_escape_string($email, $GLOBALS['DB']),
				 mysql_real_escape_string($phone, $GLOBALS['DB']),
				 mysql_real_escape_string($fox, $GLOBALS['DB']),
				 mysql_real_escape_string($qq, $GLOBALS['DB']),
				 mysql_real_escape_string($msn, $GLOBALS['DB']),
				 mysql_real_escape_string($addr, $GLOBALS['DB']),
				 mysql_real_escape_string($linkman, $GLOBALS['DB'])
				 );
				 
		
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo "<script>alert('基本信息已更新!');location.href='basic.php';</script>";
		}
		else { // 否则提示错误并回退
			//定义不能为空的提示文本
			if ($sitename == '') {
			$errname = '站点名称';
			}
			else if ($site_title == '') {
			$errname = '站点Title';
			}
			else if ($site_description == '') {
			$errname = '站点Description';
			}
			else if ($site_keywords == '') {
			$errname = '站点Keywords关键词';
			}
			else if ($email == '') {
			$errname = '电子邮件';
			}
			else if ($phone == '') {
			$errname = '电话号码';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $errname . '不能为空!");history.back(-1);</script>';
		}
	}
	else { // 如果是多语言版
		if ($sitename != '' && $sitename_en != '' && $site_title != '' && $site_title_en != '' && $site_description != '' && $site_description_en != '' && $site_keywords != '' && $site_keywords_en != '' && $email != '' && $phone != '') { // 如果必填字段不为空 则更新数据
		
		$query = sprintf('UPDATE %sconfig SET ' .
				'SITENAME = "%s",
				 SITENAME_EN = "%s",
				 SITE_TITLE = "%s",
				 SITE_TITLE_EN = "%s",
				 SITE_DESCRIPTION = "%s",
				 SITE_DESCRIPTION_EN = "%s",
				 SITE_KEYWORDS = "%s",
				 SITE_KEYWORDS_EN = "%s",
				 EMAIL = "%s",
				 PHONE = "%s",
				 FOX = "%s",
				 QQ = "%s",
				 MSN = "%s",
				 ADDRESS = "%s",
				 ADDRESS_EN = "%s",
				 LINKMAN = "%s",
				 LINKMAN_EN = "%s"
				 WHERE
				 ID = 1',
				 
				 DB_TBL_PREFIX,
				 mysql_real_escape_string($sitename, $GLOBALS['DB']),
				 mysql_real_escape_string($sitename_en, $GLOBALS['DB']),
				 mysql_real_escape_string($site_title, $GLOBALS['DB']),
				 mysql_real_escape_string($site_title_en, $GLOBALS['DB']),
				 mysql_real_escape_string($site_description, $GLOBALS['DB']),
				 mysql_real_escape_string($site_description_en, $GLOBALS['DB']),
				 mysql_real_escape_string($site_keywords, $GLOBALS['DB']),
				 mysql_real_escape_string($site_keywords_en, $GLOBALS['DB']),
				 mysql_real_escape_string($email, $GLOBALS['DB']),
				 mysql_real_escape_string($phone, $GLOBALS['DB']),
				 mysql_real_escape_string($fox, $GLOBALS['DB']),
				 mysql_real_escape_string($qq, $GLOBALS['DB']),
				 mysql_real_escape_string($msn, $GLOBALS['DB']),
				 mysql_real_escape_string($addr, $GLOBALS['DB']),
				 mysql_real_escape_string($addr_en, $GLOBALS['DB']),
				 mysql_real_escape_string($linkman, $GLOBALS['DB']),
				 mysql_real_escape_string($linkman_en, $GLOBALS['DB'])
				 );
				 
		
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo "<script>alert('基本信息已更新!');location.href='basic.php';</script>";
		}
		else { // 否则提示错误并回退
			if ($sitename == '') {
			$errname = '站点名称';
			}
			else if ($sitename_en == '') {
			$errname = '英文站点名称';
			}
			else if ($site_title == '') {
			$errname = '站点Title';
			}
			else if ($site_title_en == '') {
			$errname = '站点英文Title';
			}
			else if ($site_description == '') {
			$errname = '站点Description';
			}
			else if ($site_description_en == '') {
			$errname = '站点英文Description';
			}
			else if ($site_keywords == '') {
			$errname = '站点Keywords关键词';
			}
			else if ($site_keywords_en == '') {
			$errname = '站点英文Keywords关键词';
			}
			else if ($email == '') {
			$errname = '电子邮件';
			}
			else if ($phone == '') {
			$errname = '电话号码';
			}
			
			//弹出提示框 第一行可防止提示文字在IE中乱码
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("' . $errname . '不能为空!");history.back(-1);</script>';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>基本信息设置</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/tips.css" media="all"/>
<script type="text/javascript" src="js/tips.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
</head>
<body>
<div id="wrap">
<h1>基本信息</h1>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

<div class="width-90">
<fieldset class="adminform">
<legend>基本信息</legend>
<ul class="adminformlist">
<li><label for="sitename">网站名称</label><input type="text" name="sitename" value="<?php echo htmlspecialchars($sitename); ?>" id="sitename" class="textinput" /></li>

<?php if (IS_CH_EN == 1) { ?>
<li><label for="sitename_en">英文网站名称</label><input type="text" name="sitename_en" value="<?php echo htmlspecialchars($sitename_en); ?>" id="sitename_en" class="textinput" /></li>
<?php } ?>

<li><label for="site_title">Title标题</label><input type="text" name="site_title" value="<?php echo htmlspecialchars($site_title); ?>" id="site_title" class="textinput" /></li>

<?php if (IS_CH_EN == 1) { ?>
<li><label for="site_title_en">英文Title标题</label><input type="text" name="site_title_en" value="<?php echo htmlspecialchars($site_title_en); ?>" id="site_title_en" class="textinput" /></li>
<?php } ?>

<li><label for="site_keywds">Meta关键词</label><input type="text" name="site_keywds" value="<?php echo htmlspecialchars($site_keywords); ?>" id="site_keywds" class="textinput" /></li>

<?php if (IS_CH_EN == 1) { ?>
<li><label for="site_keywd_en">英文Meta关键词</label><input type="text" name="site_keywd_en" value="<?php echo htmlspecialchars($site_keywords_en); ?>" id="site_keywd_en" class="textinput" /></li>
<?php } ?>

<li><label for="site_desc">Meta描述</label><textarea name="site_desc" rows="3" cols="10" id="site_desc" /><?php echo htmlspecialchars($site_description); ?></textarea></li>

<?php if (IS_CH_EN == 1) { ?>
<li><label for="site_desc_en">英文Meta描述</label><textarea name="site_desc_en" rows="3" cols="10" id="site_desc_en" /><?php echo htmlspecialchars($site_description_en); ?></textarea></li>
<?php } ?>

</ul>
<div style="clear:both;height:22px;"></div>
</fieldset>
</div>

<div class="width-90">
<fieldset class="adminform">
<legend>联系信息</legend>
<ul class="adminformlist">
<li><label for="email">电子邮件</label><input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" id="email" class="textinput" /></li>

<li><label for="phone">电话/手机</label><input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" id="phone" class="textinput" /></li>

<li><label for="fox">传真号码</label><input type="text" name="fox" value="<?php echo htmlspecialchars($fox); ?>" id="fox" class="textinput" /></li>

<li><label for="qq">QQ号码</label><input type="text" name="qq" value="<?php echo htmlspecialchars($qq); ?>" id="qq" class="textinput" /></li>

<li><label for="msn">MSN帐号</label><input type="text" name="msn" value="<?php echo htmlspecialchars($msn); ?>" id="msn" class="textinput" /></li>

<li><label for="addr">公司地址</label><input type="text" name="addr" value="<?php echo htmlspecialchars($addr); ?>" id="addr" class="textinput" /></li>

<?php if (IS_CH_EN == 1) { ?>
<li><label for="addr_en">英文地址</label><input type="text" name="addr_en" value="<?php echo htmlspecialchars($addr_en); ?>" id="addr_en" class="textinput" /></li>
<?php } ?>

<li><label for="linkman">联系人</label><input type="text" name="linkman" value="<?php echo htmlspecialchars($linkman); ?>" id="linkman" class="textinput" /></li>

<?php if (IS_CH_EN == 1) { ?>
<li><label for="linkman_en">英文联系人</label><input type="text" name="linkman_en" value="<?php echo htmlspecialchars($linkman_en); ?>" id="linkman_en" class="textinput" /></li>
<?php } ?>

</ul>
<div style="clear:both;height:22px;"></div>
</fieldset>
</div>

<input type="submit" name="submit" value="保存更改" id="submitbutton" class="submit" /><input type="hidden" name="submitted" value="true" />
</form>
<?php
mysql_free_result($result);
?>
</div>
</body>
</html>
