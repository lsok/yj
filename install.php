<?php
// 包含文件:开发环境配置 数据库配置 数据库连接
include 'includes/common.php';
include 'ss-config.php';
include 'includes/ss-db.php';
session_start();

// 定义管理员变量，如提交则为提交的值，否则默认为admin
$admname = (isset($_POST['admname'])) ? trim($_POST['admname']) : 'admin';

if (isset($_POST['submitted']))
{
   $admpass1 = (isset($_POST['admpass1'])) ? trim($_POST['admpass1']) : '';
   $admpass2 = (isset($_POST['admpass2'])) ? trim($_POST['admpass2']) : '';
   
   if ($admname !='' && $admpass1 !='' && ($admpass1 === $admpass2))
   {
     $admpass = md5($admpass1); //密码MD5加密
	 
	 //创建站点基本信息表
	 $query = sprintf('CREATE TABLE IF NOT EXISTS %sconfig ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	SITENAME VARCHAR(200) NOT NULL,
	SITENAME_EN VARCHAR(200),
	SITE_TITLE VARCHAR(200) NOT NULL,
	SITE_TITLE_EN VARCHAR(200),
	SITE_DESCRIPTION TEXT NOT NULL,
	SITE_DESCRIPTION_EN TEXT,
	SITE_KEYWORDS TEXT NOT NULL,
	SITE_KEYWORDS_EN TEXT,
	EMAIL VARCHAR(255) NOT NULL,
	PHONE VARCHAR(100) NOT NULL,
	FOX VARCHAR(100),
	QQ VARCHAR(100),
	MSN VARCHAR(100),
	ADDRESS VARCHAR(255),
	ADDRESS_EN VARCHAR(255),
	LINKMAN VARCHAR(100),
	LINKMAN_EN VARCHAR(100),

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' ,DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//写入站点默认基本信息
	$query = sprintf('INSERT INTO %sconfig ('.
	'SITENAME, SITE_TITLE, SITE_DESCRIPTION, SITE_KEYWORDS, PHONE, EMAIL) '.
	'VALUES ("网站名称", "产品名称|产品名称|公司名称", "这是我的公司或产品简介", "关键词,关键词,关键词", "010-8888888", "info@example.com")', DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
  
   
   //创建管理员表 ADMPERMISSION权限字段0为普通管理员，1为超级管理员.超级管理员可管理普通管理员，普通管理
	//员只能修改自己的登录密码
	$query = sprintf('CREATE TABLE IF NOT EXISTS %sadmuser ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	ADMNAME VARCHAR(100) NOT NULL,
	ADMPASS CHAR(50) NOT NULL,
	ADMPERMISSION INTEGER NOT NULL DEFAULT 0,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
   
   //写入管理员信息	
	$query = sprintf('INSERT INTO %sadmuser ('.
	'ADMNAME, ADMPASS, ADMPERMISSION) VALUES ("%s", "%s", 1)',
	 DB_TBL_PREFIX,
	 mysql_real_escape_string($admname, $GLOBALS['DB']),
	 $admpass);
	 
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//创建文章类别表
	$query = sprintf('CREATE TABLE IF NOT EXISTS %sarticles_cates ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	FAT_ID INTEGER UNSIGNED NOT NULL,
	CATENAME VARCHAR(255) NOT NULL,
	CATENAME_EN VARCHAR(255),

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建文章表 IS_SHOW为发布状态 0为已发布 1为未发布;IS_PUBLIC为文章阅读权限 0为公开 1为仅会员可阅读    //;IS_HOMESHOW为是否显示于首页 0为显示 1为不显示;IS_TOP为是否置顶 0为不置顶 1为置顶;
	//IS_DELETE为是否已删除(进入回收站) 0为否 1为是
	 $query = sprintf('CREATE TABLE IF NOT EXISTS %sarticles ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	CAT_ID INTEGER UNSIGNED NOT NULL,
	ART_TITLE VARCHAR(255) NOT NULL,
	ART_TITLE_EN VARCHAR(255),
	ART_CONTENT TEXT NOT NULL,
	ART_CONTENT_EN TEXT,
	AUTHOR VARCHAR(255) NOT NULL,
	AUTHOR_EN VARCHAR(255),
	SUBMIT_DATE DATETIME NOT NULL,
	READCOUNT INTEGER NOT NULL DEFAULT 0,
	IS_SHOW INTEGER NOT NULL DEFAULT 0,
	IS_PUBLIC INTEGER NOT NULL DEFAULT 0,
	IS_HOMESHOW INTEGER NOT NULL DEFAULT 0,
	IS_TOP INTEGER NOT NULL DEFAULT 0,
	IS_DELETE INTEGER NOT NULL DEFAULT 0,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' ,DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//创建产品类别表
	$query = sprintf('CREATE TABLE IF NOT EXISTS %sproducts_cates ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	FAT_ID INTEGER UNSIGNED NOT NULL,
	CATENAME VARCHAR(255) NOT NULL,
	CATENAME_EN VARCHAR(255),
	CATEDESC TEXT,
	CATEDESC_EN TEXT,
	ORDERID INTEGER UNSIGNED,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建产品表 IS_SHOW为发布状态 0为已发布 1为未发布; IS_HOMESHOW为是否显示于首页 0为显示 1为不显示;IS_TOP为是否置顶 0为不置顶 1为置顶;
	//IS_DELETE为是否已删除(进入回收站) 0为否 1为是
	 $query = sprintf('CREATE TABLE IF NOT EXISTS %sproducts ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	CAT_ID INTEGER UNSIGNED NOT NULL,
	PRO_NAME VARCHAR(255) NOT NULL,
	PRO_NAME_EN VARCHAR(255),
	PRO_TYPE VARCHAR(255),
	PRO_PRICE DEC(8,2),
	PRO_IMAGE VARCHAR(255) NOT NULL,
	PRO_CONTENT TEXT,
	PRO_CONTENT_EN TEXT,
	SUBMIT_DATE DATETIME NOT NULL,
	READCOUNT INTEGER NOT NULL DEFAULT 0,
	IS_SHOW INTEGER NOT NULL DEFAULT 0,
	IS_HOMESHOW INTEGER NOT NULL DEFAULT 0,
	IS_TOP INTEGER NOT NULL DEFAULT 0,
	IS_DELETE INTEGER NOT NULL DEFAULT 0,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' ,DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建单页表
	$query = sprintf('CREATE TABLE IF NOT EXISTS %spages ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	FAT_ID INTEGER UNSIGNED NOT NULL,
	PAGE_NAME VARCHAR(255) NOT NULL,
	PAGE_TITLE VARCHAR(255),
	PAGE_TITLE_EN VARCHAR(255),
	PAGE_DESCRIPTION TEXT,
	PAGE_DESCRIPTION_EN TEXT,
	PAGE_KEYWORDS TEXT,
	PAGE_KEYWORDS_EN TEXT,
	PAGE_CONTENT TEXT,
	PAGE_CONTENT_EN TEXT,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建图片类别表
	$query = sprintf('CREATE TABLE IF NOT EXISTS %simages_cates ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	FAT_ID INTEGER UNSIGNED NOT NULL,
	CATENAME VARCHAR(255) NOT NULL,
	CATENAME_EN VARCHAR(255),

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建图片表 IS_SHOW为发布状态 0为已发布 1为未发布; IS_HOMESHOW为是否显示于首页 0为显示 1为不显示;IS_TOP为是否置顶 0为不置顶 1为置顶;
	 $query = sprintf('CREATE TABLE IF NOT EXISTS %simages ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	CAT_ID INTEGER UNSIGNED NOT NULL,
	IMG_NAME VARCHAR(255) NOT NULL,
	IMG_NAME_EN VARCHAR(255),
	IMG_URL VARCHAR(255) NOT NULL,
	IMG_DESC TEXT,
	IMG_DESC_EN TEXT,
	IS_SHOW INTEGER NOT NULL DEFAULT 0,
	IS_HOMESHOW INTEGER NOT NULL DEFAULT 0,
	IS_TOP INTEGER NOT NULL DEFAULT 0,
	IS_DELETE INTEGER NOT NULL DEFAULT 0,
	READCOUNT INTEGER NOT NULL DEFAULT 0,
	SUBMIT_DATE DATETIME NOT NULL,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' ,DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建留言参数表 IS_MAILT为是否将客户留言同时发送到管理员邮箱 0为否 1为是; 
	//IS_SHOW为前台是否显示留言内容 0为否 1为是;IS_VERICODE为是否开启留言验证码,0为否 1为是;
	//RECEIVE_EMAIL为接收客户留言的管理员邮箱;REPLY_SMTP等字段为回复时执行邮件发送的SMTP
	 $query = sprintf('CREATE TABLE IF NOT EXISTS %sguest_param ('.
	'IS_MAIL INTEGER NOT NULL DEFAULT 1,
	IS_SHOW INTEGER NOT NULL DEFAULT 0,
	IS_VERICODE INTEGER NOT NULL DEFAULT 0,
	RECEIVE_EMAIL VARCHAR(255),
	REPLY_SMTP VARCHAR(255),
	REPLY_SMTP_USERNAME VARCHAR(255),
	REPLY_SMTP_PASSWORD VARCHAR(255)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' ,DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//写入默认留言参数
	$query = sprintf('INSERT INTO %sguest_param ('.
	'REPLY_SMTP, REPLY_SMTP_USERNAME, REPLY_SMTP_PASSWORD) '.
	'VALUES ("smtp.163.com", "servicelsok", "753951")', 
	DB_TBL_PREFIX);
	
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建留言表
	 $query = sprintf('CREATE TABLE IF NOT EXISTS %sguestbook ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	USER_NAME VARCHAR(255) NOT NULL,
	EMAIL VARCHAR(255) NOT NULL,
	QQMSN VARCHAR(255),
	PHONE VARCHAR(255),
	MOBILE VARCHAR(255),
	ADDRESS VARCHAR(255),
	COMPANY VARCHAR(255),
	CON_TITLE VARCHAR(255),
	CONTENT TEXT NOT NULL,
	CONTENT_REPLY TEXT,
	IS_MAIL INTEGER NOT NULL DEFAULT 1,
	SUBMIT_DATE DATETIME NOT NULL,
	REPLY_DATE DATETIME NOT NULL,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' ,DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建友情链接组表  TEXT_OR_IMG为链接显示形式 默认0为文字 1为LOGO
	$query = sprintf('CREATE TABLE IF NOT EXISTS %slink_group ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	GROUPNAME VARCHAR(255) NOT NULL,
	TEXT_OR_IMG INTEGER NOT NULL DEFAULT 0,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建链接表
	$query = sprintf('CREATE TABLE IF NOT EXISTS %slinks ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	GROUP_ID INTEGER UNSIGNED NOT NULL,
	LINK_NAME VARCHAR(255) NOT NULL,
	LINK_TEXT VARCHAR(255) NOT NULL,
	LINK_URL VARCHAR(255) NOT NULL,
	LINK_LOGO VARCHAR(255) NOT NULL,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建轮换大图表  ORDERNUM为图片排序号码  从1开始
	$query = sprintf('CREATE TABLE IF NOT EXISTS %sbanner ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	IMG_URL VARCHAR(255) NOT NULL,
	IMG_LINK VARCHAR(255),
	IMG_TITLE VARCHAR(255),
	IMG_DESC VARCHAR(255),
	ORDERNUM INTEGER UNSIGNED NOT NULL,

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//创建浮动QQ客服表  IS_SHOW为默认显示状态 0为不显示 1为显示；QQ_POSITION为控件显示位置(页面左或右侧);
	//QQ_TOP为与页面顶部距离；QQ_COLOR 为界面颜色 默认为蓝色 可选的样式文件有 red.css green.css gray.css
	//QQ_STYLE为显示的QQ客服图标样式(有五种可用样式值：1 2 3 5 6  因第4种效果不好故省略)
	//QQ_EFFECT为滚动或固定 0为滚动 1为固定；QQ_OPEN为默认展开状态 0为展开 1为关闭；KF_TEL为客服电话
	$query = sprintf('CREATE TABLE IF NOT EXISTS %sqqkefu ('.
	'ID INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	IS_SHOW INTEGER NOT NULL DEFAULT 0,
	QQ_NUMS VARCHAR(255),
	QQ_POSITION VARCHAR(255) NOT NULL DEFAULT "right",
	QQ_TOP INTEGER NOT NULL DEFAULT 160,
	QQ_COLOR VARCHAR(255) NOT NULL DEFAULT "default_blue",
	QQ_STYLE INTEGER NOT NULL DEFAULT 1,
	QQ_EFFECT INTEGER UNSIGNED NOT NULL DEFAULT 0,
	QQ_OPEN INTEGER UNSIGNED NOT NULL DEFAULT 0,
	KF_TEL VARCHAR(255) NOT NULL DEFAULT "400-655-8888",

	PRIMARY KEY (ID)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;', DB_TBL_PREFIX);
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	//写入QQ客服默认信息	
	$query = sprintf('INSERT INTO %sqqkefu (QQ_NUMS) VALUES ("615912549|售前咨询,402719549|售前咨询,402719549|售后咨询,402719549|技术支持")', DB_TBL_PREFIX);
	 
	mysql_query("set names 'utf8'");
	mysql_query($query, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	
	//设置session
	$_SESSION['admuser'] = $admname;
	$_SESSION['admpermission'] = 1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SimSite安装</title>
<link rel="stylesheet" type="text/css" href="css/install.css" />
</head>
<body>
<div id="wrap">
<h1>安装SimSite成功!</h1>
<p>安装已成功完成，请点击下面的按钮登录管理后台——</p>
<input type="button" value="进入管理后台" onclick="javascript:location='administrator/index.php';" class="success" />
<input type="button" value="浏览网站" onclick="javascript:location='index.php';" class="success" />
</div>
</body>
</html>
<?php
   die();
   }
   else 
   {
     //定义错误信息数组
     $errors = array();
	 
	 if ($admname == '')
     {$errors[] = '请输入字母或数字格式的管理员名称!';}
	 elseif ($admpass1 == '' or ($admpass1 !== $admpass2))
	 {$errors[] = '请输入密码并确保两次输入一致!';}
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SimSite安装</title>
<link rel="stylesheet" type="text/css" href="css/install.css" />
</head>
<body>
<div id="wrap">
<h1>欢迎安装SimSite系统</h1>
<p>请设置管理员名称和密码，点击“一键安装”，安装程序会在您指定的MySQL数据库中创建表并写入一些默认信息，安装完成后，可随时登录系统修改这些信息。</p>
<?php
//如出错则显示错误提示
if (isset($errors)) {
  echo '<ul class="err">';
  foreach ($errors as $error) {
   echo '<li>' . $error . '</li>';
  }
  echo '</ul>';
}
?>
<form method="post" action="install.php">
<table>
<tr><td><label for="admname">管理员</label></td><td><input type="text" name="admname" id="admname" maxlength="100" value="<?php echo $admname; ?>" /></td></tr>
<tr><td><label for="admpass1">密码</label></td><td><input type="password" name="admpass1" id="admpass1" maxlength="80" /></td></tr>
<tr><td><label for="admpass2">重复密码</label></td><td><input type="password" name="admpass2" id="admpass2" maxlength="80" /></td></tr>
<tr><td colspan="2"><input type="submit" value="一键安装" id="submit" /><input type="hidden" name="submitted" value="1" /></td></tr>
</table>
</form>
</div>
</body>
</html>