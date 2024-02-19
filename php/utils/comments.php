<?php
function GetCommentCount($visible){
    global $db;
    return (int)$db->QuerySQL("SELECT COUNT(*) FROM comment WHERE visible=$visible")[0][0];
}
function GetCommentByArt(int $artID,int $page,bool $adminMode){
    global $db;
    $outputLimit = (int)ReadConfigProfile('limits')['ArtPrintLimit'];
    $pageCount = ceil((int)$db->QuerySQL("SELECT COUNT(*) FROM comment WHERE artid=$artID".($adminMode?'':' AND visible=1'))[0][0] / $outputLimit);
    $commentCount = (int)$db->QuerySQL("SELECT COUNT(*) FROM comment WHERE artid=$artID".($adminMode?'':' AND visible=1')."")[0][0];
    $data = $db->QuerySQL("SELECT `id`,`artid`,`name`,email,content,`time`,resp as respid,(SELECT `name` FROM comment WHERE id=respid) as repname,(SELECT `content` FROM comment WHERE id=respid) as `repsrc` FROM comment WHERE artid=$artID".($adminMode?'':' AND visible=1')." ORDER BY `time` DESC LIMIT ".($page*$outputLimit).",$outputLimit",PDO::FETCH_ASSOC);
    
    foreach($data as &$d){
        $d["email"] = md5($d["email"]);
    }
    
    return array('maxPage'=>$pageCount,'count'=>$commentCount,'commits'=>$data);
}
function GetCommentByID(int $id,bool $adminMode){
    global $db;
    $data = $db->QuerySQL("SELECT `id`,`artid`,`name`,email,content,`time`,resp as respid,(SELECT `name` FROM comment WHERE id=respid) as repname,(SELECT `content` FROM comment WHERE id=respid) as `repsrc` FROM comment WHERE id=$id".($adminMode?'':' AND visible=1'),PDO::FETCH_ASSOC);
    return array('maxPage'=>0,'count'=>count($data),'commits'=>$data);
}
function GetAllHiddenComment($page){
    global $db;
    $outputLimit = (int)ReadConfigProfile('limits')['ArtPrintLimit'];
    $pageCount = ceil((int)$db->QuerySQL("SELECT COUNT(*) FROM comment WHERE visible=0")[0][0] / $outputLimit);
    $commentCount = (int)$db->QuerySQL("SELECT COUNT(*) FROM comment WHERE visible=0")[0][0];
    $data = $db->QuerySQL("SELECT `id`,`artid`,`name`,email,content,`time`,resp as respid,(SELECT `name` FROM comment WHERE id=respid) as repname,(SELECT `content` FROM comment WHERE id=respid) as `repsrc` FROM comment WHERE visible=0 ORDER BY `time` ASC LIMIT ".($page * $outputLimit).",$outputLimit",PDO::FETCH_ASSOC);
    return array('maxPage'=>$pageCount,'count'=>$commentCount,'commits'=>$data);
}
function CreateHiddenComment($name,$emailhash,$content,$artid,$rep){
    return CreateComment($name,$emailhash,$content,$rep,$artid,0);
}
function CreateComment($name,$emailhash,$content,$rep,$artid,$visible = 1){
    global $db;
    return (bool)$db->ExecSQL("INSERT INTO comment(`name`,`email`,`content`,`artid`,`resp`,`visible`,`time`) VALUES({$db->quote($name)},{$db->quote($emailhash)},'$content',$artid,$rep,$visible,NOW())");
}
function SetCommentVisible(int $id,int $value){
    global $db;
    return (bool)$db->ExecSQL("UPDATE comment SET visible=$value WHERE id=$id");
}
function DeleteComment(int $id){
    global $db;
    return (bool)$db->ExecSQL("DELETE FROM comment WHERE id=$id");
}
function CheckEmailIfWhiteList($emailHash){
    global $db;
    return (bool)$db->QuerySQL("SELECT COUNT(*) FROM comment_whitelist WHERE emailhash={$db->quote($emailHash)}")[0][0];
}
function GetCommentEmailHash($id){
    global $db;
    $data = $db->QuerySQL("SELECT email FROM comment WHERE id=$id"); 
    if(count($data) < 1) return false;
    else return $data[0][0];
}
function SetEmailWhiteList($emailhash){
    global $db;
    if(!WhiteListExists($emailhash)){
        return (bool)$db->ExecSQL("INSERT INTO comment_whitelist(`emailhash`) VALUES('$emailhash')");
    }
    else return true;
}
function WhiteListExists($emailhash){
    global $db;
    return (int)$db->QuerySQL("SELECT COUNT(*) FROM comment_whitelist WHERE emailhash='$emailhash'")[0][0];
}
?>