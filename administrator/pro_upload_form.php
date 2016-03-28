<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';
include 'includes/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>上传产品图片表单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="js/xmlhttp.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
</head>
<body>
<?php
//如是编辑图片  先显示旧图片
 if(isset($_GET['isedit']) && ($_GET['isedit'] == 'yes')) { 
 $pro_id = $_GET['pro_id'];
 
 //检索此ID的图片路径
	$query = sprintf('SELECT PRO_IMAGE FROM %sproducts WHERE ID = ' . $pro_id, DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_assoc($result);
		$old_img_path =  $row['PRO_IMAGE'];
	}
 ?>
<script type="text/javascript">
window.onload = display_old_img;
function display_old_img()
{
	document.getElementById("showimg").innerHTML = "<img src='<?php echo $old_img_path; ?>' width='180' height='160'>";
}
</script>
<?php 
} 
?>
<div id="showimg" style="height:170px;"></div>

<form id="uploadform" action="pro_upload_process.php" method="post" enctype="multipart/form-data" target="uploadframe">
<input type="file" id="myfile" name="myfile" /> 
<input type="submit" value="上传" onclick="uploadimg(document.getElementById('uploadform')); return false;" />
<iframe id="uploadframe" name="uploadframe" src="pro_upload_process.php" class="noshow"></iframe>
</form>

</body>
</html>