<?php
// set time zone to use date/time functions without warnings
date_default_timezone_set('Asia/Shanghai');

/*
���������������:ʹ��get_magic_quotes_gpc()�ж�php.ini�����Ƿ��Զ�Ϊ��������ӷ�б����ת�嵥����,˫����,����б��.�����,����˵��Զ���ӵķ�б��
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
