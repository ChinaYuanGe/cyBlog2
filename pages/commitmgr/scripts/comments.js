function DelComment(id) {
    let m = mkAutoAlertModal('请稍后', '正在删除', () => {
        ajax_post('/api/commitMgr/delete.php', { id: id }, w = {
            success: (data) => {
                let d = JSON.parse(data);
                if (d['status'] == 'ok') {
                    ReloadComment(0);
                }
                else if (d['status'] == 'fail') {
                    w.fail(d['data']['msg']);
                }
                else {
                    w.fail("服务器发送了无效的响应");
                }
            },
            fail: (t) => {
                mkAlertModal('错误', t, () => { }, true, '好', 'danger');
            },
            always: () => {
                dismissModal(m);
            }
        });
    }, '删除', 'danger');
}

function PassComment(id, white = 0) {
    let m = mkAutoAlertModal('请稍后...', "正在请求放行", () => {
        ajax_post('/api/commitMgr/pass.php', { id: id, white: white }, w = {
            success: (data) => {
                let d = JSON.parse(data);
                if (d['status'] == 'ok') {
                    ReloadComment(0);
                }
                else if (d['status'] == 'fail') {
                    w.fail(d['data']['msg']);
                }
                else {
                    w.fail('服务器发送了无效的响应');
                }
            },
            fail: (t) => {
                mkAlertModal('错误', t, () => { }, true, '好', 'danger');
            },
            always: () => {
                dismissModal(m);
            }
        });
    }, '放行', 'success');

}

function ReloadComment(page) {
    ajax_get('/api/commitMgr/getHidden.php', { p: page }, w = {
        before: () => {
            $("#comments tbody").empty();
            $('#comments tbody').append('加载中...');
            $('#pagena').empty();
        },
        success: (data) => {
            let d = JSON.parse(data);
            if (d['status'] == 'ok') {
                let Holder = $('#comments tbody');
                Holder.empty();
                //评论内容
                d['data']['commits'].forEach((x) => {
                    let tr = $('<tr></tr>');
                    tr.append('<td><img width="128" height="128" src="/api/commits/getavator.php?hash=' + x['email'] + '"></td>');
                    tr.append('<td>' + x['name'] + '</td>');

                    tr.append('<td>' +
                        (parseInt(x['respid']) > 0 ? '@' + x['repname'] + ': ' + fdbase(x['repsrc']) + '<br><br>' : '') +
                        fdbase(x['content']) +
                        '<br><a href="/read/' + x['artid'] + '">[前往现场]</a>' +
                        '</td>');

                    tr.append('<td>' + x['email'] + '</td>');
                    tr.append('<td>' + x['time'] + '</td>');

                    let btnTd = $('<td></td>');
                    let passBtn = $('<button class="btn btn-success mt-1">放行</button>');
                    passBtn.click(function () {
                        mkConfirmModal('放行确认', '需要放行该评论吗?', () => {
                            PassComment(x['id']);
                        }, true, 'success', '确认');

                    });

                    btnTd.append(passBtn);

                    let whiteBtn = $('<button class="btn btn-warning mt-1">白名单</button>')
                    whiteBtn.click(function () {
                        mkConfirmModal('白名单确认', '确认要将其加入白名单吗?', () => {
                            PassComment(x['id'], 1);
                        }, true, 'warning', '确认');

                    });
                    btnTd.append(whiteBtn);

                    let delBtn = $('<button class="btn btn-danger mt-1">删除</button>')
                    delBtn.click(function () {
                        mkConfirmModal('删除确认', '确认要删除该评论吗?', () => {
                            DelComment(x['id']);
                        }, true, 'danger', '确认');
                    });
                    btnTd.append(delBtn);
                    tr.append(btnTd);
                    Holder.append(tr);
                });
                let pagenation = $('<div class="pagenation"></div>')
                let navPagenation = $('<nav aria-label="Page navigation example"></nav>')
                let ulPagenation = $('<ul class="pagination">');

                let MaxPage = parseInt(d['data']['maxPage']) - 1;
                let prevPage = page <= 0 ? 0 : page - 1;
                let prevBtn = $('<li class="page-item"><button class="page-link">&lt;</button></li>');
                prevBtn.find('button').click(function () {
                    ReloadComment(prevPage);
                });
                ulPagenation.append(prevBtn);
                let nextPage = page >= MaxPage ? MaxPage : page + 1;
                ulPagenation.append('<li class="page-item"><button class="page-link" href="#">' + (page + 1) + '/' + (MaxPage + 1) + '</button></li>');
                let nextBtn = $('<li class="page-item"><button class="page-link">&gt;</button></li>');
                nextBtn.find('button').click(function () {
                    ReloadComment(nextPage);
                });
                ulPagenation.append(nextBtn);
                navPagenation.append(ulPagenation);
                pagenation.append(navPagenation);
                $('#pagena').append(pagenation);

            }
            else if (data['status'] == 'fail') {
                w.fail('请求错误:' + data['data']['msg']);
            }
            else {
                w.fail('服务器发送无效的响应');
            }
        },
        fail: (t) => {
            let holder = $('#comments');
            let retryBtn = $('<button class="btn btn-warning">重试</button>');
            retryBtn.click(function () {
                ReloadComment(page);
            });
            bsMKalert("错误:" + t, 'danger', '#commits', 60000);
            holder.append(retryBtn);
        },
        always: () => {

        }
    });
}