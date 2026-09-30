<?php
require dirname(__DIR__) . '/_runtime.php';
eging_require_bootstrap();
$_SESSION = [];
session_destroy();
header('Location: /admin/login.php');
exit;
