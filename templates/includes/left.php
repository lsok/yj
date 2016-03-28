<div class="inner_left"><!-- 左列开始 -->
<?php
//左列产品导航
echo $pro_nav;
?>

<?php
//左栏新闻列表　新闻页、留言页、联系方式页不显示
if (($pagename == '/company.php') || ($pagename == '/product.php') || ($pagename == '/product_view.php'))
{
	echo $left_news;
}
?>

<!--如果不是联系方式页就显示联系小版块-->
<?php if ($pagename != '/contact.php') { ?>
<div class="inner_contact"><!-- 联系方式  -->
		<h1><span>联系我们</span></h1>
		<img src="templates/images/inner_contact.jpg" />
		<ul>
		<li>销售电话: <?php echo $phone[0]; ?></li>
		<li>图文传真: <?php echo $baseinfo['FOX']; ?></li>
		<li>移动电话: <?php echo $phone[1]; ?></li>
		<li>客服QQ: <?php echo $baseinfo['QQ']; ?></li>
		<li>公司地址: <?php echo $address[0]; ?></li>
		</ul>
</div>
<?php } ?>
</div>				   		<!-- 左列结束 -->

