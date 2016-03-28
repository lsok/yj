<?php
/*
|
|--------------------------------------------------------------------------
| 在线留言页模板
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
			
				<h1><span>您的位置</span>:<a href="index.html">首页</a> - 在线留言</h1>
			
			</div>								 <!-- 面包屑导航结束  -->
			
			<div class="core_content"><!-- 中列内的核心内容区开始 -->
				
				<script language="JavaScript"> 
				//验证留言
				function checkform() 
				{
					if (document.liuyan.user_name.value == "" ) 
					{ 
						alert ("请填写您的姓名!"); 
						return false; 
					} 
					if (document.liuyan.mobile.value == "" ) 
					{ 
						alert ("请填写手机号码!"); 
						return false; 
					}
					if (document.liuyan.con_title.value == "" ) 
					{ 
						alert ("请填写留言标题!"); 
						return false; 
					}
					if (document.liuyan.content.value == "" ) 
					{ 
						alert ("请填写留言内容!"); 
						return false; 
					}
				} 
				</script>
				
				<script language="javascript" type="text/javascript">
				//点击刷新验证码函数
				function RefreshImage()
				{
					var el =document.getElementById("imgchange");
					el.src=el.src+'?';
				}
				</script>
				
				<!-- 留言表单开始 -->
				<form action="guest_process.php?add" method="post" name="liuyan" onsubmit="return checkform()">
					<div class="guestbook">
						<p>
						<label for="user_name">您的姓名 <span>*</span></label>
						<input type="text" name="user_name" id="user_name"/>
						</p>
						
						<p>
						<label for="mobile">手机号码 <span>*</span></label>
						<input type="text" name="mobile" id="mobile"/>
						</p>
						
						<p>
						<label for="phone">电话号码</label>
						<input type="text" name="phone" id="phone"/>
						</p>
						
						<p>
						<label for="email">电子邮件 </label>
						<input type="text" name="email" id="email"/>
						</p>

						<p>
						<label for="qqmsn">腾讯QQ号</label>
						<input type="text" name="qqmsn" id="qqmsn"/>
						</p>

						<p>
						<label for="address">联系地址</label>
						<input type="text" name="address" id="address"/>
						</p>

						<p>
						<label for="company">公司名称</label>
						<input type="text" name="company" id="company"/>
						</p>

						<p>
						<label for="con_title">留言标题 <span>*</span></label>
						<input type="text" name="con_title" value="<?php if (isset($proName)) {echo $proName;} ?>" id="con_title"/>
						</p>

						<p>
						<label for="content">咨询内容 <span>*</span></label>
						<textarea name="content" cols="30" rows="5"></textarea>
						</p>
						
						<?php 
						//如果前台显示留言回复则显示此选项
						if($guest_row['IS_SHOW'] == 1) 
						{ ?>
						<p>
						<input type="checkbox" name="mailme" value="1"  checked="checked" class="checkbox" /> 回复时发邮件给我
						</p>   
						<?php } ?>
						
						<?php
						//如果设置了显示验证码
						if($guest_row['IS_VERICODE'] == 1) 
						{ ?>
						<p>
						<label for="con_title">验证码 </label>
						<input type="text" name="captcha" id="con_title" class="verinput"/>
						<img src="administrator/images/captcha.php?nocache=<?php echo time(); ?>" id="imgchange" onmouseup="RefreshImage()"​​​​​​​​ class="vericodeimg" />
						</p>
						<?php } ?>

						<p>
						<input type="submit" value="提交留言" class="submit" />
						<input type="reset" value="重新填写" class="reset" />
						</p>
					</div>
				</form>		<!-- 留言表单结束 -->
					
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
