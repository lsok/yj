<?php
/*
|
|--------------------------------------------------------------------------
| 首页模板
|--------------------------------------------------------------------------
|
*/
//包含页头
include 'includes/header.php';
?>

<div class="content"><!-- 包含几列的主内容区开始 -->

	<div class="left">
		<h1><span>公司简介</span><a href="company.html">更多 >></a></h1>
		<div class="text"><!--公司简介-->
		<img src="templates/images/qxccj.jpg" alt="天源水泵管" style="float:left;border:1px solid #ccc;margin-right:8px;" />
沧州齐鑫管道有限公司位于河北省沧州市孟村县，本公司始建于1998年，是专业三通生产厂家，主要生产大口径三通、等径三通、异径三通、镀锌三通、接嘴三通等系列产品。本公司技术力量雄厚，生产设备先进……
		</div>
	</div>

	<div class="center"><!-- 特价商品  -->
		<h1><span>最新动态</span><a href="news-1.html">更多 >></a></h1>
		<div class="index_news">
			<?php echo $news; ?>
		</div>
	</div>						 

	<div class="right"><!-- 联系方式  -->
		<h1><span>联系方式</span><a href="contact.html">更多 >></a></h1>
		<div class="img"><img src="templates/images/index_kf.jpg" alt="承插件客服"/></div>
		<ul>
		<li>电话:<?php echo $phone[0]; ?></li>
		<li>传真:<?php echo $phone[0]; ?></li>
		<li>手机:<?php echo $phone[1]; ?></li>
		<li>客服QQ:<?php echo $baseinfo['QQ']; ?></li>
		<li>邮件:<?php echo $baseinfo['EMAIL']; ?></li>
		</ul>
	</div>
	
	<div class="clear"></div>

	<div class="left index_pronav"><!-- 产品导航区 -->
		<h1><span>产品导航</span><a href="product.html">更多 >></a></h1>
		<ul class="index_pro_nav">
			<li class="no">
			<a href="product-18-1.html">大口径三通<span>wide tee</span></a>
			</li>
			
			<li>
			<a href="product-19-1.html">等径三通<span>equal tee</span></a>
			</li>
			
			<li class="no">
			<a href="product-20-1.html">镀锌三通<span>Galvanized tee</span></a>
			</li>
			
			<li>
			<a href="product-21-1.html">焊接三通<span>welded tee</span></a>
			</li>
			
			<li class="no">
			<a href="product-22-1.html">厚壁三通<span>thick wall tee</span></a>
			</li>
			
			<li>
			<a href="product-23-1.html">接嘴三通<span>mouthpiece tee</span></a>
			</li>
			
			<li class="no">
			<a href="product-25-1.html">冷拨三通<span>Cold dial tee</span></a>
			</li>
			
			<li>
			<a href="product-26-1.html">异径三通<span>reducing tee</span></a>
			</li>
			
		</ul>	
	</div>
	
	<div class="index_pro_show"><!-- 产品展示区 -->
		<h1><span>三通系列</span><a href="product.html">更多 >></a></h1>
		<?php echo $products; ?>
	</div>
	
	<div class="clear"></div>
	
</div>						 <!-- 包含几列的主内容区结束 -->

<?php
//包含页脚
include 'includes/footer.php';
?>

</div><!-- 页面容器结束 -->
</body>
</html>
