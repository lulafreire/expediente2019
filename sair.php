<?php 
session_start();
//session_unregister('codUnidade');
unset ($_SESSION['codUnidade']);
session_destroy();
header("location:index.php");
?>
