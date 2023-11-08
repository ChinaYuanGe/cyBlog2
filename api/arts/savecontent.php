<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(CheckIfLogin() == false) die(http_response_code(403));

$db = GetDatabase();

$artid = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content']; //经过 encodeURIComponent 和 base64 编码
$group = $_POST['group'];
$tags = $_POST['tags'];

if(is_null($artid)) die(GetRespJson_Fail(array('msg'=>'请指定文章ID')));
if(is_null($content)) die(GetRespJson_Fail(array('msg'=>'请输入正文')));
if(is_null($group)) die(GetRespJson_Fail(array('msg'=>"请输入分组")));
if(is_null($tags)) die(GetRespJson_Fail(array('msg'=>"请输入标签")));
$artid = (int)$artid;

if($db->QuerySQL("SELECT COUNT(*) FROM arts WHERE id=$artid")[0][0] < 1){
    die(GetRespJson_Fail(array('msg'=>'无法找到对应文章')));
}

if($db->QuerySQL("SELECT `status` FROM arts WHERE id=$artid")[0][0] == 3){
    die(GetRespJson_Fail(array('msg'=>"无法修改最终版文章")));
}

# 文章正文
$currentArtRoot = $_SERVER['DOCUMENT_ROOT'].'/arts/'.$artid;
if(is_dir($currentArtRoot) === false){
    mkdir($currentArtRoot);
    file_put_contents($currentArtRoot.'/images.json',json_encode(array('title_img'=>"",'used_img'=>array())));
    file_put_contents($currentArtRoot.'/content.html',"");
}
if(file_put_contents($currentArtRoot.'/content.html',urldecode(base64_decode($content))) === false){
    die(GetRespJson_Fail(array('msg'=>"无法保存正文.")));
}
#处理一下tag
if(strlen($tags) < 1) $tags = null;

#剩下的数据写入数据库
$group = (int)$group;
if($db->ExecSQL("UPDATE arts SET title={$db->quote($title)}, `group`=$group, tags=".(is_null($tags) ? 'null' : $db->quote($tags)).", time_lastedit=NOW() WHERE id=$artid") < 1){
    die(GetRespJson_Fail(array('msg'=>"无法更新数据库")));
}

echo GetRespJson_OK();
?>