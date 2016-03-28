<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title><?php echo (isset($unique_title)) ? ($unique_title . '_' . $baseinfo['SITENAME']) : $baseinfo['SITE_TITLE']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo (isset($unique_description)) ? $unique_description : $baseinfo['SITE_DESCRIPTION']; ?>" />
<meta name="keywords" content="<?php echo (isset($unique_keywords)) ? $unique_keywords : $baseinfo['SITE_KEYWORDS']; ?>" />
<!-- 站点模板CSS文件开始 -->
<link rel="stylesheet" type="text/css" href="templates/style/reset.css" media="all"/>
<link rel="stylesheet" type="text/css" href="templates/style/layout.css" media="all"/>
<link rel="stylesheet" type="text/css" href="templates/style/widget.css" media="all"/>
<!-- 站点模板CSS文件结束 -->
<!-- 轮换大图SuperSlide插件所需CSS与JS文件开始 -->
<link rel="stylesheet" type="text/css" href="templates/style/superslide.css" media="all"/>
<script src="templates/js/jquery.pack.js" type="text/javascript"></script>
<script src="templates/js/jquery.SuperSlide.js" type="text/javascript"></script>
<!-- 轮换大图SuperSlide插件所需CSS与JS文件结束 -->
<!-- 浮动QQ客服所需公共样式 -->
<link rel="stylesheet" type="text/css" href="templates/style/public.css"/>
<?php
//如后台开启浮动客服则引入必要文件与代码
if ($qqkf['IS_SHOW'] == 1)
{
?>
<link rel="stylesheet" type="text/css" href="templates/style/<?php echo $qqkf['QQ_COLOR']; ?>.css"/>
<!-- 此templates/js/jquery.min.js文件为浮动客服插件必要文件，但使用SuperSlide轮换大图插件后此文件可不用(如继续使用此文件则导致轮换图无法正常显示，此处注释掉此文件，并不影响在线客服插件正常使用，因为此文件的代码功能在轮换图插件中已具备)
<script src="templates/js/jquery.min.js" type="text/javascript"></script>
此templates/js/jquery.min.js文件为浮动客服插件必要文件，但使用SuperSlide轮换大图插件后此文件可不用(如继续使用此文件则导致轮换图无法正常显示，此处注释掉此文件，并不影响在线客服插件正常使用，因为此文件的代码功能在轮换图插件中已具备) -->
<script language="javascript" src="templates/js/jquery.Sonline.js"></script>
<script type="text/javascript">
$(function(){
	$().Sonline({
		Position:"<?php echo $qqkf['QQ_POSITION']; ?>",//left或right
		Top:<?php echo $qqkf['QQ_TOP']; ?>,//顶部距离，默认200px
		Width:165,//控件宽度，默认200px
		Style:<?php echo $qqkf['QQ_STYLE']; ?>,//QQ图标风格共6种，默认显示第一种：1
		Effect:<?php echo ($qqkf['QQ_EFFECT'] == 0) ? 'true' : 'false'; ?>, //滚动或者固定两种方式，布尔值：true或false
		DefaultsOpen:<?php echo ($qqkf['QQ_OPEN'] == 0) ? 'true' : 'false'; ?>, //默认展开：true,默认收缩：false
		Tel:"<?php echo $qqkf['KF_TEL']; ?>",//其它信息图片等
		Qqlist:"<?php echo $qqkf['QQ_NUMS']; ?>" //多个QQ用','隔开，QQ和客服名用'|'隔开
	});
})	
</script>
<script type="text/javascript">
$(function(){
$(".nav > ul > li").hover(function(){
		$(this).addClass("current");
		var subHeight = ($(this).find(".subNav").find("a").length)*42;
		$(this).find(".subNav").stop(true,true).animate({height:subHeight},"fast");
		},function(){
			$(this).removeClass("current");
			$(this).find(".subNav").animate({height:0},"fast");
	});
})
</script>
<?php } ?>
</head>
<?php
//定义body ID实现主菜单当前页高亮
$pagename = $_SERVER['PHP_SELF'];

switch ($pagename)
{
	//首页
	case '/index.php': 
	$bid = 'ind';
   break;
   
   //公司介绍页
   case '/company.php':
	$bid = 'com';
	break;
	
	//产品列表页
	case '/product.php':
	$bid = 'pro';
	break;
	
	//产品详情页
	case '/product_view.php':
	$bid = 'pro';
	break;
	
	//文章列表页
	case '/news.php':
	if ($catid == 1)
	{
		$bid = 'news1';
	}
	else if ($catid == 2)
	{
		$bid = 'news2';
	}
	else if ($catid == 3)
	{
		$bid = 'news3';
	}
	break;
	
	//文章详情页
	case '/news_view.php':
	if ($item_catid == 1)
	{
		$bid = 'news1';
	}
	else if ($item_catid == 2)
	{
		$bid = 'news2';
	}
	else if ($item_catid == 3)
	{
		$bid = 'news3';
	}
	break;
	
	//图片列表页
	case '/picture.php':
	if ($catid == 1)
	{
		$bid = 'pic1';
	}
	else if ($catid == 2)
	{
		$bid = 'pic2';
	}
	break;
	
	//图片详情页
	case '/picture_view.php':
	if ($item_catid == 1)
	{
		$bid = 'pic1';
	}
	else if ($item_catid == 2)
	{
		$bid = 'pic2';
	}
	else if ($item_catid == 3)
	{
		$bid = 'don';
	}
	break;
	
	//留言页
	case '/feedback.php':
	$bid = 'fee';
	break;
	
	//联系我们页
	case '/contact.php':
	$bid = 'con';
	break;
}
?>
<body id="<?php echo $bid; ?>">
<div class="wrap"><!-- 页面容器开始 -->

	<div class="header"><!-- 页面头部开始 -->
		<div class="logo"><img src="templates/images/logo.gif" alt="齐鑫承插件"/></div><!-- 站点LOGO -->
		<ul class="header_contact"><!-- 头部区联系方式或加入收藏开始 -->
			<li><img src="templates/images/right_phone.gif" class="phone" /><span><?php echo $phone['0']; ?></span></li>
			<li><img src="templates/images/right_mobile.gif" class="mobile" /><span><?php echo $phone['1']; ?></span></li>
		</ul>										  <!-- 头部区联系方式或加入收藏结束 -->
	</div>							 <!-- 页面头部结束 -->
	
	<div class="topnav"><!-- 顶部主导航开始 -->
		<ul>
			<li class="ind"><a href="index.html">首页</a></li>
			<li class="com"><a href="company.html">公司简介</a></li>
			<li class="pro"><a href="product.html">产品展示</a></li>
			<li class="news1"><a href="news-1.html">最新动态</a></li>
			<li class="pic1"><a href="picture-1.html">生产设备</a></li>
			<li class="fee"><a href="feedback.html">在线留言</a></li>
			<li class="con"><a href="contact.html">联系我们</a></li>
		</ul>
	</div>						<!-- 顶部主导航结束 -->
	
	<div class="banner"><!-- 轮换banner开始 -->
		<div id="slideBox" class="slideBox">
			<div class="hd">
				<ul>
				<?php
				//根据轮换图数量生成导航数字
				for ($i=0;$i<$banner_num;$i++)
				{
					echo '<li>' . ($i+1) . '</li>';
				}
				?>
				</ul>
			</div>
			<div class="bd">
				<ul>
				<?php 
				//输出轮换大图(图片高度在templates/style/superslide.css第2和第9行修改,导航数字背景在第6行修改)
				foreach ($banner as $img)
				{
					echo '<li><a href="' . $img['IMG_LINK'] . '" target="_blank"><img src="' . $img['IMG_URL'] . '"></a></li>';
				}
				?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
		//轮换大图插件
		jQuery(".slideBox").slide({mainCell:".bd ul",effect:"fold",easing:"swing",autoPlay:true,delayTime:1000,interTime:5000});
		//可选其它效果:
		//jQuery(".slideBox").slide({mainCell:".bd ul",effect:"leftLoop",autoPlay:true}); //向左滑动并循环
		//jQuery(".slideBox").slide({mainCell:".bd ul",effect:"left",autoPlay:true}); //向左滑动(不循环)
		//jQuery(".slideBox").slide({mainCell:".bd ul",effect:"topLoop",autoPlay:true}); //向上滑动并循环
		//jQuery(".slideBox").slide({mainCell:".bd ul",effect:"top",autoPlay:true}); //向上滑动(不循环)
		</script>
	</div>						 <!-- 轮换banner结束 -->