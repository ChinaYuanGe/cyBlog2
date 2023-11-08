<?php
    $groupInfo = $db->QuerySQL("SELECT id,name,(SELECT COUNT(*) FROM arts WHERE arts.`group`=groups.id AND arts.`status` >= 2) as `artCount` FROM groups;");
?>

<div class="exCard">
    <h4 class="mb-1">分组 <span class="badge badge-primary"><?php echo count($groupInfo) ?></span> <button style="float:right;" class="btn btn-small btn-sm btn-primary" data-target="#groupShower" data-toggle="collapse">⬘</button></h4>
    <div id="groupShower" class="collapse">
        <?php
            foreach($groupInfo as $group){
                echo "<a href=\"".buildUrlQueryStr(0,$group['id'],null)."\"><span class=\"badge badge-primary\">{$group['artCount']}</span>&nbsp;{$group['name']}</a>\n";
            }
        ?>
    </div>
</div>