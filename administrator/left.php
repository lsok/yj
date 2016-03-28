<?php
include '401.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧菜单</title>
<link rel="stylesheet" href="css/left.css" type="text/css" />
<script type="text/javascript" src="js/sdmenu.js">
</script>
<script type="text/javascript">
// <![CDATA[
var myMenu;
window.onload = function() {
myMenu = new SDMenu("my_menu");
myMenu.init();
};
// ]]>
</script>
<style type="text/css">
html {
overflow-x:hidden;                      /*隐藏底部的横向滚动条*/ 
scrollbar-arrow-color:#ccc;          /*三角箭头的颜色*/ 
scrollbar-3dlight-color:#242424;        /*立体滚动条亮边的颜色*/ 
scrollbar-highlight-color:#242424;      /*滚动条空白部分的颜色*/ 
scrollbar-shadow-color:#242424;        /*立体滚动条阴影的颜色*/ 
scrollbar-darkshadow-color:#ccc;    /*滚动条强阴影颜色*/ 
scrollbar-track-color:#767474;          /*立体滚动条背景颜色*/ 
scrollbar-base-color:#242424;          /*滚动条的基本颜色*/
}
</style>
</head>
<body>

<div id="sidebar">

<!-- Logo -->
<div id="logo"><img src="images/logo.gif" /></div>

<div id="view_out">
<a href="../index.php" title="查看站点" class="viewsite" target="_blank">查看站点</a> | <a href="logout.php" title="退出登录" class="logout">退出登录</a>
</div>

<div id="my_menu" class="sdmenu">
      <div class="collapsed">
        <span>基本信息</span>
        <a href="basic.php" target="mainFrame">基本信息</a>
      </div>
      <div class="collapsed">
        <span>文章管理</span>
        <a href="articles_manage.php?list" target="mainFrame">文章管理</a>
        <a href="article_cates.php" target="mainFrame">文章分类</a>
      </div>
      <div class="collapsed">
        <span>产品管理</span>
        <a href="products_manage.php?list" target="mainFrame">产品管理</a>
        <a href="product_cates.php" target="mainFrame">产品分类</a>
      </div>
	  <div class="collapsed">
        <span>单页管理</span>
        <a href="page_manage.php" target="mainFrame">单页管理</a>
	 </div>
	  <div class="collapsed">
        <span>图片管理</span>
        <a href="images_manage.php?list" target="mainFrame">图片管理</a>
        <a href="images_cates.php" target="mainFrame">图片分类</a>
      </div>
	  <div class="collapsed">
		<span>留言管理</span>
		<a href="guest_manage.php" target="mainFrame">留言管理</a>
		<a href="guest_param.php" target="mainFrame">参数设置</a>     
	 </div>
	 <div class="collapsed">
        <span>友情链接</span>
        <a href="link_manage.php?list" target="mainFrame">链接管理</a>
        <a href="link_group_manage.php?list" target="mainFrame">链接组管理</a>
	 </div>
	 <div class="collapsed">
        <span>用户管理</span>
        <a href="user.php" target="mainFrame">管理员设置</a>
     </div>
	 
	 <div class="collapsed">
        <span>轮换图片</span>
		 <a href="banner_manage.php?list" target="mainFrame">图片管理</a>
	 </div>
	 <div class="collapsed">
        <span>在线客服</span>
		 <a href="qqkefu.php" target="mainFrame">客服设置</a>
	 </div>
	 <div class="collapsed">
        <span>数据备份</span>
		 <a href="backup.php" target="mainFrame">数据备份</a>
     </div>
</div>
	
</div>
</body>
</html>
