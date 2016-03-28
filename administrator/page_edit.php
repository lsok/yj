<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>单页编辑</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script language="javascript" src="js/ajax.js"></script>
<script language="javascript" src="js/Gensubselect.js"></script>
<script type="text/javascript" src="js/formsubmit.js"></script>
<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
<script>
        var editor;
        KindEditor.ready(function(K) {
                editor = K.create('#editor_zh');
        });
		 var editor_en;
        KindEditor.ready(function(K) {
                editor_en = K.create('#editor_en');
        });
</script>
</head>
<body>
<div id="wrap">
<?php
//接收单页ID
$pageid = $_GET['pageid'];

$query = sprintf('SELECT * FROM %spages WHERE ID ='.$pageid, DB_TBL_PREFIX);

mysql_query("set names 'utf8'");
$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));

if (mysql_num_rows($result))
{
	$page = mysql_fetch_array($result);
?>
<h1>编辑单页内容 - <?php echo $page['PAGE_NAME']; ?></h1>
<!--编辑单页表单开始-->
<form method="post" action="page_process.php?action=edit">
<table border="0" class="formtable">
<tr><td class="label"><label for="title">Title标题</label></td><td><input type="text" name="title" value="<?php echo $page['PAGE_TITLE']; ?>" id="title" class="textinput" /></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="title_en">英文Title</label></td><td><input type="text" name="title_en" value="<?php echo $page['PAGE_TITLE_EN']; ?>" id="title_en" class="textinput" /></td></tr>
<?php } ?>

<tr><td class="label"><label for="desc">Description描述</label></td><td><textarea name="desc"  id="desc" /><?php echo $page['PAGE_DESCRIPTION']; ?></textarea></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="desc_en">英文Description</label></td><td><textarea name="desc_en" id="desc_en"><?php echo $page['PAGE_DESCRIPTION_EN']; ?></textarea></td></tr>
<?php } ?>

<tr><td class="label"><label for="keys">keywords关键词</label></td><td><input type="text" name="keys" value="<?php echo $page['PAGE_KEYWORDS']; ?>" id="keys" class="textinput" /></td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="keys_en">英文keywords</label></td><td><input type="text" name="keys_en" value="<?php echo $page['PAGE_KEYWORDS_EN']; ?>" id="keys_en" class="textinput" /></td></tr>
<?php } ?>

<tr><td class="label"><label for="page_content">页面内容</label></td><td>
<textarea id="editor_zh" name="page_content" style="width:90%;height:300px;">
<? echo $page['PAGE_CONTENT']; ?>
</textarea>
</td></tr>

<?php if (IS_CH_EN == 1) { ?>
<tr><td class="label"><label for="page_content_en">英文内容</label></td><td>
<textarea id="editor_en" name="page_content_en" style="width:90%;height:300px;">
<? echo $page['PAGE_CONTENT_EN']; ?>
</textarea>
</td></tr>
<?php } ?>

<tr><td colspan="2">
<input type="submit" value="保存更改" id="submitbutton" class="submit" /></td></tr>
<input type="hidden" name="page_id" value="<?php echo $pageid; ?>">
</table>
</form>
<!--编辑文章表单结束-->
<?php 
}
else
{
	echo '<p class="tip_cates"><img src="images/alert.gif" />暂无单页数据!</p>';
}
 ?>
</div>
</body>
</html>
