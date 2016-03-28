<?php
/*
|
|--------------------------------------------------------------------------
| 单页模板 公司介绍
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
			
				<h1><span>您的位置</span>:<a href="index.html">首页</a> - 公司介绍</h1>
			
			</div>								 <!-- 面包屑导航结束  -->
			
			<div class="core_content"><!-- 中列内的核心内容区开始 -->
				<div class="company">
				<?php
				//输出公司介绍
				echo $company;
				?>
				</div>
				
			</div>								   <!-- 中列内的核心内容区结束 -->
			
			<div class="product_show"><!-- 商品展示区开始 -->
				<h1><span>最新产品展示</span></h1>
				<?php
				echo $products;
				?>
			</div>										<!-- 商品展示区结束 -->
					
	</div>						 <!-- 中列结束 -->

	<div class="clear"></div><!-- 清除浮动 -->

</div>						 <!-- 包含几列的主内容区结束 -->

<?php
include 'includes/footer.php';
?>

</div><!-- 页面容器结束 -->
</body>
</html>
