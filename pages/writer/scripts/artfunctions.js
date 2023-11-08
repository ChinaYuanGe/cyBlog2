global_saved = true;
global_saving = false;
function saveArtContent(successCallback, showModal = true) {
    if (global_saving) return;
    global_saving = true;
    var infoContent = btoa(encodeURIComponent(tinymce.activeEditor.getContent()));
    var infoGroup = $('#input_group option:selected').val();
    var infoTitle = $('#input_title').val();

    var infoTags = "";

    var tags = $('#tagDisplayer>div>span');
    for (var i = 0; i < tags.length; i++) {
        infoTags += tags[i].innerText + ",";
    }
    infoTags = infoTags.substring(0, infoTags.length - 1);
    var modal;
    var saveFun = () => {
        let showmodal = showModal;
        ajax_post('/api/arts/savecontent.php', { id: artID, title: infoTitle, content: infoContent, group: infoGroup, tags: infoTags }, {
            success: (data) => {
                try {
                    var jdata = JSON.parse(data);
                }
                catch {
                    mkAlertModal('保存失败', '服务器发送了无效的响应');
                }
                if (jdata['status'] == 'ok') {
                    console.log(showmodal);
                    if (showmodal) dismissModal(modal);
                    global_saved = true;
                    if (typeof (successCallback) == 'function') successCallback();
                }
                else if (jdata['status'] == 'fail') {
                    mkAlertModal('保存失败', '服务器响应如下:' + jdata['data']['msg']);
                }
                else {
                    mkAlertModal('保存失败', '服务器发送了无效的响应');
                }
            },
            fail: (t) => {
                mkAlertModal('保存失败(' + t + ')', '请检查您的网络连接是否正常');
            },
            always: () => {
                global_saving = false;
                if (showmodal) dismissModal(modal);
            }
        }, 10000)
    };

    if (showModal) {
        modal = mkAutoAlertModal('保存...', '执行时请勿关闭本页.', saveFun, "保存中", 'success');
    }
    else {
        saveFun();
    }
}

function uploadImage(type, file, method) {
    var formData = new FormData();

    formData.append('id', artID);
    formData.append('type', type);
    formData.append('file', file);
    a = formData;

    $.ajax({
        type: 'post',
        url: "/api/arts/uploadImage.php",
        contentType: false,
        data: formData,
        processData: false,
        timeout: 15000,
        success: (data) => {
            data = JSON.parse(data);
            if (data['status'] == 'ok') {
                method.success(data['data']);
            } else if (data['status'] == 'fail') {
                console.error('无法上传图片:' + data['data']['msg']);
                method.fail(data['data']['msg']);
            } else {
                console.error('无法上传图片:未知错误');
                method.fail('未知错误');
            }
            method.always();
        },
        error: (t) => {
            console.error('无法上传图片:' + t);
            method.error(t);
            method.always();
        }
    });
}

function uploadTitleImage() {
    let input = document.createElement('input');
    input.setAttribute('type', 'file');

    input.onchange = () => {
        var modal = mkAutoAlertModal('上传封面', '正在上传图片,请稍后...', () => {
            uploadImage(1, input.files[0], {
                success: (d) => {
                    $('#prev_titleimage').attr('src', d['src']);
                },
                fail: (m) => {
                    mkAlertModal('发生错误！', '上传图片失败：' + m, () => { }, true, '好.', 'danger');
                },
                error: (t) => {
                    mkAlertModal('发生错误！', '上传图片失败：' + t.statusText, () => { }, true, '好.', 'danger');
                },
                always: () => {
                    dismissModal(modal);
                    GetServerImages();
                }
            });
        }, '上传...', 'primary');
    }

    input.click();
}

function DeleteArt() {
    var modal = mkConfirmModal('删除确认', '确定要删除该文章吗?<br>此操作不可逆!', () => {
        ajax_post('/api/arts/delete.php', { id: artID }, {
            before: () => {
                confirmModal_setProcess(modal);
            },
            success: (data) => {
                var jData = JSON.parse(data);
                if (jData['status'] == 'ok') {
                    mkAlertModal('删除完成', '点击确认跳转至主页', () => {
                        window.location.href = "/";
                    });
                }
                else if (jData['status'] == "fail") {
                    mkAlertModal('删除出错', '请求发生错误:' + jData['data']['msg']);
                }
                else {
                    mkAlertModal('删除出错', '请求发生错误:服务器发送了无效的响应');
                }
            },
            fail: (t) => {
                mkAlertModal('删除出错', '与服务器通讯时发生错误:' + t);
            },
            always: () => {
                confirmModal_unsetProcess(modal);
                dismissModal(modal);
            }
        }, 6000);
    }, false, 'danger', '删除');
}