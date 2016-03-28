<?php
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
include 'includes/PHPMailer/class.phpmailer.php';
include 'includes/PHPMailer/class.smtp.php';
session_start();

//如果客户提交留言 先写入数据库
if (isset($_GET['add']))
{
	$user_name = (isset($_POST['user_name'])) ? trim($_POST['user_name']) : '';
	$email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
	$qqmsn = (isset($_POST['qqmsn'])) ? trim($_POST['qqmsn']) : '';
	$phone = (isset($_POST['phone'])) ? trim($_POST['phone']) : '';
	$mobile = (isset($_POST['mobile'])) ? trim($_POST['mobile']) : '';
	$address = (isset($_POST['address'])) ? trim($_POST['address']) : '';
	$company = (isset($_POST['company'])) ? trim($_POST['company']) : '';
	$con_title = (isset($_POST['con_title'])) ? trim($_POST['con_title']) : '';
	$content = (isset($_POST['content'])) ? trim($_POST['content']) : '';
	$is_mailme = (isset($_POST['mailme']) && ($_POST['mailme'] == 1)) ? 1 : 1;
	
	//如前台显示“有回复时发邮件给我”选项，则允许用户选择(使用此注释掉的语句)；否则总是在回复时发邮件给用户
	//$is_mailme = (isset($_POST['mailme']) && ($_POST['mailme'] == 1)) ? 1 : 0;
	
	//检索管理员设置的留言参数
	$query_param = sprintf('SELECT * FROM %sguest_param', DB_TBL_PREFIX);

	mysql_query("set names 'utf8'");
	$result = mysql_query($query_param, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row = mysql_fetch_assoc($result);
	
	//定义留言参数变量供PHPmailer使用 包括: 是否发邮件给管理员 管理员邮箱 SMTP服务器信息
	$is_mail = $row['IS_MAIL'];
	$is_vericode = $row['IS_VERICODE'];
	$receive_email = $row['RECEIVE_EMAIL'];
	$smtphost = $row['REPLY_SMTP'];
	$smtpuser = $row['REPLY_SMTP_USERNAME'];
	$smtpps = $row['REPLY_SMTP_PASSWORD'];
	
	mysql_free_result($result);
		
	//如果开启了验证码 则检查验证码
	if ($is_vericode == 1) 
	{
		if ($_POST['captcha'] != $_SESSION['captcha'])
		{
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
			echo '<script>alert("验证码错误!");history.back(-1);</script>';
			die();
		}
	}
	
	// 如果必填项不为空 则写入数据
	if ($user_name != '' && $mobile != '' && $con_title != '' && $content != '')
	{ 
		$query = sprintf ('INSERT INTO %sguestbook (USER_NAME, EMAIL, QQMSN, PHONE, MOBILE, ADDRESS, COMPANY, CON_TITLE, CONTENT, IS_MAIL, SUBMIT_DATE, REPLY_DATE) ' .
					'VALUES ("%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%d", "%s", "%s")',
					DB_TBL_PREFIX,
					mysql_real_escape_string($user_name, $GLOBALS['DB']),
					mysql_real_escape_string($email, $GLOBALS['DB']),
					mysql_real_escape_string($qqmsn, $GLOBALS['DB']),
					mysql_real_escape_string($phone, $GLOBALS['DB']),
					mysql_real_escape_string($mobile, $GLOBALS['DB']),
					mysql_real_escape_string($address, $GLOBALS['DB']),
					mysql_real_escape_string($company, $GLOBALS['DB']),
					mysql_real_escape_string($con_title, $GLOBALS['DB']),
					mysql_real_escape_string($content, $GLOBALS['DB']),
					$is_mailme,
					date('Y-m-d H:i:s'),
					date('Y-m-d H:i:s'));                
			
		mysql_query("set names 'utf8'");
		mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		
	
		//如果参数中设置了同时发送邮件 则将留言发送到指定的管理员邮箱
		if($is_mail == 1)
		{
			$mail                   =        new PHPMailer();
			$mail->IsSMTP();                      //使用SMTP方式发信
			$mail->SMTPAuth  =        true;   // SMTP是否需要验证(STMP服务器基本都需要验证)
			$mail->CharSet     =        "UTF-8"; //语言编码
			
			$mail->Host          =        $smtphost;    // SMTP服务器地址
			$mail->Username   =        $smtpuser;   // 发信邮箱登录用户名
			$mail->Password   =        $smtpps;   //发信邮箱登录密码
			
			$mail->From = "servicelsok@163.com";   //发件人地址 即完整的SMTP发信邮箱
			$mail->FromName = "客户在线咨询";    //发件人名称 设置一个醒目的名称即可 目的是邮件不被忽略

			$mail->AddAddress($receive_email);  //接收留言的管理员邮箱
			$mail->AddReplyTo($email);  //留言客户的邮件地址,管理员可在收到邮件后直接回复
			$mail->WordWrap   = 50;
			$mail->IsHTML(true);

			$mail->Subject    = $con_title;   //邮件标题  此处设置为客户留言的标题
			
			//定义邮件主体内容
			$mail_content = "<h2 style='font-size:15px;border-bottom:1px solid #000'>" . $con_title . "</h2>";
			$mail_content .= "<div>" . $content . "</div>";
			$mail_content .= "<ul>";
			$mail_content .= "<li>客户姓名:" . $user_name . "</li>";
			$mail_content .= "<li>邮件地址:" . $email . "</li>";
			$mail_content .= "<li>QQ/MSN:" . $qqmsn . "</li>";
			$mail_content .= "<li>电话号码:" . $phone . "</li>";
			$mail_content .= "<li>手机号码:" . $mobile . "</li>";
			$mail_content .= "<li>客户地址:" . $address . "</li>";
			$mail_content .= "<li>公司名称:" . $company . "</li>";
			
			$mail->Body = $mail_content; //邮件主体内容
				   
			$mail->Send(); //发送邮件
		}
		
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("留言发送成功!");location.href="feedback.html";</script>';
	}
	else
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("请将必填项填写完整!");history.back(-1);</script>';
	}
}

//如果是管理员回复留言或编辑回复
if (isset($_GET['reply']))
{
	//接收留言ID与回复内容
	$guest_id = $_POST['guest_id'];
	$content_reply = (isset($_POST['reply_content'])) ? trim($_POST['reply_content']) : '';
	
	if (!$content_reply)
	{
		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
		echo '<script>alert("回复不能为空!");history.back(-1);</script>';
		die();
	}
	
	//更新留言回复字段与回复时间字段
	$query = sprintf('UPDATE %sguestbook SET ' .
				'CONTENT_REPLY = "%s",
				 REPLY_DATE = "%s"
				 WHERE
				 ID = %d',
				 
				 DB_TBL_PREFIX,
				 mysql_real_escape_string($content_reply, $GLOBALS['DB']),
				 date('Y-m-d H:i:s'),
				 $guest_id);
				 
		
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//检查用户是否同意接收邮件
	$query = sprintf('SELECT USER_NAME, EMAIL, IS_MAIL FROM %sguestbook WHERE ID = %d', DB_TBL_PREFIX, $guest_id);
	
	mysql_query("set names 'utf8'");
	$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	$row = mysql_fetch_assoc($result);
	
	$is_mail = $row['IS_MAIL']; //用户是否同意接收邮件
	$user_name = $row['USER_NAME']; //用户名称
	$user_email = $row['EMAIL']; //用户邮件地址
	
	mysql_free_result($result);
	
	
	//如果用户同意接收邮件 则同时把回复内容发送到客户邮箱
	if($is_mail == 1)
	{
		//取出SMTP服务器等参数
		$query = sprintf('SELECT * FROM %sguest_param', DB_TBL_PREFIX);
		
		mysql_query("set names 'utf8'");
		$result = mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
		$row = mysql_fetch_assoc($result);
		
		$manage_email = $row['RECEIVE_EMAIL']; //管理员邮箱 设置AddReplyTo属性 以使客户收到邮件后可直接给管理员回复邮件
		$smtphost = $row['REPLY_SMTP']; //SMTP服务器
		$smtpuser = $row['REPLY_SMTP_USERNAME']; //SMTP用户名
		$smtpps = $row['REPLY_SMTP_PASSWORD']; //SMTP密码
		
		mysql_free_result($result);
	
		$mail                   =        new PHPMailer();
		$mail->IsSMTP();                      //使用SMTP方式发信
		$mail->SMTPAuth  =        true;   // SMTP是否需要验证(STMP服务器基本都需要验证)
		$mail->CharSet     =        "UTF-8"; //语言编码
		
		$mail->Host          =        $smtphost;    // SMTP服务器地址
		$mail->Username   =        $smtpuser;   // 发信邮箱登录用户名
		$mail->Password   =        $smtpps;   //发信邮箱登录密码
		
		$mail->From = "servicelsok@163.com";   //发件人地址 即完整的SMTP发信邮箱
		$mail->FromName = "留言回复";    //发件人名称 设置一个醒目的名称即可 目的是邮件不被忽略

		$mail->AddAddress($user_email);  //接收回复的客户邮箱
		$mail->AddReplyTo($manage_email);  //网站管理员邮件地址,客户收到回复邮件后可直接回复
		$mail->WordWrap   = 50;
		$mail->IsHTML(true);

		$mail->Subject    = "您的留言已回复";   //邮件标题  此处设置为客户留言的标题
		
		//定义邮件主体内容
		$mail_content = "<h3>" . $user_name . ",您好!</h3>";
		$mail_content .= "<div>" . $content_reply . "</div>";
		$mail_content .= "<div>" . date('Y-m-d H:i:s') . "</div>";
		
		$mail->Body = $mail_content; //邮件主体内容
			   
		$mail->Send(); //发送邮件
	}
		
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("回复成功!");location.href="administrator/guest_manage.php";</script>';
}

//删除留言
if (isset($_GET['del']))
{
	//接收要删除的留言ID
	$guest_id = $_GET['id'];
	
	$query = sprintf('DELETE FROM %sguestbook WHERE ID = %d', DB_TBL_PREFIX, $guest_id);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
	echo '<script>alert("留言已删除!");location.href="administrator/guest_manage.php";</script>';
}
?>