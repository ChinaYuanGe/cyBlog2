<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(CheckIfLogin() == false) die(http_response_code(403));

$artid = (int)$_POST['id'];

if($db->ExecSQL("DELETE FROM arts WHERE id=$artid") > 0){
    DeleteArtDataAndDir($artid);
    echo GetRespJson_OK();
}
else echo GetRespJson_Fail();

?>