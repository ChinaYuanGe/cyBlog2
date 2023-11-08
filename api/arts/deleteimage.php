<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(CheckIfLogin() == false) die(http_response_code(403));

$artid = (int)$_POST['id'];
$targetPic = $_POST['pic'];
$picRoot = $_SERVER['DOCUMENT_ROOT']."/img/arts";

if($db->QuerySQL("SELECT COUNT(*) FROM arts WHERE id=$artid")[0][0] < 1){
    die(GetRespJson_Fail(array('msg'=>"无法找到文章")));
}

$artImageDataPath = $_SERVER['DOCUMENT_ROOT']."/arts/$artid/images.json";

$imgDataContent = file_get_contents($artImageDataPath);
if($imgDataContent === false){
    die(GetRespJson_Fail(array('msg'=>"无法加载相关文件")));
}
$imgData = json_decode($imgDataContent,true);

$imgData['used_img'] = array_values(array_diff($imgData['used_img'],array($targetPic)));

if(file_put_contents($artImageDataPath,json_encode($imgData)) === false){
    die(GetRespJson_Fail(array('msg'=>"无法保存相关文件")));
}

echo GetRespJson_OK();
?>