<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

//检索留言参数表
$query = sprintf('SELECT * FROM %sqqkefu', DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
$row = mysql_fetch_array($result);

// 设置表单各字段默认值
$is_show = $row['IS_SHOW'];
$qq_nums = $row['QQ_NUMS'];
$qq_position = $row['QQ_POSITION'];
$qq_top = $row['QQ_TOP'];
$qq_color = $row['QQ_COLOR'];
$qq_style = $row['QQ_STYLE'];
$qq_effect = $row['QQ_EFFECT'];
$qq_open = $row['QQ_OPEN'];
$kf_tel = $row['KF_TEL'];

//如果提交了表单则更新数据
if (isset($_POST['submitted'])) 
{ 
	// 接收表单值
	$is_show = $_POST['is_show'];
	$qq_nums = $_POST['qq_nums'];
	$qq_position = $_POST['qq_position'];
	$qq_top = $_POST['qq_top'];
	$qq_color = $_POST['qq_color'];
	$qq_style = $_POST['qq_style'];
	$qq_effect = $_POST['qq_effect'];
	$qq_open = $_POST['qq_open'];
	$kf_tel = $_POST['kf_tel'];
	
	
	$query = sprintf('UPDATE %sqqkefu SET ' .
			'IS_SHOW = %d,
			 QQ_NUMS = "%s",
			 QQ_POSITION = "%s",
			 QQ_TOP = %d,
			 QQ_COLOR = "%s",
			 QQ_STYLE = %d,
			 QQ_EFFECT = %d,
			 QQ_OPEN = %d,
			 KF_TEL = "%s"',
			 
			 DB_TBL_PREFIX,
			 $is_show,
			 mysql_real_escape_string($qq_nums, $GLOBALS['DB']),
			 mysql_real_escape_string($qq_position, $GLOBALS['DB']),
			 $qq_top,
			 mysql_real_escape_string($qq_color, $GLOBALS['DB']),
			 $qq_style,
			 $qq_effect,
			 $qq_open,
			 mysql_real_escape_string($kf_tel, $GLOBALS['DB'])
			 );
			 
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo "<script>alert('参数设置成功!');location.href='qqkefu.php';</script>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>QQ浮动客服设置</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/tips.css" media="all"/>
<script type="text/javascript" src="js/tips.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
</head>
<body>
<div id="wrap">
<h1>QQ浮动客服设置</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<table border="0" class="formtable">
<tr>
<td colspan="2">启用QQ浮动客服 <input type="radio" name="is_show" value="1" id="yes" class="radio" <?php if($is_show == 1) { ?>checked="checked"<?php } ?> /> <label for="yes" class="radio_label">是</label><input type="radio" name="is_show" value="0" id="no" class="radio" <?php if($is_show == 0) { ?>checked="checked"<?php } ?> /> <label for="no" class="radio_label">否</label>
</td>
</tr>

<tr>
<td class="label"><label for="smtphost">QQ号码|客服名称</label></td>
<td  id="set1"><input type="text" name="qq_nums" id="qq_nums" class="textinput" value="<?php echo $qq_nums; ?>" /><a href="#" class="hintanchor" onMouseover="showhint('格式如: 615912549|售前咨询,402719549|售前咨询', this, event, '300px')"><img src="images/tips.gif" /></a>
</tr>

<tr>
<td class="label"><label for="qq_top">顶部距离(像素)</label></td>
<td><input type="text" name="qq_top" id="qq_top" class="textinput" value="<?php echo $qq_top; ?>" /></td>
</tr>

<tr>
<td colspan="2">浮动位置 <input type="radio" name="qq_position" value="left" id="qq_pos_left" class="radio" <?php if($qq_position == 'left') { ?>checked="checked"<?php } ?> /> <label for="qq_pos_left" class="radio_label">左侧</label><input type="radio" name="qq_position" value="right" id="qq_pos_right" class="radio" <?php if($qq_position == 'right') { ?>checked="checked"<?php } ?> /> <label for="qq_pos_right" class="radio_label">右侧</label>
</td>
</tr>

<tr>
<td colspan="2">定位方式 <input type="radio" name="qq_effect" value="0" id="scroll" class="radio" <?php if($qq_effect == 0) { ?>checked="checked"<?php } ?> /> <label for="scroll" class="radio_label">滚动</label><input type="radio" name="qq_effect" value="1" id="noscroll" class="radio" <?php if($qq_effect == 1) { ?>checked="checked"<?php } ?> /> <label for="noscroll" class="radio_label">固定</label>
</td>
</tr>

<tr>
<td colspan="2">默认状态 <input type="radio" name="qq_open" value="0" id="open" class="radio" <?php if($qq_open == 0) { ?>checked="checked"<?php } ?> /> <label for="open" class="radio_label">展开</label><input type="radio" name="qq_open" value="1" id="close" class="radio" <?php if($qq_open == 1) { ?>checked="checked"<?php } ?> /> <label for="close" class="radio_label">关闭</label>
</td>
</tr>

<tr>
<td colspan="2">界面颜色 
<input type="radio" name="qq_color" value="default_blue" id="default_blue" class="radio" <?php if($qq_color == 'default_blue') { ?>checked="checked"<?php } ?> /> <label for="default_blue" class="radio_label">蓝色</label>
<input type="radio" name="qq_color" value="red" id="red" class="radio" <?php if($qq_color == 'red') { ?>checked="checked"<?php } ?> /> <label for="red" class="radio_label">红色</label>
<input type="radio" name="qq_color" value="gray" id="gray" class="radio" <?php if($qq_color == 'gray') { ?>checked="checked"<?php } ?> /> <label for="gray" class="radio_label">灰色</label>
<input type="radio" name="qq_color" value="green" id="green" class="radio" <?php if($qq_color == 'green') { ?>checked="checked"<?php } ?> /> <label for="green" class="radio_label">绿色</label>
</td>
</tr>

<tr>
<td colspan="2">QQ图标样式 
<input type="radio" name="qq_style" value="1" id="style01" class="radio" <?php if($qq_style == 1) { ?>checked="checked"<?php } ?> /> <label for="style01" class="radio_label"><img src="images/qq_style_01.gif" class="radio_img" /></label>
<input type="radio" name="qq_style" value="2" id="style02" class="radio" <?php if($qq_style == 2) { ?>checked="checked"<?php } ?> /> <label for="style02" class="radio_label"><img src="images/qq_style_02.gif" class="radio_img" /></label>
<input type="radio" name="qq_style" value="3" id="style03" class="radio" <?php if($qq_style == 3) { ?>checked="checked"<?php } ?> /> <label for="style03" class="radio_label"><img src="images/qq_style_03.gif" class="radio_img" /></label>
<input type="radio" name="qq_style" value="5" id="style04" class="radio" <?php if($qq_style == 5) { ?>checked="checked"<?php } ?> /> <label for="style04" class="radio_label"><img src="images/qq_style_04.gif" class="radio_img" /></label>
<input type="radio" name="qq_style" value="6" id="style05" class="radio" <?php if($qq_style == 6) { ?>checked="checked"<?php } ?> /> <label for="style05" class="radio_label"><img src="images/qq_style_05.gif" class="radio_img" /></label>
</td>
</tr>

<tr>
<td class="label"><label for="smtphost">客服电话</label></td>
<td><input type="text" name="kf_tel" id="kf_tel" class="textinput" value="<?php echo $kf_tel; ?>" /></td>
</tr>

<tr>
<td colspan="2">
<input type="submit" name="submit" value="保存设置" id="submitbutton" class="submit" />
<input type="hidden" name="submitted" value="true" />
</td>
</tr>
</table>
</form>
</div>
</body>
</html>
