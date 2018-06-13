// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | GPL v3 ( https://choosealicense.com/licenses/gpl-3.0/ )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------

var $$ = mdui.JQ;
var time = 60;
var re_cid = 0; 

$$('#getCode').on('click', function () {

    if ($$('#email').val() == '' || $$('#username').val() == '') {
        mdui.snackbar({
            message: '邮箱或用户名不得为空',
            position: 'top'
        });
    } else {
        $$.ajax({
            method: 'POST',
            url: '/api/api/getRegCode.html',
            data: {
                email: $$('#email').val(),
                username: $$('#username').val()
            },
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    mdui.snackbar({
                        message: res.message,
                        position: 'top'
                    });
                    $$('#getCode').prop('disabled', true);
                    time_o();
                } else {
                    mdui.snackbar({
                        message: res.message,
                        position: 'top'
                    });
                }
            }
        });
    }


})

function time_o() {
    if (time == 0) {
        $$('#getCode').prop('disabled', false);
        $$('#getCode').text('获取验证码');
        time = 60;
    } else {
        $$('#getCode').text('还有 ' + time + ' 再次获取');
        time--;
        setTimeout(time_o, 1000);
    }
}

function delTopic(_tid, _uid) {
    if (_uid == null) {
        _uid = 0;
    }
    mdui.dialog({
        title: '删除主题',
        content: '确定删除本主题吗？',
        buttons: [
            {
                text: '取消'
            },
            {
                text: '确认',
                onClick: function (inst) {
                    $$.ajax({
                        method: 'GET',
                        url: '/api/api/del/type/topic/id/' + _tid + '/uid/' + _uid,
                        dataType: 'json',
                        success: function (res) {
                            if (res.code == 0) {
                                mdui.snackbar({
                                    message: res.message,
                                    position: 'top',
                                    onClosed: function () {
                                        window.history.go(-1);
                                    }
                                });
                            } else {
                                mdui.snackbar({
                                    message: res.message,
                                    position: 'top'
                                });
                            }
                        }
                    });
                }
            }
        ]
    });
}

function recomment(_id) {
    editor.txt.clear();
    var id = '#reply-' + _id;
    var data = $$(id).data();
    $$('#reply').trigger('click');
    re_cid = data.cid;
    var html = '<p>回复 <a href="/user/' + data.uid + '.html"> @' + data.username + ' </a>：<a href="#reply-content-' + data.cid + '" >#' + data.cid + '</a></p>';
    if (editor.txt.text() == null || editor.txt.text() == '') {
        editor.txt.html(html);
    } else {
        editor.txt.append(html);
    }
}