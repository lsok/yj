<?
ini_set('max_execution_time', '600');//设置PHP程序执行超时时间 600为秒数 因恢复大量数据可能需时较长 故设置此函数
include '401.php';
include '../ss-config.php';
include("backup.class.php");

global $mysqlhost, $mysqluser, $mysqlpwd, $mysqldb;
$mysqlhost= DB_HOST;              //host name
$mysqluser= DB_USER;              //login name
$mysqlpwd= DB_PASSWORD;           //password
$mysqldb= DB_NAME;                //name of database

$d=new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);

if(!isset($_POST['act']) && !isset($_SESSION['data_file'])){
$msgs = array();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>数据备份与恢复</title>
<link rel="stylesheet" href="css/admin.css" type="text/css" />
<script type="text/javascript" src="js/formsubmit.js"></script>
<body>
<div id="wrap">
<h1>数据备份与恢复<a href="backup.php">数据备份</a><a href="restore.php">数据恢复</a></h1>
<p class="tip" style="margin-top:22px;color:#333;"><img src="images/tip.gif" />如需上传本地sql文件,请使用FTP软件上传至administrator/backup目录中.</p>
<form action="" method="post" enctype="multipart/form-data" name="restore.php">
<table width="99%" border="0" class="formtable" style="margin-top:10px;"><tr><td width="100%"><input type="hidden" name="restorefrom" value="server">
<select name="serverfile">
    <option value="">-请选择备份文件-</option>
<?
$handle=opendir('./backup');
while ($file = readdir($handle)) {
    if(preg_match("/^[0-9]{8,8}([0-9a-z_]+)(\.sql)$/i",$file)) echo "<option value='$file'>$file</option>";}
closedir($handle);
?>
  </select> </td></tr>
<!--<tr><td><input type="radio" name="restorefrom" value="localpc">       ???</td>
<td><input type="hidden" name="MAX_FILE_SIZE" value="1500000"><input type="file" name="myfile"></td></tr>-->
<tr><td><input type="submit" name="act" value="恢复数据" id="submitbutton" class="submit">
</td>  </tr></table></form>


<?/***************************界面结束*/}/*************************************/
/*****************************主程序*/if(isset($_POST['act']) && $_POST['act']=="恢复数据"){/**************/
/***************服务器恢复*/if($_POST['restorefrom']=="server"){/**************/
if(!$_POST['serverfile'])
	{$msgs[]="请选择要恢复的备份文件!";
	 show_msg($msgs); pageend();	}
if(!preg_match("/_v[0-9]+/",$_POST['serverfile']))
	{$filename="./backup/".$_POST['serverfile'];
	if(import($filename)) $msgs[]="数据恢复成功!";
	else $msgs[]="备份文件".$_POST['serverfile']."导入失败";
	show_msg($msgs); pageend();		
	}
else
	{
	$filename="./backup/".$_POST['serverfile'];
	if(import($filename)) $msgs[]="数据恢复成功!";
	else {$msgs[]="备份文件".$_POST['serverfile']."导入失败";show_msg($msgs);pageend();}
	$voltmp=explode("_v",$_POST['serverfile']);
	$volname=$voltmp[0];
	$volnum=explode(".sq",$voltmp[1]);
	$volnum=intval($volnum[0])+1;
	$tmpfile=$volname."_v".$volnum.".sql";
	if(file_exists("./backup/".$tmpfile))
		{
		$msgs[]="程序将在3秒钟后自动开始导入此分卷备份的下一部份:文件".$tmpfile.",请勿手动中止程序的运行，以免数据库结构受损";
		$_SESSION['data_file']=$tmpfile;
		show_msg($msgs);
		sleep(3);
		echo "<script language='javascript'>"; 
		echo "location='restore.php';"; 
		echo "</script>"; 
		}
	else
		{
		$msgs[]="此分卷备份全部导入成功";
		show_msg($msgs);
		}
	}
/**************服务器恢复结束*/}/********************************************/
/*****************本地恢复*/if($_POST['restorefrom']=="localpc"){/**************/
	switch ($_FILES['myfile']['error'])
	{
	case 1:
	case 2:
	$msgs[]="您上传的文件大于服务器限定值,上传未成功";
	break;
	case 3:
	$msgs[]="未能从本地完整上传备份文件";
	break;
	case 4:
	$msgs[]="从本地上传备份文件失败";
	break;
    case 0:
	break;
	}
	if($msgs){show_msg($msgs);pageend();}
$fname=date("Ymd",time())."_".sjs(5).".sql";
if (is_uploaded_file($_FILES['myfile']['tmp_name'])) {
    copy($_FILES['myfile']['tmp_name'], "./backup/".$fname);}

if (file_exists("./backup/".$fname)) 
	{
	$msgs[]="本地备份文件上传成功";
	if(import("./backup/".$fname)) {$msgs[]="本地备份文件成功导入数据库"; unlink("./backup/".$fname);}
	else $msgs[]="本地备份文件导入数据库失败";
	}
else ($msgs[]="从本地上传备份文件失败");
show_msg($msgs);
/****本地恢复结束*****/}/****************************************************/
/****************************主程序结束*/}/**********************************/
/*************************剩余分卷备份恢复**********************************/
if(!isset($_POST['act']) && isset($_SESSION['data_file']))
{
	$filename="./backup/".$_SESSION['data_file'];
	if(import($filename)) $msgs[]="备份文件".$_SESSION['data_file']."成功导入数据库";
	else {$msgs[]="备份文件".$_SESSION['data_file']."导入失败";show_msg($msgs);pageend();}
	$voltmp=explode("_v",$_SESSION['data_file']);
	$volname=$voltmp[0];
	$volnum=explode(".sq",$voltmp[1]);
	$volnum=intval($volnum[0])+1;
	$tmpfile=$volname."_v".$volnum.".sql";
	if(file_exists("./backup/".$tmpfile))
		{
		$msgs[]="程序将在3秒钟后自动开始导入此分卷备份的下一部份:文件".$tmpfile.",请勿手动中止程序的运行,以免数据库结构受损";
		$_SESSION['data_file']=$tmpfile;
		show_msg($msgs);
		sleep(3);
		echo "<script language='javascript'>"; 
		echo "location='restore.php';"; 
		echo "</script>"; 
		}
	else
		{
		$msgs[]="此分卷备份全部导入成功";
		unset($_SESSION['data_file']);
		show_msg($msgs);
		}
}
/**********************剩余分卷备份恢复结束*******************************/
function import($fname)
{global $d;
$sqls=file($fname);
foreach($sqls as $sql)
	{
	str_replace("\r","",$sql);
	str_replace("\n","",$sql);
	if(!$d->query(trim($sql))) return false;
	}
return true;
}

function show_msg($msgs)
{
$wartext = '';
foreach ($msgs as $w)
{
	$wartext .= $w;
}

//echo $wartext;

echo '<meta http-equiv="Content-Type" content="text/html; charset=utf8" />'; 
echo "<script>alert('". $wartext ."');location.href='restore.php';</script>";	
}

function pageend()
{
exit();
}
?>
