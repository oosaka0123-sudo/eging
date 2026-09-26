<?php
require dirname(__DIR__, 2) . '/app/bootstrap.php';
$_SESSION = [];
session_destroy();
header('Location: /admin/login.php');
exit;
