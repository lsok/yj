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
			
			//����ļ���׺����������
			$pinfo=pathinfo($_FILES['myfile']['name']);
			$ftype=$pinfo['extension'];
			$thefile = $savefolder . time() . "." . $ftype;
			
			if (!move_uploaded_file ($_FILES['myfile']['tmp_name'], $thefile))
			{
				echo "There was an error uploading the file.";
			} 
			else
			{
				//���ݲ�Ʒ�������þ����Ƿ����ˮӡ
				if(WATERMARK == 1)
				{
					//������ϴ�ͼƬ�Ŀ��ֵ������
					list($width, $height, $type, $attr) = getimagesize($thefile);
					
					//����ͼƬ��ʽ�����ϴ�ͼƬд���ڴ�
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
					
					 //����ˮӡͼƬ
					$waterimg='images/water.png' ; //ˮӡͼƬ
					
					//��ȡˮӡͼƬ�Ŀ��ֵ
					list($wmk_width, $wmk_height) = getimagesize($waterimg);
					
					//����ˮӡͼƬ�ڱ���ͼƬ�ϵ�����
					$x = ($width - $wmk_width) / 2;
					$y = ($height - $wmk_height) / 2;
					
					//��ˮӡͼƬд���ڴ�  ע��ˮӡͼƬ�ĸ�ʽҪ��imagecreatefrompng���!
					$wmk = imagecreatefrompng($waterimg);
					//�ϲ�ͼƬ �����ˮӡ
					imagecopymerge($image, $wmk, $x, $y, 0, 0, $wmk_width, $wmk_height, 80);
					
					//���ˮӡͼƬ�ڴ�
					imagedestroy($wmk);
					
					//���������ˮӡ����ͼƬ
					imagejpeg($image, $thefile, 100);
					//���ˮӡ���̽���
				}
				
				//Signal the parent to load the image.
				?>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<script type="text/javascript" src="js/functions.js"></script>
				<script type="text/javascript">
				//�ؼ����� ���Ʒ���д����ϴ�ͼƬ��·��
				window.onload = chuanzhi;
				function chuanzhi()
				{
					//���ϴ�ͼƬʹ����αajax����ʵ��ͼƬԤ�� �����ڲ�Ʒ���е�iframe���������һ����iframe
					//��ҳ�淢��Ŀ��Ϊ��iframe(nameΪuploadframe), �����Ʒ���д�ֵ��ʹ�� parent.parent �﷨
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