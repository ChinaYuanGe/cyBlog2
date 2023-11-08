<?php
$limit = ReadConfigProfile("limits")['ArtPrintLimit'];
$page = ((int)$routerInfo[2]) < 0 ? 0 : ((int)$routerInfo[2]);
$allDraft = GetAllArtDraft($limit,$page);
$groupData = $db->QuerySQL("SELECT id,name,(SELECT COUNT(*) FROM arts WHERE arts.`group`=groups.id) as artCount FROM groups");
?>

<div class="row" id="main_container">
    <div class="col-sm-4 mb-3">
        <button class="btn btn-block btn-success mb-3" onclick="createArtDraft()">(+) 新建草稿</button>
        <div class="card_sp mb-3">
            <h4>总览</h4>
            <p>文章:&nbsp;<?php echo $db->QuerySQL("SELECT COUNT(*) FROM arts WHERE `status`>0;")[0][0] ?></p>
            <p>草稿:&nbsp;<?php echo $db->QuerySQL("SELECT COUNT(*) FROM arts WHERE `status`=0;")[0][0] ?></p>
            <p>最终:&nbsp;<?php echo $db->QuerySQL("SELECT COUNT(*) FROM arts WHERE `status`=3;")[0][0] ?></p>
        </div>
        <div class="card_sp">
            <h4>分组</h4>
            <table id="groups">
                <?php  
                foreach($groupData as $g){
                    echo '<tr><td><span class="badge badge-primary">'.$g['artCount'].'</span>&nbsp;'.$g['name'].'</td><td><button class="btn btn-block btn-danger btn-sm" onclick="deleteGroup('.$g['id'].')">删除</button></td></tr>'."\n";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="col-sm-8 mb-3">
        <?php
        if(count($allDraft) < 1){
            echo "<h1 align=\"center\">此处空空如也~</h1>";
        }
        else{
            foreach($allDraft as $draft){
                echo 
                "<a class=\"art_atag\" href=\"/writer/{$draft['id']}\">\n".
                "   <div class=\"card art_cardview art_rib\" data-text=\"草稿\">\n".
                "       <img src=\"{$draft['title_img']}\" class=\"card-img-top\">\n".
                "       <div class=\"tagContainer\">";
                foreach($draft['tags'] as $tag){
                    $colorHash = '#'.GetColorByHex($tag);
                    if(strlen($tag) > 0)
                    echo 
                    "       <div class=\"art_tag\" style=\"background-color:$colorHash;color:$colorHash\"><span>$tag</span></div>\n";
                }
                echo "       </div>".
                "       <div class=\"card-body\">\n".
                "           <p class=\"mt-0 mb-1\">[{$draft['group']}]</p>".
                "           <h4 class=\"card-title\">{$draft['title']}</h4>\n".
                "           <p>创建时间:&nbsp;{$draft['time_pubish']}</p>\n".
                "       </div>\n".
                "   </div>\n".
                "</a>\n";
            }
        }
        ?>
    </div>
</div>