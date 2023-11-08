<?php
$currentArtID = (int)$routerInfo[2];
$artRoot = $docRoot."arts/$currentArtID";

$artData = GetArtData($currentArtID);
if($artData === false || $artData['status'] > 2){
    echo '<h1 align="center">无法找到相应文章</h1>';
    goto endofwriter;
}

if(!is_dir($artRoot) && !CreateEmptyArtFile($currentArtID)){
    echo '<h1 align="center">无法创建相关文件</h1>';
    goto endofwriter;
}

$artContentPath = $artRoot.'/content.html';

?>

<div class="row" id="main_container">
    <div class="col-sm-8 mb-3">
        <input id="input_title" type="text" class="form-control mb-1" placeholder="标题" value="<?php echo $artData['title'] ?>">
        <textarea id="mainEditor"><?php
            include($artContentPath);
        ?></textarea>
    </div>
    <div class="col-sm-4">
        <div class="card_sp mb-3" style="position:relative">
            <div id="alertHolder" style="position: absolute;left:0;right:0;top:0"></div>
            <img id="prev_titleimage" class="picPreview mb-3" src="<?php echo $artData['title_img'] ?>">
            <button class="btn btn-block btn-success" onclick="uploadTitleImage()">更换封面</button>
        </div>
        <div class="card_sp mb-3">
            <h5>分组</h5>
            <select class="custom-select" id="input_group">
                <option value="0"<?php echo ($artData['groupid']==0 ? " selected" : null); ?>>未分组</option>
                <option value="-1">新建分类...</option>
                <?php
                    $groupData = GetGroupData();
                    foreach($groupData as $group){
                        echo "<option value=\"{$group['id']}\"".($artData['groupid'] == $group['id'] ? ' selected':null).">{$group['name']}</option>\n";
                    }
                ?>
            </select>
        </div>
        <div class="card_sp mb-3">
            <h5>标签</h5>
            <div id="tagDisplayer" style="display:flex; justify-content:center; flex-wrap:wrap" class="mb-2">
                <?php
                foreach($artData['tags'] as $tag){
                    $colorHash = '#'.GetColorByHex($tag);
                    if(strlen($tag) > 0)
                    echo "<div class=\"art_tag\" style=\"background-color:$colorHash;color:$colorHash\"><span>$tag</span></div>\n";
                }
                ?>
            </div>
            <div class="input-group">
            <input id="input_tag" type="text" class="form-control" placeholder="标签(Enter确认,使用','分割)"/>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="InsertATag()">插入</button>
                </div>
            </div>
            
        </div>
        <div class="card_sp mb-3">
            <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modal_serverImages">位于服务端的图片</button>
        </div>
        <div class="card_sp mb-3">
            <div class="row" style="margin:0;width:100%">
                <div class="col-sm-6">
                    <button class="mt-3 mb-3 btn btn-block btn-success artBtn" onclick="saveArtContent()" disabled>保存</button>
                </div>
                <div class="col-sm-6">
                    <button class="mt-3 mb-3 btn btn-block btn-danger artBtn" onclick="DeleteArt()" disabled>删除</button>
                </div>
            </div>
        </div>
        <div class="card_sp mb-3">
            <div class="row" style="margin:0;width:100%">
                <div class="col-sm-6">
                    <button class="mt-3 mb-3 btn btn-block btn-warning artBtn" onclick="pubish()" <?php echo ($artData['status'] == 2? "noallow" : null)?> disabled><?php echo ($artData['status'] == 2? "
                    已" : null)?>发布</button>
                </div>
                <div class="col-sm-6">
                    <button class="mt-3 mb-3 btn btn-block btn-outline-danger artBtn" onclick="pubish_final()" disabled>发布最终版</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_serverImages" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">位于服务器的图片</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="serverPicList">
        <div class="mb-3">
            <img class="prevServerPic mb-3" src="/img/arts/default_title.png">
            <button class="btn btn-block btn-success">插入光标位置</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script> $(function(){ initMCE(); }); </script>

<?php endofwriter: ?>