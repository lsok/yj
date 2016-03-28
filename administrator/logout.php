<?php
session_start();
$_SESSION['admuser'] = '';
session_unset();
session_destroy();
// top.location ¿ÉÌø³ö¸¸¿ò¼Ü·µ»ØµÇÂ¼Ò³
echo '<script>top.location="login.php?logout=yesout";</script>';
?>
