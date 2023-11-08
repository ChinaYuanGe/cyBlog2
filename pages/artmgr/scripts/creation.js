function createArtDraft(){
    var modal = mkInputModal('新建草稿','标题',(text)=>{
        ajax_post('/api/arts/create.php',{title:text},{
            before:()=>{
                confirmModal_setProcess(modal);
            },
            success:(data)=>{
                var jData = JSON.parse(data);
                if(jData['status'] == "ok"){
                    window.location.href = "/writer/"+jData['data']['id'];
                }
                else if(jData['status'] == "fail"){
                    mkAlertModal('请求错误','请求时发生错误:'+jData['data']['msg']);
                }
                else{
                    mkAlertModal('请求错误',"请求时发生错误:服务器发送了无效的响应");
                }
            },
            fail:(t)=>{
                mkAlertModal('请求错误',"与服务器通讯时发生错误:"+t);
            },
            always:()=>{
                confirmModal_unsetProcess(modal);
            }
        },6000);
    },'新建','success',false);
}