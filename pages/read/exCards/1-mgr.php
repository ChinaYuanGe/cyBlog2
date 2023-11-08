<?php
if(!CheckIfLogin()){
    goto endofmgr;
}

?>
<div class="exCard">
    <?php
        if($artInfo['status'] == 3){
            echo "<a class=\"btn btn-block btn-danger\" onclick=\"DeleteArt()\">删除文章</a>";
        }
        else{
            echo "<a class=\"btn btn-block btn-primary\" href=\"/writer/$artID\">编辑文章</a>";
        }
    ?>
    
</div>

<?php endofmgr: ?>