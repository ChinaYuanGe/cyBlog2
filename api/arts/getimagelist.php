<?php
require($_SERVER['DOCUMENT_ROOT'].'/api/requires.php');
if(CheckIfLogin() == false) die(http_response_code(403));

$targetID = (int)$_POST['id'];

if($db->QuerySQL("SELECT COUNT(*) FROM arts WHERE id=$targetID")[0][0] < 1) {
    die(GetRespJson_Fail(array('msg'=>'无法找到文章')));
}

$imgDataPath = $_SERVER['DOCUMENT_ROOT']."/arts/$targetID/images.json";
$dataContent = file_get_contents($imgDataPath,true);
if($dataContent === false){
    die(GetRespJson_Fail(array('msg'=>'无法读取相应文件')));
}
$imgData = json_decode($dataContent,true);

echo GetRespJson_OK(array('imgs'=>$imgData['used_img']));

?>