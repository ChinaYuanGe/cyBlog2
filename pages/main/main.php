<?php
$exCardRoot = $pageRoot.'exCards/';

#搜索参数
$page = (int)$_GET['p'];
if($page < 0) $page = 0;

$filter_searcher = $_GET['search'];
$filter_group = (int)$_GET['group'];

#构建文章文档
$pageLimit = ReadConfigProfile('limits')['ArtPrintLimit'];

$queryStr = "SELECT id,title,`group` as `groupid`,(SELECT `name` FROM groups WHERE groups.id=arts.`group`) as `group`,tags,time_pubish,time_lastedit,`status`,`counter_visit`,`counter_commits` FROM arts WHERE status>1";

if($filter_group>0) $queryStr = $queryStr." AND `group`=$filter_group";
$queryStr = $queryStr." AND (title LIKE '%$filter_searcher%' OR tags LIKE '%$filter_searcher%')";


$queryStr = $queryStr." ORDER BY time_pubish DESC LIMIT ".($page*$pageLimit).",$pageLimit";

$artData = $db->QuerySQL($queryStr);
$artCount= $db->QuerySQL("SELECT COUNT(*) FROM arts WHERE `status`>1 ".($filter_group > 0? " AND `group`=$filter_group":null)." AND (title LIKE '%$filter_searcher%' OR tags LIKE '%$filter_searcher%')")[0][0];
$pageCount = ($artCount/$pageLimit) - 1;

$artArray = array();

foreach($artData as $art){
    $currentArt = array();
    $currentArt['id'] = $art['id'];
    $currentArt['title'] = $art['title'];
    $currentArt['group'] = (is_null($art['group']) ? "未分组" : $art['group']);
    $currentArt['tags'] = explode(',',$art['tags']);
    $currentArt['time_pubish'] = $art['time_pubish'];
    $currentArt['time_lastedit'] = $art['time_lastedit'];
    $currentArt['status'] = $art['status'];
    $currentArt['counter_visit'] = $art['counter_visit'];
    $currentArt['counter_commits'] = $art['counter_commits'];

    $photoInfo = json_decode(file_get_contents($docRoot."arts/{$art['id']}/images.json"),true);

    if(is_null($photoInfo['title_img']) || strlen($photoInfo['title_img']) < 1){
        $currentArt['title_img'] = "img/arts/default_title.png";
    }
    else $currentArt['title_img'] = "/img/arts/{$photoInfo['title_img']}";

    $clearContnet = preg_replace("/<([a-z]+)[^>]*>/i","",file_get_contents($docRoot."arts/{$art['id']}/content.html"));
    $currentArt['prev'] = mb_substr($clearContnet,0,35);
    array_push($artArray,$currentArt);
}
function GetArtsHTMLDom(){
    global $isNight;
    global $artArray;
    $outputString = "";
    foreach($artArray as $art){
        $date = explode('-',explode(' ',$art['time_pubish'])[0]);
        $time = explode(':',explode(' ',$art['time_pubish'])[1]);
        $currentArtContent = 
        "<a style=\"text-decoration:none\" href=\"/read/{$art['id']}\">\n".
        "   <div class=\"card art_cardview".($art['status'] == 3? " art_rib":null)."\"".($art['status'] == 3? " data-text=\"最终版\"":null).">\n".
        "       <img src=\"{$art['title_img']}\" class=\"card-img-top\">\n";
        $currentArtContent = $currentArtContent.
        "       <div class=\"tagContainer\">\n";

        foreach($art['tags'] as $tag){
            $colorHash = '#'.GetColorByHex($tag);
            if(strlen($tag) > 0) $currentArtContent = $currentArtContent.
            "       <div class=\"art_tag\" style=\"background-color:$colorHash;color:$colorHash\"><span>$tag</span></div>\n";
        }

        $currentArtContent = $currentArtContent."       </div>\n";
        $currentArtContent = $currentArtContent.
        "       <div class=\"card-body\">\n".
        "           <p class=\"mt-0 mb-1\">[{$art['group']}]</p>\n".
        "           <h4 class=\"card-title\">{$art['title']}</h4>\n".
        "           <p class=\"card-text\">{$art['prev']}...</p>\n".
        "           <div class=\"card-text\"><img src=\"/img/element/watched.svg\" width=\"24\" height=\"24\"/>&nbsp;&nbsp;{$art['counter_visit']}&nbsp;&nbsp;<img src=\"/img/element/comments.svg\" width=\"24\" height=\"24\"/>&nbsp;&nbsp;{$art['counter_commits']}&nbsp;</div>\n".
        "           <p class=\"card-text\" align=\"right\">{$date[0]}年{$date[1]}月{$date[2]}日 {$time[0]}时{$time[1]}分</p>\n".
        "       </div>\n".
        "   </div>\n".
        "</a>\n";
        $outputString = $outputString.$currentArtContent;
    }
    return $outputString;
}

function buildUrlQueryStr($pageto = null,$groupIDto = null,$searchto = null){
    global $page;
    global $targetPage;
    $tPage = $page;
    if(!is_null($pageto)) $tPage = $pageto;
    
    global $filter_group;
    $tGroup = $filter_group;
    if(!is_null($groupIDto)) $tGroup = $groupIDto;

    global $filter_searcher;
    $tSearch = $filter_searcher;
    if(!is_null($searchto)) $tSearch = $searchto;

    return "/$targetPage?p=$tPage&group=$tGroup&search=$tSearch";
}

?>
<div id="main_container" class="row">
    <div id="arts" class="col-sm-8">
        <!--<a class="art_atag" href="">
            <div class="card art_cardview">
                <img src="/img/arts/test.png" class="card-img-top" alt="...">
                <div class="tagContainer">
                    <div class="art_tag" style="background-color:aqua;color:aqua"><span>C#</span></div>
                </div>
                <div class="card-body">
                    <p class="mt-0 mb-1">[分组]</p>
                    <h4 class="card-title">文章标题</h4>
                    <p class="card-text">文章简介</p>
                    <p class="art_status"><img src="/img/element/clicked.svg">&nbsp;0&nbsp;<img src="/img/element/comments.svg">&nbsp;0&nbsp;</p>
                </div>
            </div>
        </a>-->
        <?php echo GetArtsHTMLDom() ?>

        <nav aria-label="Page navigation">
            <ul class="pagination mt-3" style="justify-content:center">

            <?php
            $thisPage = $page;
            $pageMax = ceil($pageCount);
            //echo $artCount;

            if($thisPage > 0){
                echo '<li class="page-item"><a class="page-link" href="'.buildUrlQueryStr($thisPage-1).'">&lt;</a></li>';
            }
            else echo '<li class="page-item"><a class="page-link">&lt;</a></li>';
            if($thisPage > 0) {
                echo '<li class="page-item"><a class="page-link" href="'.buildUrlQueryStr(0).'">1</a></li>';
                echo '<li class="page-item"><a class="page-link" href="#">..</a></li>';
            }
            echo '<li class="page-item active"><a class="page-link" href="'.buildUrlQueryStr($thisPage).'">'.($thisPage+1).'</a></li>';
            if($thisPage < $pageMax){
                echo '<li class="page-item"><a class="page-link" href="#">..</a></li>';
                echo '<li class="page-item"><a class="page-link" href="'.buildUrlQueryStr($pageMax).'">'.($pageMax+1).'</a></li>';
            }
            if($thisPage < $pageMax){
                echo '<li class="page-item"><a class="page-link" href="'.buildUrlQueryStr($thisPage+1).'">&gt;</a></li>';
            }
            else echo '<li class="page-item"><a class="page-link">&gt;</a></li>';
            ?>
                
            </ul>
        </nav>
    </div>
    <div id="external" class="col-sm-4 mb-3 <?php if($isNight) echo 'night'; ?>">
        <?php
        $listCard = scandir($exCardRoot);
        $listCard = array_values(array_diff($listCard,array('.','..')));
        foreach($listCard as $card){
            include($exCardRoot.$card);
        }
        ?>
    </div>
</div>