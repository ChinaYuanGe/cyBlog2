function deleteGroup(id){
    var modal = mkAutoAlertModal('正在请求','成功后页面将刷新',()=>{
        ajax_post('/api/arts/deleteGroup.php',{id:id},{
        before:()=>{},
        success:(data)=>{
            var jData = JSON.parse(data);
            if(jData['status'] == 'ok'){
                window.location.reload();
            }
            else if(jData['status'] == 'fail'){
                mkAlertModal('请求错误','与服务器通讯时发生错误:'+jData['data']['msg']);
            }
            else{
                mkAlertModal('请求错误','与服务器通讯时发生错误:服务器发送了无效的响应');
            }
        },
        fail:(t)=>{
            mkAlertModal('请求错误','与服务器通讯时发生错误:'+t);
        },
        always:()=>{
            dismissModal(modal);
        }
    },6000)},'删除...','danger');
}