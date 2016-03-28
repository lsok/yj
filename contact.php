<?php
/*
|
|--------------------------------------------------------------------------
| 联系我们页数据
|--------------------------------------------------------------------------
|
*/
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
include 'includes/share.php';
include 'includes/functions.php';

//包含左栏数据(产品导航  联系方式)
include 'includes/leftdata.php';

//定义个性化元标签
$unique_title = '三通_联系方式';


//调用联系我们页模板
include 'templates/contact.php';
?>