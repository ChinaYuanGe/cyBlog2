<div class="exCard">
    <div id="tagLister">
        <?php
            $latestArt = GetAllArtData(12,0,2,null,1);
            $outputTags = array();
            $outputCount = 0;
            $outputMax = 12;
            foreach($latestArt as $a){
                if(count($a['tags']) == 1){
                    if(strlen($a['tags'][0]) < 1) continue;
                    else if(!in_array($a['tags'][0],$outputTags)){
                        array_push($outputTags,$a['tags'][0]);
                        $outputCount++;
                    }
                }
                else{
                    foreach($a['tags'] as $subtags){
                        if(!in_array($subtags,$outputTags)){
                            array_push($outputTags,$subtags);
                            $outputCount++;
                            break;
                        }
                    }
                }

                if($outputCount >= $outputMax) break;
            }

            foreach($outputTags as $tag){
                $colorHash = '#'.GetColorByHex($tag);
                echo '<a class="art_atag" href="/'.$targetPage.'?p=0&group=0&search='.urlencode($tag).'"><div class="art_tag" style="background-color:'.$colorHash.';color:'.$colorHash.'"><span class="rej">'.$tag.'</span></div></a>';
            }
        ?>
    </div>
</div>