function pubish(){
    mkConfirmModal('确认发布','发布后，访客将可查看您的文章，你也可以随时修改文章。',()=>{
        saveArtContent(()=>{
            var modal = mkAutoAlertModal('正在发布','请稍后...',()=>{
                ajax_post('/api/arts/pubish.php',{id:artID},{
                    success:(data)=>{
                        var jData = JSON.parse(data);
                        if(jData['status'] == 'ok'){
                            mkAlertModal('发布完毕','点击确认以检查您的作品.',()=>{
                                window.location.href="/read/"+artID;
                            });
                        }
                        else if(jData['status'] == 'fail'){
                            mkAlertModal('发布出错','请求发生错误:'+jData['data']['msg']);
                        }
                        else{
                            mkAlertModal('发布出错','请求发生错误:服务器发送了无效的响应');
                        }
                    },
                    fail:(t)=>{
                        mkAlertModal('发布出错','与服务器通讯时发生错误:'+t);
                    },
                    always:()=>{
                        dismissModal(modal);
                    }
                });
            })
        });
    });
}
function pubish_final(){
    mkConfirmModal('最终确认','确认后，您将<span style="color:red">无法再次编辑该文章</span>。',()=>{
        saveArtContent(()=>{
            var modal = mkAutoAlertModal('正在发布','请稍后...',()=>{
                ajax_post('/api/arts/final.php',{id:artID},{
                    success:(data)=>{
                        var jData = JSON.parse(data);
                        if(jData['status'] == 'ok'){
                            mkAlertModal('发布完毕','点击确认以检查您的作品.',()=>{
                                window.location.href="/read/"+artID;
                            });
                        }
                        else if(jData['status'] == 'fail'){
                            mkAlertModal('发布出错','请求发生错误:'+jData['data']['msg']);
                        }
                        else{
                            mkAlertModal('发布出错','请求发生错误:服务器发送了无效的响应');
                        }
                    },
                    fail:(t)=>{
                        mkAlertModal('发布出错','与服务器通讯时发生错误:'+t);
                    },
                    always:()=>{
                        dismissModal(modal);
                    }
                });
            })
        });
    },false,'danger','确认');
}