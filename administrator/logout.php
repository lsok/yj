<?php
session_start();
$_SESSION['admuser'] = '';
session_unset();
session_destroy();
// top.location ����������ܷ��ص�¼ҳ
echo '<script>top.location="login.php?logout=yesout";</script>';
?>
