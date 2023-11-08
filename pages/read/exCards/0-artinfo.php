<div class="exCard">
    <p class="mb-1">分组于:&nbsp;<?php echo $artInfo['group'] ?></p>
    <?php
    if($artInfo['status'] == 2){ //发布
        if(!is_null($artInfo['time_pubish'])) echo "<p class=\"mb-1\">发布于:&nbsp;".GetFormatedDateTime($artInfo['time_pubish'])."</p>";
        if(!is_null($artInfo['time_lastedit'])) echo "<p class=\"mb-1\">编辑于:&nbsp;".GetFormatedDateTime($artInfo['time_lastedit'])."</p>";
    }
    else if($artInfo['status'] == 3){ // 最终版
        if(!is_null($artInfo['time_lastedit'])) echo "<p class=\"mb-1\">确认于:&nbsp;".GetFormatedDateTime($artInfo['time_lastedit'])."</p>";
    }
    ?>
    <div style="display:flex;justify-content:center">
    <?php
    if(!(count($artInfo['tags']) == 1 && strlen($artInfo['tags'][0]) < 1)){
        foreach($artInfo['tags'] as $tag){
            $colorHash = '#'.GetColorByHex($tag);
            echo "<div class=\"art_tag\" style=\"background-color:$colorHash;color:$colorHash\"><span>$tag</span></div>";
        }
    }
    ?>
    </div>
</div>