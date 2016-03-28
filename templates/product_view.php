<?php
/*
|
|--------------------------------------------------------------------------
| 产品详情页模板
|--------------------------------------------------------------------------
|
*/
include 'includes/header.php';
?>

<div class="inner_content"><!-- 包含几列的主内容区开始 -->

	<?php
	//包含左列 产品导航
	include 'includes/left.php';
	?>

	<div class="inner_center"><!-- 中列开始  -->
	
		<div class="breadcrumb"><!-- 面包屑导航开始  -->
		
			<h1><span>您的位置</span>:<a href="index.html">首页</a> - <a href="product.html">产品列表</a><?php if (isset($breadcrumb)) echo $breadcrumb; ?></h1>
		
		</div>								 <!-- 面包屑导航结束  -->
		
		<div class="core_content"><!-- 中列内的核心内容区开始 -->
		<?php
		//输出产品详情
		echo $product_details;
		
		//输出相关产品
		echo $related_product;
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
