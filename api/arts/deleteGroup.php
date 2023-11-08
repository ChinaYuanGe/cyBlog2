<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(!CheckIfLogin()) die(http_response_code(403));

$id = (int)$_POST['id'];

if($db->ExecSQL("DELETE FROM groups WHERE id=$id") < 1){
    echo GetRespJson_Fail();
}
else{
    echo GetRespJson_OK();
}
?>