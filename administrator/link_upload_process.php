<?php
include '401.php';
include 'includes/Constant.php';

//���������ϴ���ͼƬ��ʽ
$allowedtypes = array ("image/jpeg","image/pjpeg","image/png","image/gif","image/bmp");

//����ͼƬ�ϴ��󱣴��Ŀ¼
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
			
			//����ļ���׺����������
			$pinfo=pathinfo($_FILES['myfile']['name']);
			$ftype=$pinfo['extension'];
			$thefile = $savefolder . time() . "." . $ftype;
			
			if (!move_uploaded_file ($_FILES['myfile']['tmp_name'], $thefile))
			{
				echo "�ϴ�ͼƬ���ִ���!";
			}
			else
			{
				?>
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<script type="text/javascript" src="js/link_functions.js"></script>
				<script type="text/javascript">
				//�ؼ����� ���Ʒ���д����ϴ�ͼƬ��·��
				window.onload = chuanzhi;
				function chuanzhi()
				{
					//���ϴ�ͼƬʹ����αajax����ʵ��ͼƬԤ�� �����ڲ�Ʒ���е�iframe���������һ����iframe
					//��ҳ�淢��Ŀ��Ϊ��iframe(nameΪuploadframe), �����Ʒ���д�ֵ��ʹ�� parent.parent �﷨
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