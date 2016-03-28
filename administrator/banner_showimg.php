<?php
//显示友情链接LOGO
session_start();

if (is_file ($_GET['thefile']) && file_exists ($_GET['thefile']))
{
	$aryImageSize=getimagesize($_GET['thefile']);//[0]:高;[1]:宽;[2]:图片格式;[3]：图片宽高字符串，如height=XX width=XX
	$defineWidth=255;
	
	//图片尺寸：下面img标签中的"180"和"160"已设为固定值,如使用此处的$imgWidth和$imgHeight可保持图片长宽比例
	$imgWidth=$defineWidth;
	$imgHeight=floor(($defineWidth/$aryImageSize[0])*$aryImageSize[1]);
?>
	<img src="<?=$_GET['thefile']?>" width="255" height="70" />
<?php
}
?>