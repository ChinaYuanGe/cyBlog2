<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/master/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/configer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/router.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/etc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/jsonResp.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/auth.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/arts.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/comments.php");

$db = GetDatabase();
?>