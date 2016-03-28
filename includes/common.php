<?php
// set time zone to use date/time functions without warnings
date_default_timezone_set('Asia/Shanghai');

/*
下面代码块的作用是:使用get_magic_quotes_gpc()判断php.ini配置是否自动为输入流添加反斜线来转义单引号,双引号,及反斜线.如果是,则过滤掉自动添加的反斜线
*/
if (get_magic_quotes_gpc())
{
    function _stripslashes_rcurs($variable, $top = true)
    {
        $clean_data = array();
        foreach ($variable as $key => $value)
        {
            $key = ($top) ? $key : stripslashes($key);
            $clean_data[$key] = (is_array($value)) ?
                stripslashes_rcurs($value, false) : stripslashes($value);
        }
        return $clean_data;
    }
	
    $_GET = _stripslashes_rcurs($_GET);
    $_POST = _stripslashes_rcurs($_POST);
    // $_REQUEST = _stripslashes_rcurs($_REQUEST);
    // $_COOKIE = _stripslashes_rcurs($_COOKIE);
}
?>
