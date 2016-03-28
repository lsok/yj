<?php
include '401.php';
include 'includes/Constant.php';

//Allowed file mime types.
$allowedtypes = array ("image/jpeg","image/pjpeg","image/png","image/gif","image/bmp");
//Where we want to save the file to.
$savefolder = '../userupload/product/';

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
				echo "There was an error uploading the file.";
			} 
			else
			{
				//根据产品参数设置决定是否添加水印
				if(WATERMARK == 1)
				{
					//获得已上传图片的宽高值与类型
					list($width, $height, $type, $attr) = getimagesize($thefile);
					
					//根据图片格式将已上传图片写入内存
					switch ($type) { 
					case IMAGETYPE_GIF:
						$image = imagecreatefromgif($thefile) or
							die($error);
						break;
					case IMAGETYPE_JPEG:
						$image = imagecreatefromjpeg($thefile) or
							die($error);
						break;
					case IMAGETYPE_PNG:
						$image = imagecreatefrompng($thefile) or
							die($error);
						break;
					default:
						die($error);
					}
					
					 //定义水印图片
					$waterimg='images/water.png' ; //水印图片
					
					//获取水印图片的宽高值
					list($wmk_width, $wmk_height) = getimagesize($waterimg);
					
					//定义水印图片在背景图片上的坐标
					$x = ($width - $wmk_width) / 2;
					$y = ($height - $wmk_height) / 2;
					
					//将水印图片写入内存  注意水印图片的格式要与imagecreatefrompng相符!
					$wmk = imagecreatefrompng($waterimg);
					//合并图片 即添加水印
					imagecopymerge($image, $wmk, $x, $y, 0, 0, $wmk_width, $wmk_height, 80);
					
					//清除水印图片内存
					imagedestroy($wmk);
					
					//保存已添加水印的新图片
					imagejpeg($image, $thefile, 100);
					//添加水印过程结束
				}
				
				//Signal the parent to load the image.
				?>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<script type="text/javascript" src="js/functions.js"></script>
				<script type="text/javascript">
				//关键函数 向产品表单中传递上传图片的路径
				window.onload = chuanzhi;
				function chuanzhi()
				{
					//因上传图片使用了伪ajax方法实现图片预览 所以在产品表单中的iframe框架中又有一个子iframe
					//此页面发送目标为子iframe(name为uploadframe), 故向产品表单中传值需使用 parent.parent 语法
					var imginput = parent.parent.document.getElementById("pro_img");
					imginput.value = "<?php echo $thefile; ?>";
				}
				</script>
				</head>
				<body>
					<img src="<?=$thefile?>" onload="doneloading (parent,'<?=$thefile?>')"/>
				</body>
				</html>	
				<?php
			}
		}
	}
}

?>