<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
//生产环境应该取消注释
sleep(2);
$queueMax = ReadConfigProfile('limits')['CommentMaxQueue'];
$defaultName = ReadConfigProfile('commits')['anonymousName'];

$ImageCacheRoot = $_SERVER['DOCUMENT_ROOT'].'/img/cache';

$nameLenLimit = 20;

$adminAccess = CheckIfLogin();

$artid = (int)$_POST['a'];
$name = $_POST['n'];
$email = strtolower($_POST['e']);
$emailhash = md5($email); //正常应该是md5
$content = $_POST['c'];

$repID = (int)$_POST['r'];

$cachedImageRoot = $ImageCacheRoot."/$emailhash.png";

//判断是否超过队列
if((int)$db->QuerySQL("SELECT COUNT(*) FROM comment WHERE visible=0")[0][0] >= $queueMax){
    die(GetRespJson_FailMsg('当前队列已爆满,请稍后再评论.'));
}

//空白-掐断
if($artid < 1){
    die(GetRespJson_FailMsg('无效的文章ID'));
}

if(strlen($email) > 255){
    die(GetRespJson_FailMsg('电子邮件过长'));
}
else if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
    die(GetRespJson_FailMsg('电子邮件格式不正确'));
}

if(strlen($content) < 1){
    die(GetRespJson_FailMsg('缺少正文'));
}

//空白-补齐
if(is_null($name) || strlen($name) < 1){
    $name = $defaultName;
}

//超过-掐断
if(mb_strlen($name) > 24){
    die(GetRespJson_FailMsg('名称不应该超过 24 个字符'));
}
//过滤名称
$name = str_replace('<',"&lt;",$name);
$name = str_replace('>',"&gt;",$name);

if(strlen($content) > 512){
    die(GetRespJson_FailMsg('正文太多了,请删减一些(不超过 200 个字符)'));
}

//正文过滤
$urldecodedContent = rawurldecode(base64_decode($content));
$recdContent = $urldecodedContent;
$urldecodedContent = str_replace('<',"&lt;",$urldecodedContent);
$urldecodedContent = str_replace('>',"&gt;",$urldecodedContent);

$finalContent = base64_encode(rawurlencode($urldecodedContent));

//不是管理员，检查黑名单Email
if(!$adminAccess){
    $blackList = explode(',',ReadConfigProfile('commits')['blacklist']);
    foreach($blackList as $blackmail){
        if(strtolower($blackmail) == $email) die(GetRespJson_FailMsg('该电子邮件无法使用'));
    }
}

if($adminAccess || CheckEmailIfWhiteList($email)){
    if(CreateComment($name,$email,$finalContent,$repID,$artid)){
        AddArtCommitCounter($artid);
        echo GetRespJson_OK(array('checking' => false));
        unlink($cachedImageRoot);
    }
    else echo GetRespJson_Fail();
}
else{
    if(CreateHiddenComment($name,$email,$finalContent,$artid,$repID)){
        echo GetRespJson_OK(array('checking'=>true));
        unlink($cachedImageRoot);
        register_shutdown_function(function($name,$outContent){
            require($_SERVER['DOCUMENT_ROOT']."/php/utils/ios_nofpush.php");
            if(ReadConfigProfile("ios_nofpush")["enable"] == true){
                PushIOSNof($name." 的评论",$outContent);
            }
        },$name,$recdContent);
    }
    else echo GetRespJson_Fail();
}
?>