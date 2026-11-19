<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: menus.php');
exit;
