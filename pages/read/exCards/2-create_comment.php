<div class="commentCard">
    <div class="commentHead">
        <div>
            <img id="comment_imgdisplay" src="">
        </div>
        <div>
            <div id="comment_name" contenteditable="true" placeholder="大名"></div>
            <div id="comment_email" contenteditable="true" placeholder="电子邮件(必须)"></div>
        </div>
    </div>
    <div class="commentBody">
        <div id="comment_reply" class="commentRep" order="0" onclick="ClearReply()" hidden>回复 XXX: somecontent (点击取消)</div>
        <div id="comment_content" contenteditable placeholder="正文(必须)"></div>
    </div>
    <div class="mt-2">
        <button id="comment_sendbtn" class="btn btn-outline-success btn-sm btn-block" onclick="UI_PostComment()">提交评论</button>
    </div>
    <div class="mt-1"><p align="center"><small>用 Cookie 保存: 别名、Email</small></p></div>
</div>