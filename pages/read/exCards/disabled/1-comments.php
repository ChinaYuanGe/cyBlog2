<?php
$enable = ReadConfigProfile('functions')['comment'];
if($enable != 'true'){
    goto endofcomments;
}
?>
<div class="exCard">
    <div id="comments">
        <div>
            <h5>ChinaYuanGe</h5>
            <p>短评论短评论短评论短评论短评论</p>
            <small>1970年01月01日 00时00分</small>
        </div>
        <div>
            <h5>ChinaYuanGe</h5>
            <p>短评论短评论短评论短评论短评论</p>
            <small>1970年01月01日 00时00分</small>
        </div>
        <div>
            <h5>ChinaYuanGe</h5>
            <p>短评论短评论短评论短评论短评论</p>
            <small>1970年01月01日 00时00分</small>
        </div>
    </div>
    <hr/>
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0" style="justify-content:center">
            <li class="page-item"><a class="page-link" href="#">&lt;</a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">...</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">...</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">&gt;</a></li>
        </ul>
    </nav>
</div>
<?php endofcomments:?>