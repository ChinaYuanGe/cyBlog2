<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(CheckIfLogin() == false) die(http_response_code(403));

$artid = (int)$_POST['id'];

if($db->QuerySQL("SELECT COUNT(*) FROM arts WHERE id=$artid AND `status`<3")[0][0] < 1){
    die(GetRespJson_Fail(array('msg'=>'无法找到文章')));
}

if($db->ExecSQL("UPDATE arts SET `status`=3,time_pubish=NOW(),time_lastedit=NOW() WHERE id=$artid") < 1){
    die(GetRespJson_Fail(array('msg'=>'数据库更新失败')));
}

echo GetRespJson_OK();
?>