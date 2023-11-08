<?php
//检查Art是否存在
function ArtExists(int $id){
    global $db;
    return (bool)$db->QuerySQL("SELECT COUNT(*) FROM arts WHERE id=$id AND status>1")[0][0];
}
//返回特定的 ArtData, 返回结构看GetAllArtData
function GetArtData(int $id){
    $data = GetAllArtData(8,0,3,$id,-1); 
    if(count($data) < 1){
        return false;
    }
    return $data[0];
}

/*
获取归档后的文章数据，返回结构如下:
~~~
array(
	"年份"=>array(
		"月份"=>array(
			"文章ID"=>array(
				"id"=>文章ID,
				"group"=>"分组名",
				"title"=>"文章标题",
				"daypub"=>"文章发布日期-天"
			)
		)
	)
)
~~~*/
function GetArchivedArtData(){
    global $db;
    global $docRoot;
    
    $artData = $db->QuerySQL("SELECT id,(SELECT `name` FROM groups WHERE groups.id=arts.`group`) as 'group',title,time_pubish FROM arts WHERE `status`>=2 ORDER BY time_pubish DESC");

    $retData = array();
    foreach($artData as $art){
        $dateStr = mb_split(' ',$art["time_pubish"])[0];
        $year = mb_split('-',$dateStr)[0];
        $month = mb_split('-',$dateStr)[1];
        $day = mb_split('-',$dateStr)[2];
        $artID = $art["id"];
        if(array_key_exists($year,$retData) === false){
            $retData[$year] = array();
        }
        if(array_key_exists($month,$retData[$year]) === false){
            $retData[$year][$month] = array();
        }

        $retData[$year][$month][$artID] = array(
            "id"=>$artID,
            "group"=>$art["group"] == null?"未分组":$art["group"],
            "title"=>$art["title"],
            "daypub"=>$day
        );
    }
    return $retData;
}
function GetAllArtData(int $limit,int $page = 0,int $statusCondition = 0,int $idRefer = null,int $statusControl = 0){
    global $db;
    global $docRoot;
    $pageLimit = $limit;

    $queryStr = "SELECT id,title,(SELECT `name` FROM groups WHERE groups.id=arts.`group`) as `group`,`group` as `groupid`,tags,time_pubish,time_lastedit,`status` FROM arts WHERE ";
    
    if($statusControl >= 1){
        $queryStr = $queryStr."status>=$statusCondition ";
    }
    else if($statusControl <= 1){
        $queryStr = $queryStr."status<=$statusCondition ";
    }
    else{
        $queryStr = $queryStr."status=$statusCondition ";
    }
    
    /*if($statusCondition >= 0 && $statusControl == 0){
        $queryStr = $queryStr."status=$statusCondition ";
    }
    else if($statusCondition >= 0 && $statusControl >=1){
        $queryStr = $queryStr."status>$statusCondition ";
    }
    else $queryStr = $queryStr."status!=$statusCondition ";*/
    
    if(!is_null($idRefer)){
        $queryStr = $queryStr."AND id=$idRefer ";
    }
    $queryStr = $queryStr."ORDER BY time_pubish DESC LIMIT ".($page*$pageLimit).",$pageLimit";

    $artData = $db->QuerySQL($queryStr);
    $pageCount = (count($artData)/$pageLimit) + 1;

    $artArray = array();

    foreach($artData as $art){
        $currentArt = array();
        $currentArt['id'] = $art['id'];
        $currentArt['title'] = $art['title'];
        $currentArt['group'] = ((is_null($art['groupid'])  || $art['groupid'] < 1) ? "未分组" : $art['group']);
        $currentArt['groupid'] = $art['groupid'];
        $currentArt['tags'] = explode(',',$art['tags']);
        $currentArt['time_pubish'] = $art['time_pubish'];
        $currentArt['time_lastedit'] = $art['time_lastedit'];
        $currentArt['status'] = $art['status'];

        $photoInfo = json_decode(file_get_contents($docRoot."arts/{$art['id']}/images.json"),true);
        if( file_exists($_SERVER['DOCUMENT_ROOT']."/img/arts/{$photoInfo['title_img']}") &&
            is_file($_SERVER['DOCUMENT_ROOT']."/img/arts/{$photoInfo['title_img']}" ) ){
            $currentArt['title_img'] = "/img/arts/{$photoInfo['title_img']}";
        }
        else{
            $currentArt['title_img'] = "/img/arts/default_title.png";
        }

        //$clearContnet = preg_replace("/<([a-z]+)[^>]*>/i","",file_get_contents($docRoot."arts/{$art['id']}/content.html"));
        $clearContnet = strip_tags(file_get_contents($docRoot."arts/{$art['id']}/content.html"));
        $currentArt['prev'] = mb_substr($clearContnet,0,35);
        array_push($artArray,$currentArt);
    }
    return $artArray;
}
function GetAllArtPubish(int $limit,int $page = 0){
    return GetAllArtData($limit,$page,2);

}
function GetAllArtDraft(int $limit,int $page = 0){
    return GetAllArtData($limit,$page,0);
}

function CreateEmptyArtFile($id){
    $creationStatus = true;
    $artRoot = $_SERVER['DOCUMENT_ROOT'].'/arts';
    $currentArtRoot = $artRoot."/$id";
    if(is_dir($artRoot) === false){
        if(mkdir($artRoot) === false){
            $creationStatus = false;
        }
    }
    if(mkdir($currentArtRoot) === false){
        $creationStatus = false;
    }
    if(file_put_contents($currentArtRoot.'/images.json',json_encode(array("title_img"=>null,"used_img"=>array()))) === false){
        $creationStatus = false;
    }
    if(file_put_contents($currentArtRoot.'/content.html',"") === false){
        $creationStatus = false;
    }
    return $creationStatus;
}

function GetGroupData(){
    global $db;
    return $db->QuerySQL("SELECT * FROM groups");
}
// 添加组，如果成功则返回组的id，否则返回false
function AddGroup(string $name){
    global $db;
    if($db->ExecSQL("INSERT INTO groups(`name`) VALUES({$db->quote($name)})") > 0){
        return $db->QuerySQL("SELECT LAST_INSERT_ID()")[0][0];
    }
    else return false;
}

function DeleteArtDataAndDir($id){
    $ArtRoot = $_SERVER['DOCUMENT_ROOT']."/arts/$id";
    if(!is_dir($ArtRoot)){
        return false;
    }
    RemoveImagesByArtID($id);
    rmdirf($ArtRoot);
}

function RemoveImagesByArtID($artID){
    $imgRoot = $_SERVER['DOCUMENT_ROOT']."/img/arts";
    $artPath = $_SERVER['DOCUMENT_ROOT']."/arts/$artID";
    $imgDataPath = $artPath."/images.json";
    if(!is_dir($artPath)){
        return false;
    }
    if(!is_file($imgDataPath)){
        return false;
    }
    $artDataContent = file_get_contents($imgDataPath);
    if($artDataContent === false){
        return false;
    }
    $imgData = json_decode($artDataContent,true);
    if(is_null($imgData)){
        return false;
    }
    $titleImg = $imgRoot."/{$imgData['title_img']}";
    if(is_file($titleImg)){
       if(!unlink($titleImg)) error_log("Unable to delete file:".$titleImg);
    }
    foreach($imgData['used_img'] as $img){
        $targetImg = $imgRoot."/$img";
        if(is_file($targetImg)){
            if(!unlink($targetImg)) error_log("Unable to delete file:".$targetImg);
        }
    }
    return true;
}

function GetArtCount($status,$operactor = '='){
    global $db;
    return (int)$db->QuerySQL("SELECT COUNT(*) FROM arts WHERE status$operactor$status")[0][0];
}

function AddArtVisitCounter(int $id){
    global $db;
    return $db->ExecSQL("UPDATE arts SET arts.counter_visit=arts.counter_visit+1 WHERE id=$id;");
}
function AddArtCommitCounter(int $id){
    global $db;
    return $db->ExecSQL("UPDATE arts SET arts.counter_commits=arts.counter_commits+1 WHERE id=$id;");
}
function RecalcArtCommitCounter(){
    global $db;
    return $db->ExecSQL("UPDATE arts SET `counter_commits`=(SELECT COUNT(*) FROM `comment` WHERE artid=arts.id AND visible!=0)");
}
?>