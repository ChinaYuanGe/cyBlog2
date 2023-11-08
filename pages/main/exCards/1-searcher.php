<div class="exCard">
    <h5>搜索<?php if($filter_group > 0){echo '&nbsp;('.$db->QuerySQL("SELECT `name` FROM groups WHERE id=$filter_group")[0][0].")";} ?></h5>
    <div class="input-group">
        <input id="input_search" type="text" class="form-control" placeholder="文章标题" aria-label="文章标题" aria-describedby="button-addon2" value="<?php echo $filter_searcher ?>">
        <div class="input-group-append">
            <?php if(is_null($filter_searcher) && $filter_group < 1) goto endofCancelBtn; ?>
            <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="window.location.href='/<?php echo $targetPage; ?>'">X</button>
            <?php endofCancelBtn: ?>
            <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="doSearch()">检索</button>
        </div>
    </div>
</div>