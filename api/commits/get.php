<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
$artID = (int)$_GET['a'];
$page = (int)$_GET['p'];
$page = $page <= 0 ? 0 :$page;
//如果fetchID > 0，则忽略上面两个参数，直接输出对应ID的评论
$fetchID = (int)$_GET['id'];

if($artID <= 0 && $fetchID <= 0){
    die(GetRespJson_Fail(array('msg'=>'缺少必要参数')));
}

$outputLimit = (int)ReadConfigProfile("limits")['ArtPrintLimit'];

$adminMode = false;

if($fetchID > 0){
    echo GetRespJson_OK(GetCommentByID($fetchID,$adminMode));
}
else{
    if(!ArtExists($artID)) die(GetRespJson_Fail(array('msg'=>'无此文章')));
    echo GetRespJson_OK(GetCommentByArt($artID,$page,$adminMode));
}

?>