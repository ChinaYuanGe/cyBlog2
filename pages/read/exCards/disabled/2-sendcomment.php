<?php
$enable = ReadConfigProfile('functions')['comment'];
if($enable != 'true'){
    goto endofsendcomment;
}
?>
<div class="exCard">
    <h4>评论</h4>
    <div>
        <form>
            <input class="form-control mb-3" type="text" placeholder="您的大名(必须)*">
            <textarea class="form-control mb-2" placeholder="评论正文" style="resize:none; height:8em"></textarea>
            <input class="btn btn-success btn-block" type="submit" value="提交(需审核)">
        </form>
    </div>
</div>

<?php endofsendcomment:?>