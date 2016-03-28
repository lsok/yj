<?
include '401.php';
include '../ss-config.php';
include("backup.class.php");

global $mysqlhost, $mysqluser, $mysqlpwd, $mysqldb;
$mysqlhost= DB_HOST;              //host name
$mysqluser= DB_USER;              //login name
$mysqlpwd= DB_PASSWORD;           //password
$mysqldb= DB_NAME;                //name of database

$d=new db($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);
if(!isset($_POST['act'])){
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
<form name="form1" method="post" action="backup.php">
  <table width="99%" border="0" class="formtable">
    <tr><td colspan="2" style="font-size:14px;">第一步:选择备份方式</td></tr>
    <tr><td><input type="radio" name="bfzl" value="quanbubiao" checked="checked" class="radio" id="bfqb"><label for="bfqb"> 备份全部数据(推荐)</label></td><td>备份全部数据到backup目录下的一个sql文件</td></tr>
    <tr><td><input type="radio" name="bfzl" value="danbiao" class="radio" id="bfdb"> <label for="bfdb">备份单张数据表</label>
        <select name="tablename"><option value="">请选择</option>
          <?
		$d->query("show table status from $mysqldb");
		while($d->nextrecord()){
		echo "<option value='".$d->f('Name')."'>".$d->f('Name')."</option>";}
		?>
        </select></td><td>备份选中数据表中的数据到backup目录下的一个sql文件</td></tr>
    <!--<tr><td colspan="2">使用分卷备份</td></tr>
    <tr><td colspan="2"><input type="checkbox" name="fenjuan" value="yes">
        分卷备份 <input name="filesize" type="text" size="10">K</td></tr>-->
    <tr><td colspan="2" style="font-size:14px;">第二步:选择备份文件的保存位置</td></tr>
    <tr><td colspan="2"><input type="radio" name="weizhi" value="server" checked="checked" class="radio" id="server"> <label for="server">备份到服务器</label></td></tr><tr class="cells"><td colspan='2'> <input type="radio" name="weizhi" value="localpc" class="radio" id="local">
        <label for="local">备份到我的计算机</td></tr>
    <tr><td colspan="2"><input type="submit" name="act" value="立即备份" id="submitbutton" class="submit"></td></tr>
  </table></form>
</div>
</body>
</html>
<?/*-----------界面结束---------------*/}/*---------------------------------*/
/*----*/else{/*-----------主程序--------------------------------------------*/
if($_POST['weizhi']=="localpc" && isset($_POST['fenjuan']) && $_POST['fenjuan']=='yes')
	{$msgs[]="只有选择备份到服务器,才能使用分卷备份功能";
show_msg($msgs); pageend();}
if(isset($_POST['fenjuan']) && $_POST['fenjuan']=="yes" && !$_POST['filesize'])
	{$msgs[]="您选择了分卷备份功能,但未填写分卷文件大小";
show_msg($msgs); pageend();}
if($_POST['weizhi']=="server"&&!writeable("./backup"))
	{$msgs[]="备份文件存放目录'./backup'不可写,请修改目录属性";
show_msg($msgs); pageend();}

/*---------备份全部表-------------*/if($_POST['bfzl']=="quanbubiao"){/*----*/
/*----不分卷*/if(!isset($_POST['fenjuan'])){/*--------------------------------*/
if(!$tables=$d->query("show table status from $mysqldb"))
	{$msgs[]="读数据库结构错误"; show_msg($msgs); pageend();}
$sql="";
while($d->nextrecord($tables))
	{
	$table=$d->f("Name");
	$sql.=make_header($table);
	$d->query("select * from $table");
	$num_fields=$d->nf();
	while($d->nextrecord())
	{$sql.=make_record($table,$num_fields);}
	}
$filename=date("Ymd",time())."_all.sql";
if($_POST['weizhi']=="localpc") down_file($sql,$filename);
elseif($_POST['weizhi']=="server")
	{if(write_file($sql,$filename))
  $msgs[]="数据备份成功!";
//$msgs[]="全部数据表数据备份完成,生成备份文件'./backup/$filename'";
	else $msgs[]="数据备份失败!";
	show_msg($msgs);
	pageend();
	}
/*-----------------不分卷结束*/}/*-----------------------*/
/*-----------------分卷*/else{/*-------------------------*/
if(!$_POST['filesize'])
	{$msgs[]="请填写备份文件分卷大小"; show_msg($msgs);pageend();}
if(!$tables=$d->query("show table status from $mysqldb"))
	{$msgs[]="读数据库结构错误"; show_msg($msgs); pageend();}
$sql=""; $p=1;
$filename=date("Ymd",time())."_all";
while($d->nextrecord($tables))
{
	$table=$d->f("Name");
	$sql.=make_header($table);
	$d->query("select * from $table");
	$num_fields=$d->nf();
	while($d->nextrecord())
	{$sql.=make_record($table,$num_fields);
	if(strlen($sql)>=$_POST['filesize']*1000){
			$filename.=("_v".$p.".sql");
			if(write_file($sql,$filename))
			$msgs[]="全部数据表-卷-".$p."数据备份完成,生成备份文件'./backup/$filename'";
			else $msgs[]="备份表-".$_POST['tablename']."-失败";
			$p++;
			$filename=date("Ymd",time())."_all";
			$sql="";}
	}
}
if($sql!=""){$filename.=("_v".$p.".sql");		
if(write_file($sql,$filename))
$msgs[]="全部数据表-卷-".$p."-数据备份完成,生成备份文件'./backup/$filename'";}
show_msg($msgs);
/*---------------------分卷结束*/}/*--------------------------------------*/
/*--------备份全部表结束*/}/*---------------------------------------------*/

/*--------备份单表------*/elseif($_POST['bfzl']=="danbiao"){/*------------*/
if(!$_POST['tablename'])
	{$msgs[]="请选择要备份的数据表!"; show_msg($msgs); pageend();}
/*--------不分卷*/if(!isset($_POST['fenjuan'])){/*-------------------------------*/
$sql=make_header($_POST['tablename']);
$d->query("select * from ".$_POST['tablename']);
$num_fields=$d->nf();
while($d->nextrecord())
	{$sql.=make_record($_POST['tablename'],$num_fields);}
$filename=date("Ymd",time())."_".$_POST['tablename'].".sql";
if($_POST['weizhi']=="localpc") down_file($sql,$filename);
elseif($_POST['weizhi']=="server")
	{if(write_file($sql,$filename))

	$msgs[]="数据表".$_POST['tablename']."备份成功!";
	
//$msgs[]="表-".$_POST['tablename']."-数据备份完成,生成备份文件'./backup/$filename'";
	else $msgs[]="备份表-".$_POST['tablename']."-失败";
	show_msg($msgs);
	pageend();
	}
/*----------------不分卷结束*/}/*------------------------------------*/
/*----------------分卷*/else{/*--------------------------------------*/
if(!$_POST['filesize'])
	{$msgs[]="请填写备份文件分卷大小"; show_msg($msgs);pageend();}
$sql=make_header($_POST['tablename']); $p=1; 
	$filename=date("Ymd",time())."_".$_POST['tablename'];
	$d->query("select * from ".$_POST['tablename']);
	$num_fields=$d->nf();
	while ($d->nextrecord()) 
	{	
		$sql.=make_record($_POST['tablename'],$num_fields);
	   if(strlen($sql)>=$_POST['filesize']*1000){
			$filename.=("_v".$p.".sql");
			if(write_file($sql,$filename))
			$msgs[]="表-".$_POST['tablename']."-卷-".$p."-数据备份完成,生成备份文件'./backup/$filename'";
			else $msgs[]="备份表-".$_POST['tablename']."-失败";
			$p++;
			$filename=date("Ymd",time())."_".$_POST['tablename'];
			$sql="";}
	}
if($sql!=""){$filename.=("_v".$p.".sql");		
if(write_file($sql,$filename))
$msgs[]="表-".$_POST['tablename']."-卷-".$p."-数据备份完成,生成备份文件'./backup/$filename'";}
show_msg($msgs);
/*----------分卷结束*/}/*--------------------------------------------------*/
/*----------备份单表结束*/}/*----------------------------------------------*/

/*---*/}/*----------主程序结束---------------------------------------------*/

function write_file($sql,$filename)
{
$re=true;
if(!@$fp=fopen("./backup/".$filename,"w+")) {$re=false; echo "failed to open target file";}
if(!@fwrite($fp,$sql)) {$re=false; echo "failed to write file";}
if(!@fclose($fp)) {$re=false; echo "failed to close target file";}
return $re;
}

function down_file($sql,$filename)
{
	ob_end_clean();
	header("Content-Encoding: none");
	header("Content-Type: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
			
	header("Content-Disposition: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ')."filename=".$filename);
			
	header("Content-Length: ".strlen($sql));
	header("Pragma: no-cache");
			
	header("Expires: 0");
	echo $sql;
	$e=ob_get_contents();
}

function writeable($dir)
{
	
	if(!is_dir($dir)) {
	@mkdir($dir, 0777);
	}
	
	if(is_dir($dir)) 
	{
	
	if($fp = @fopen("$dir/test.test", 'w'))
		{
@fclose($fp);
	@unlink("$dir/test.test");
	$writeable = 1;
} 
	else {
$writeable = 0;
	}
	
}
	
	return $writeable;

}

function make_header($table)
{global $d;
$sql="DROP TABLE IF EXISTS ".$table."\n";
$d->query("show create table ".$table);
$d->nextrecord();
$tmp=preg_replace("/\n/","",$d->f("Create Table"));
$sql.=$tmp."\n";
return $sql;
}

function make_record($table,$num_fields)
{global $d;
$comma="";
$sql = "INSERT INTO ".$table." VALUES(";
for($i = 0; $i < $num_fields; $i++) 
{$sql .= ($comma."'".mysql_escape_string($d->record[$i])."'"); $comma = ",";}
$sql .= ")\n";
return $sql;
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
echo "<script>alert('". $wartext ."');location.href='backup.php';</script>";	
}

function pageend()
{
exit();
}
?>
