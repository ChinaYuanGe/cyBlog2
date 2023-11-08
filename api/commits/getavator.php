<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
header('content-type: image/png');
header('Cache-Control: max-age=86400');

$cacheRoot = $_SERVER['DOCUMENT_ROOT'].'/img/cache';
$hash = $_GET['hash'];

$cacheTimeout = 86400;

$imgCachePath = $cacheRoot.'/'.$hash.'.png';

$opts = array(
    'http'=>array(
        'method'=>"GET",
        'timeout'=> 2
    )
);
$defaultImagePath = $_SERVER['DOCUMENT_ROOT'].'/img/element/unknown.png';

if(count(scandir($cacheRoot)) > 100){//按数量清除cache
    rmdirf($cacheRoot);
}

if($hash != null){//传入空的HASH只会返回空的image
    $fileModifyTime = filemtime($imgCachePath);
    if(!file_exists($imgCachePath) || $fileModifyTime > ($fileModifyTime + $cacheTimeout)){
        $imageContent = file_get_contents('http://cn.gravatar.com/avatar/'.$hash.'?s=128&d=retro',false,stream_context_create($opts));
        if($imageContent !== false) file_put_contents($imgCachePath,$imageContent);
    }
    else if(!file_exists($imgCachePath)){
        $imageContent = file_get_contents($imgCachePath);
    }
    else{
        $imageContent = file_get_contents($imgCachePath);
    }
}
else{
    $imageContent = file_get_contents($defaultImagePath);
}
if($imageContent === false){//如果请求失败则返回默认图片
    $imageContent = file_get_contents($defaultImagePath);
}
echo $imageContent;
?>