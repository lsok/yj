<?php
include '401.php';
include 'includes/Constant.php';

//设置允许上传的图片格式
$allowedtypes = array ("image/jpeg","image/pjpeg","image/png","image/gif","image/bmp");

//设置图片上传后保存的目录
$savefolder = '../userupload/links/';

//If we have a valid file.
if (isset ($_FILES['myfile']))
{
	//Then we need to confirm it is of a file type we want.
	if (in_array ($_FILES['myfile']['type'],$allowedtypes))
	{
		//Then we can perform the copy.
		if ($_FILES['myfile']['error'] == 0)
		{
			$image_size = getimagesize($_FILES['myfile']['tmp_name']);
			
			//获得文件后缀名并重命名
			$pinfo=pathinfo($_FILES['myfile']['name']);
			$ftype=$pinfo['extension'];
			$thefile = $savefolder . time() . "." . $ftype;
			
			if (!move_uploaded_file ($_FILES['myfile']['tmp_name'], $thefile))
			{
				echo "上传图片出现错误!";
			}
			else
			{
				?>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<script type="text/javascript" src="js/link_functions.js"></script>
				<script type="text/javascript">
				//关键函数 向产品表单中传递上传图片的路径
				window.onload = chuanzhi;
				function chuanzhi()
				{
					//因上传图片使用了伪ajax方法实现图片预览 所以在产品表单中的iframe框架中又有一个子iframe
					//此页面发送目标为子iframe(name为uploadframe), 故向产品表单中传值需使用 parent.parent 语法
					var imginput = parent.parent.document.getElementById("item_img");
					imginput.value = "<?php echo $thefile; ?>";
				}
				</script>
				</head>
				<body>
					<img src="<?=$thefile?>" onload="doneloading (parent,'<?=$thefile?>')" />
				</body>
				</html>	
				<?php
			}
		}
	}
}

?>