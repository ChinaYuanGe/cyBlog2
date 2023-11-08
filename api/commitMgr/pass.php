<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(!CheckIfLogin()) die(http_response_code(403));

$targetID = (int)$_POST['id'];
$PickAsWhite = (int)$_POST['white'];

if(SetCommentVisible($targetID,1)){
    AddArtCommitCounter($targetID);
    if($PickAsWhite > 0){
        if(SetEmailWhiteList(GetCommentEmailHash($targetID)) === false){
            GetRespJson_FailMsg('无法设置白名单, 评论已放行.');
        }
        else echo GetRespJson_OK();
    }
    else echo GetRespJson_OK();
}
else echo GetRespJson_Fail();
?>