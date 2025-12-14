<?php
require_once __DIR__ . '/config/session.php';

$_SESSION = array();
session_destroy();

header('Location: ' . BASE_URL . '/login.php');
exit();
?>
