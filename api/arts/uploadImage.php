<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(CheckIfLogin() == false) die(http_response_code(403));

$artid = $_POST['id'];
$type = (int)$_POST['type'];
$file = $_FILES['file'];

if($db->QuerySQL("SELECT COUNT(*) FROM arts WHERE id=$artid")[0][0] < 1){
    die(GetRespJson_Fail(array('msg'=>'找不到文章')));
}

$imageRoot = $_SERVER['DOCUMENT_ROOT']."/img/arts";
if(is_dir($imageRoot) === false){
    mkdir($imageRoot);
}
$artRoot = $_SERVER['DOCUMENT_ROOT']."/arts/$artid";

$imgData = json_decode(file_get_contents($artRoot."/images.json"),true);

//创建对应的文件名

$fileNameExplode = explode('.',$file['name']);
$fileType = $fileNameExplode[count($fileNameExplode) - 1];

do{
    $imgTargetName = GetRandDoubleMD5().'.'.$fileType;
}while(is_file($imageRoot."/$imgTargetName"));

$imgTargetPath = $imageRoot."/$imgTargetName";
$imgTargetUrl = "/img/arts/$imgTargetName";

if($type == 1){ //封面图片
    if(!unlink($imageRoot."/".$imgData['title_img'])){
        error_log("Unable to delete title image:Refer Art id=".$artid.";picSource=".$imgData['title_img']);
    }
    if(!move_uploaded_file($file['tmp_name'],$imgTargetPath)){
        die(GetRespJson_Fail(array('msg'=>'无法创建文件')));
    }
    $imgData['title_img'] = $imgTargetName;
}
else{ //普通的图片
    if(!move_uploaded_file($file['tmp_name'],$imgTargetPath)){
        die(GetRespJson_Fail(array('msg'=>'无法创建文件')));
    }
    array_push($imgData['used_img'],$imgTargetName);
}

file_put_contents($artRoot.'/images.json',json_encode($imgData));
echo GetRespJson_OK(array('src'=>$imgTargetUrl));
?>