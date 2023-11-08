<?php
$mainConfig = ReadModuleConfigProfile('master');
$infoLinks = ReadModuleConfigProfile('links');
$friendLinks = ReadModuleConfigProfile('friendlinks');
$hobbyList = ReadModuleConfigProfile('hobby');

$displayName = $mainConfig['mainname'];
$myDes = $mainConfig['des'];
$avatorImageURL = $mainConfig['avator'];

$totalVisibleComment = GetCommentCount(1);
$totalHiddenComment = GetCommentCount(0);

$totalActiveArt = GetArtCount(1,'>');
?>

<div class="exCard">
    <p class="mt-2" style="text-align:center">
        <img src="<?php echo $avatorImageURL; ?>" style="width:8em;height:8em;border-radius:50%;object-fit: cover;">
    </p>
    <h3 style="text-align:center"><?php echo $displayName ?></h3>
    <p style="text-align:center"><?php echo $myDes ?></p>
    <div style="display:flex;flex-wrap:nowrap;justify-content:space-around">
        <div style="width: fit-content;">
            <h4 style="text-align:center"><?php echo $totalActiveArt; ?></h4>
            <p style="text-align:center">文章</p>
        </div>
        <div style="width: fit-content;">
            <h4 style="text-align:center"><?php echo $totalVisibleComment ?><span style="color:#D34E00;font-size:.8em" <?php echo ($totalHiddenComment < 1 ? "hidden" : "") ?>>+<?php echo $totalHiddenComment ?></span></h4>
            <p style="text-align:center">评论</p>
        </div>
    </div>
    <div class="mylinks">
        <?php
            foreach($infoLinks as $key=>$value){
                $values = explode(',',$value);
                $display = $values[0];
                $targetURL = $values[1];
                $imgURL = $values[2];
                $targetColor = $values[3];
                echo '<div><a title="'.$key.'" href="'.$targetURL.'" class="btn btn-outline-'.$targetColor.'">'.($imgURL=='null'?'':'<img src="'.$imgURL.'"/>').($display=="NO_VALUE"?"":"&nbsp;".$display).'</a></div>'."\n";
            }
        ?>
    </div>
</div>