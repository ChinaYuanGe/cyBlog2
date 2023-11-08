<?php
require($_SERVER['DOCUMENT_ROOT'].'/api/requires.php');
$targetToken = $_GET['token'];
DeleteToken($targetToken);
echo GetREspJson_OK();
?>