<?php
/*
|
|--------------------------------------------------------------------------
| 新闻详情页模板
|--------------------------------------------------------------------------
|
*/
include 'includes/header.php';
?>

<div class="inner_content"><!-- 包含几列的主内容区开始 -->

	<?php
	//左列
	include 'includes/left.php';
	?>

	<div class="inner_center"><!-- 中列开始  -->
	
		<div class="breadcrumb"><!-- 面包屑导航开始  -->
		
			<h1><span>您的位置</span>:<a href="index.html">首页</a><?php echo $breadcrumb; ?></h1>
		
		</div>								 <!-- 面包屑导航结束  -->
		
		<div class="core_content"><!-- 中列内的核心内容区开始 -->
		<?php
		//输出文章内容
		echo $article;
		?>
		
		<!-- 百度分享代码开始 -->
		<div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare" style="margin:20px 0 20px 0"
		<a class="bds_qzone"></a>
		<a class="bds_tsina"></a>
		<a class="bds_tqq"></a>
		<a class="bds_renren"></a>
		<a class="bds_t163"></a>
		<span class="bds_more"></span>
		<a class="shareCount"></a>
		</div>
		<script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=0" ></script>
		<script type="text/javascript" id="bdshell_js"></script>
		<script type="text/javascript">
		document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
		</script>
		<!-- 百度分享代码结束 -->
		
		<?php
		//输出相关文章
		echo $related_news;
		?>
		</div>								   <!-- 中列内的核心内容区结束 -->
		
	</div>						 <!-- 中列结束 -->
	
	<div class="clear"></div><!-- 清除浮动 -->
			
</div>						 <!-- 包含几列的主内容区结束 -->

<?php
include 'includes/footer.php';
?>

</div><!-- 页面容器结束 -->
</body>
</html>
