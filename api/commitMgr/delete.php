<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(!CheckIfLogin()) die(http_response_code(403));
$targetID = (int)$_POST['id'];

if(DeleteComment($targetID)){
    echo GetRespJson_OK();
}
else echo GetRespJson_Fail();
?>