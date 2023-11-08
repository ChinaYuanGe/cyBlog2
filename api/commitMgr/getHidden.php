<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(!CheckIfLogin()) die(http_response_code(403));
$page = (int)$_GET['p'];
$page = $page <= 0 ? 0 : $page;
echo GetRespJson_OK(GetAllHiddenComment($page));
?>