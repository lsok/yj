<?php
//���ļ�����Ϊ�ڱ�����ʾ�ϴ����ͼƬ
session_start();

if (is_file ($_GET['thefile']) && file_exists ($_GET['thefile'])){
	$aryImageSize=getimagesize($_GET['thefile']);//[0]:��;[1]:��;[2]:ͼƬ��ʽ;[3]��ͼƬ����ַ�������height=XX width=XX
	$defineWidth=180;
	
	//ͼƬ�ߴ磺����img��ǩ�е�"180"��"160"����Ϊ�̶�ֵ,��ʹ�ô˴���$imgWidth��$imgHeight�ɱ���ͼƬ�������
	$imgWidth=$defineWidth;
	$imgHeight=floor(($defineWidth/$aryImageSize[0])*$aryImageSize[1]);
?>
	<img src="<?=$_GET['thefile']?>" alt="" width="180" height="160"/>
<?php
}
?>