// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | Apache License v2 ( https://www.apache.org/licenses/LICENSE-2.0.html )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------
function getAjax(url, data, callback) {
    $$.ajax({
        method: 'POST',
        url: url,
        data: data,
        dataType: 'json',
        success: function (res) {
            callback(res)
        }
    });
}

class MfMessage {
    constructor(option) {
        this.url = option.url;
        this.addUrl = option.addUrl;
        this.readUrl = option.readUrl;
        this.delUrl = option.delUrl;
        this.uid = option.uid;
    }

    MessageList(toUid, status = 2) {
        getAjax(this.url, { uid: toUid, status: status }, function (res) {
            return res;
        })
    }

    addMessage(uid, content, callback = null) {
        var data = null;
        $$.ajax({
            method: 'POST',
            url: this.addUrl,
            data: {
                content: content,
                toUid: uid,
            },
            dataType: 'json',
            success: function (res) {
                callback(res);
            }
        });
    }

    readMessage(mid, callback = null) {
        $$.ajax({
            method: 'POST',
            url: this.readUrl,
            data: {
                mid: mid,
            },
            dataType: 'json',
            success: function (res) {
                callback(res);
            }
        });
    }

    delMessage(mid, uid = null, callback = null) {
        $$.ajax({
            method: 'POST',
            url: this.delUrl,
            data: {
                mid: mid,
                uid: uid,
            },
            dataType: 'json',
            success: function (res) {
                callback(res);
            }
        });
    }

    createHtml(content) {
        var html = '';
        for (let value of content.data) {
            let status = '';
            if (value['status'] == 0) {
                status = '<span class="layui-badge">未读</span>';
            } else {
                status = '<span class="layui-badge layui-bg-blue">已读</span>';
            }
            html += `<li class="mdui-list-item mdui-ripple">
                    <div class="mdui-list-item-avatar"><img src="${value['avatar']}" alt="${value['userName']}"/></div>
                    <div class="mdui-list-item-content">
                    <div class="mdui-list-item-title"><a href="javascript:;" onclick="msgclick(${value['mid']})">${value['title']}</a>${status}</div>
                    <div class="mdui-list-item-text mdui-list-item-one-line"><span class="mdui-text-color-theme-text">${value['content']}</div>
                    </div>
                    </li>
                    <li class="mdui-divider-inset mdui-m-y-0"></li>`
        }
        return '<ul class="mdui-list">' + html + '</ul><a href="/index/user/Message.html" class="mdui-btn">查看全部消息</a><buttom id="msg-readeAll" class="mdui-btn mdui-color-theme" onclick="readAll()">全部设为已读</buttom>';
    }
}

var msg = new MfMessage(msgoption);

$('#mf-msg').webuiPopover({
    placement: 'bottom',
    title: '消息列表',
    type: 'async',
    trigger: 'hover',
    url: msgoption.url,
    content: function (data) {
        return msg.createHtml(data);
    },
    closeable: false,
    padding: false,
    arrow: true,
    cache: false,
});

function readAll() {
    msg.readMessage('all');
    location.reload();
}

function msgclick(mid) {
    layui.use('layer', function () {
        var layer = layui.layer;
        $$.ajax({
            method: 'POST',
            url: msgoption.get,
            data: {
                mid: mid,
            },
            dataType: 'json',
            success: function (res) {
                msg.readMessage(mid);
                layer.open({
                    title: res.message.title,
                    content: `<div class="mdui-typo mdui-text-color-black mdui-color-white"><p>${res.message.content}</p><span>${res.message.time}</span></div>`,
                    closeBtn: 2,
                    shade: 0,
                    anim: 5,
                    btn: ['删除', '关闭'],
                    yes: function (index, layero) {
                        msg.delMessage(mid);
                        $$(this).remove();
                        layer.close(index);
                    },
                    btn2: function (index, layero) {
                        layer.close(index);
                    }
                })
            }
        });
    })
}