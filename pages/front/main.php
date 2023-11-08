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

        <div class="row fixedHeight">
            <div class="col-sm-6">
                <div class="contentFlexBox jacenter dircol"><img class="avatorImg" src="<?php echo $avatorImageURL ?>"></div>
            </div>
            <div class="col-sm-6">
                <div class="contentFlexBox jacenter dircol">
                    <div class="row">
                        <div class="col-sm">
                            <h2 class="tac"><?php echo $displayName; ?></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <p class="tac"><?php echo $myDes ?></p>
                        </div>
                    </div>
                    <div class="row mt-2 mb-2" style="align-content: center">
                        <div class="col-sm-6">
                            <h2 class="tac">文章: <?php echo $totalActiveArt ?></好>
                        </div>
                        <div class="col-sm-6">
                            <h2 class="tac">评论: <?php echo $totalVisibleComment ?><span style="font-size:.8em<?php echo ($totalHiddenComment > 0? ';color:#D34E00' : '') ?>"><?php echo ($totalHiddenComment > 0 ? '+'.$totalHiddenComment : '') ?></span></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <div class="mylinks">
                                <?php
                                    foreach($infoLinks as $key=>$value){
                                        $values = explode(',',$value);
                                        $targetURL = $values[0];
                                        $imgURL = $values[1];
                                        $targetColor = $values[2];
                                        echo '<div><a href="'.$targetURL.'" class="btn btn-outline-'.$targetColor.'">'.($imgURL=='null'?'':'<img src="'.$imgURL.'"/>')."&nbsp;".$key.'</a></div>'."\n";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row fixedHeight">
            <div class="col-sm-2">
                <div class="contentFlexBox jacenter dircol">
                    <div>
                        <h2 class="upsideText">兴趣 & 喜好</h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="contentFlexBox jacenter macenter">
                    <?php
                        foreach($hobbyList as $key=>$value){
                            $values = explode(',',$value);
                            $desValue = $values[0];
                            $imgURL = $values[1];
                            echo 
                            "<div class=\"infoCard\">\n".
                            "   ".($imgURL == 'null'?'':"<div><img src=\"$imgURL\"/></div>\n").
                            "   <div>".
                            "       <div class=\"infoCard_Title\">$key</div>".
                            "      <div>$desValue</div>".
                            "   </div>".
                            "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="row fixedHeight reverse">
            <div class="col-sm-2">
                <div class="contentFlexBox jacenter dircol">
                    <div>
                        <h2 class="upsideText">友链</h2>
                    </div>
                    <div>
                        <p class="tac"><button class="btn btn-warning" onclick="popupLinkMsgHelp()">?</button></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="contentFlexBox jacenter">
                    <?php
                        foreach($friendLinks as $key=>$value){
                            $values = explode(',',$value);
                            $targetURL = $values[0];
                            $desValue = $values[1];
                            $imgURL = $values[2];
                            echo 
                            "<a class=\"infoCard_a\" href=\"$targetURL\">".
                            "   <div class=\"infoCard\">\n".
                            "       ".($imgURL == 'null'?'':"<div><img src=\"$imgURL\"/></div>\n").
                            "       <div>".
                            "           <div class=\"infoCard_Title\">$key</div>".
                            "       <div>$desValue</div>".
                            "       </div>".
                            "   </div>".
                            "</a>";
                        }
                    ?>
                </div>
            </div>
</div>