<?php
    $artData = GetArchivedArtData();
?>

<div class="lineboard">
    <?php
        foreach($artData as $ykey=>$yearArray){
            echo '<div class="lb-title">'.$ykey."年</div>";
            foreach($yearArray as $mkey=>$monthArray){
                $monthKey = (int)$mkey;
                echo '<div class="lb-sub">'.($monthKey)."月</div>";
                foreach($monthArray as $curArt){
                    echo '<a class="lblink" href="/read/'.$curArt['id'].'"><div class="lb-content"><p>'."[{$curArt["group"]}] {$curArt["title"]}".'</p></div></a>';
                }
            }
        }
    ?>
</div>