<?php
include '401.php';
include 'includes/Constant.php';
include '../includes/common.php';
include '../ss-config.php';
include '../includes/ss-db.php';

$fid = $_GET['fid'];
$tabname = $_GET['tabname'];

	$sql= sprintf('SELECT * FROM %s' . $tabname . ' WHERE FAT_ID = ' . $fid, DB_TBL_PREFIX);//检索子类别
	mysql_query("set names 'utf8'");
	$result = mysql_query($sql, $GLOBALS['DB']) or die(mysql_error($GLOBALS['DB']));
	
	if (mysql_num_rows($result) == 0)
	{
	die;
	}
	
	echo "<input type='hidden' value='$fid' name='des'>";//提交表单时要获取的select的name中的变量部分 以帮助标识要接收哪一个select
	?>
	<!-- JS函数queryCity()的最后一个参数原来的值为'加载中',在加载下级选单时如服务器延迟就会出现"加载中"提示,但如果某个类别已无下级分类,此"加载中"提示仍会出现,故删除. -->
	<select id="ss<? echo $fid; ?>" name="ss<? echo $fid; ?>" onchange="queryCity('get_cates_select.php?tabname=<?php echo $tabname; ?>&fid='+this.options[this.selectedIndex].value+'&n='+Math.random(),<?php echo $fid;?>,'网络传输错误','')">
	<option value='-1' selected>请选择分类</option>
	<?php
	while ($row = mysql_fetch_row($result)){
	//循环输出父id为$fid的子分类
	echo "<option value='$row[0]'>$row[2]</option>";
	}
	?>
	</select>
	<?php
	echo "<span id='$fid'></span>";//生成显示下级分类的容器
?>