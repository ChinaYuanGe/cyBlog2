<?php
$exCardRoot = $pageRoot.'exCards/';

$artID = (int)$routerInfo[2];
$artPathRoot1 = $docRoot."arts/$artID";
$artPathRoot = $docRoot."arts/$artID/";

$artInfo = GetArtData($artID);

$artIsExists = ($artInfo !== false && is_dir($artPathRoot1));

if($artIsExists){
    if(Bot_UA_Match($_SERVER["HTTP_USER_AGENT"]) === false){
        AddArtVisitCounter($artID);
    }
}
else{
    goto missingdoc;
}
?>

<div id="main_container" class="row night">
    <div class="col-sm-8">
        <h1 align="center"><?php echo $artInfo['title']; ?></h1>
        <hr/>
        <div class="mce-content-body">
            <?php include($artPathRoot.'content.html'); ?>
        </div>
    </div>
    <div class="col-sm-4">
    <?php
        $listCard = scandir($exCardRoot);
        $listCard = array_values(array_diff($listCard,array('.','..')));
        foreach($listCard as $card){
            include($exCardRoot.$card);
        }
        ?>
    </div>
</div>
<?php if($artIsExists) goto endofdoc; ?>
<?php missingdoc: ?>

<div class="mt-2<?php if($isNight) echo ' night' ?>">
    <h1 align="center">文章不存在或文档缺失</h1>
</div>

<?php
    endofdoc:
?>