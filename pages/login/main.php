<div class="row">
    <div class="col-sm-4"></div>
    <div align="center" class="col-sm-4">
        <h2>登录入口</h2>
        <form>
            <input id="passmd5" type="password" class="form-control mb-3" placeholder="登入口令">
            <input type="submit" id="btn_login" class="btn btn-primary" onclick="goAuth($('#passmd5').val())" value="登入">
        </form>
        <div id="alertPlacer" class="mt-3"></div>
    </div>
    <div class="col-sm-4"></div>
</div>