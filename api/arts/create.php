<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(!CheckIfLogin()) die(http_response_code(403));

$title = $_POST['title'];
if(is_null($title) || strlen($title) < 1){
    $title = "未命名文章";
}

$db->ExecSQL("INSERT INTO arts(title,time_pubish,time_lastedit) VALUES({$db->quote($title)},NOW(),NOW())");
$currentID = $db->QuerySQL("SELECT LAST_INSERT_ID()",PDO::FETCH_BOTH)[0][0];

$currentArtRoot = $_SERVER['DOCUMENT_ROOT']."/arts/$currentID";

$creationStatus = CreateEmptyArtFile($currentID);

if($creationStatus){
    echo GetRespJson_OK(array('id'=>$currentID));
}
else{
    $db->ExecSQL("DELETE FROM arts WHERE id=$currentID");
    rmdirf($currentArtRoot);
    echo GetRespJson_Fail(array('msg'=>"无法创建相关文件"));
}
?>