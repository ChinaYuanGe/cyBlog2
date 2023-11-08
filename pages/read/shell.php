<?php
function GetPageTitle(){
    global $routerInfo;
    $artID = $routerInfo[2];

    $artData = GetArtData($artID);
    if($artData === false){
        return "未找到文章";
    }

    return $artData['title'];
}
function GetSeoDes(){
    global $routerInfo;
    $artID = $routerInfo[2];
    $artData = GetArtData($artID);
    return $artData['prev'];
}
function GetKeyword(){
    global $routerInfo;
    $artID = $routerInfo[2];
    $artData = GetArtData($artID);
    $keywords = $artData['group'].",";
    foreach($artData['tags'] as $tag){
        $keywords = $keywords.$tag.',';
    }
    $keywords = substr($keywords,0,strlen($keywords)-1);
    return $keywords;
}
?>