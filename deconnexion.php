<?php
require 'config.php';
$_SESSION = [];
session_destroy();
header('Location: menus.php');
exit;
