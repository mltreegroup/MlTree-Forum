// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | GPL v3 ( https://choosealicense.com/licenses/gpl-3.0/ )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------

function mfFlow(type) {
    this.type = type;
    this.flow = function (tid = 0) {
        if (type == 'index') {
            layui.use(['flow'], function () {
                var flow = layui.flow;
                var $ = layui.jquery;
                flow.load({
                    elem: '#topic-cps',
                    done: function (page, next) {
                        var list = [];
                        $.get('/index/forum/api/page/' + page, function (res) {
                            layui.each(res.data, function (index, item) {
                                var html = '<li class="mdui-list-item mdui-ripple"><div class="mdui-list-item-avatar">';
                                html += '<img src="' + item.userData.avatar + '" alt="' + item.userData.username + '" title="' + item.userData.username + '">'
                                html += '</div><div class="mdui-list-item-content">'
                                html += '<a class="mdui-list-item-title" href="/index/topic/index/tid/' + item.tid + '">' + item.subject + item.Badge + '</a>'
                                html += '<div class="mdui-list-item-text mdui-list-item-one-line">' + item.content + '</div>'
                                html += '<div class="mdui-list-item-text">'
                                html += '<a href="/index/user/index/uid/' + item.uid + '">' + item.userData.username + '</a>'
                                html += '<span title="' + item.create_time + '">   ' + item.time_format + '</span>'
                                html += '<span class="mdui-float-right" >'
                                html += '<i class="mdui-icon material-icons">looks</i>' + item.views + '</span>'
                                html += '<span class="mdui-float-right">'
                                html += '<i class="mdui-icon material-icons">comment</i>' + item.comment + '</span>'
                                html += '</div></div></li><li class="mdui-divider-inset mdui-m-y-0"></li>'
                                list.push(html);
                            })
                            next(list.join(''), page < res.pages);
                        })
                    }
                })

                flow.load({
                    elem: '#topic-ess',
                    done: function (page, next) {
                        var list = [];
                        $.get('/index/forum/api/page/' + page + '/t/2', function (res) {
                            layui.each(res.data, function (index, item) {
                                var html = '<li class="mdui-list-item mdui-ripple"><div class="mdui-list-item-avatar">';
                                html += '<img src="' + item.userData.avatar + '" alt="' + item.userData.username + '" title="' + item.userData.username + '">'
                                html += '</div><div class="mdui-list-item-content">'
                                html += '<a class="mdui-list-item-title" href="/index/topic/index/tid/' + item.tid + '">' + item.subject + item.Badge + '</a>'
                                html += '<div class="mdui-list-item-text mdui-list-item-one-line">' + item.content + '</div>'
                                html += '<div class="mdui-list-item-text">'
                                html += '<a class="mdui-list-item-title" href="/index/user/index/uid/' + item.uid + '">' + item.userData.username + '</a>'
                                html += '<span title="' + item.create_time + '">   ' + item.time_format + '</span>'
                                html += '<span class="mdui-float-right" >'
                                html += '<i class="mdui-icon material-icons">looks</i>' + item.views + '</span>'
                                html += '<span class="mdui-float-right">'
                                html += '<i class="mdui-icon material-icons">comment</i>' + item.comment + '</span>'
                                html += '</div></div></li><li class="mdui-divider-inset mdui-m-y-0"></li>'
                                list.push(html);
                            })
                            next(list.join(''), page < res.pages);
                        })
                    }
                })
            })
        } else if (type == 'comment') {

            layui.use(['flow'], function () {
                var flow = layui.flow;

                flow.load({
                    elem: '#mf-comments',
                    done: function (page, next) {
                        var list = [];

                        $.get('/api/api/commentList/tid/' + tid + '/type/comment/page/' + page, function (res) {
                            layui.each(res.data, function (index, item) {
                                var html = '<div class="mdui-row mf-panel"><div class="mf-panel-hd">';
                                html += '<a href="/index/user/index/uid/' + item.uid + '" class="mdui-float-right" title="' + item.username + '">'
                                html += '<img src="' + item.avatar + '" alt="' + item.username + '" class="mdui-img-circle" width="32">'
                                html += '</a><header>'
                                html += '<a href="/index/user/index/uid/' + item.uid + '">' + item.username + '</a>评论于'
                                html += '<span title="' + item.create_time + '" >' + item.time_format + '</span>'
                                html += '</header></div><div class="mf-panel-bd">' + item.content
                                html += '</div><footer class="mf-panel-footer"><div class="layui-btn-group"><button class="layui-btn layui-btn-xs"><i class="layui-icon">&#xe6c6;</i></button><button class="layui-btn layui-btn-xs mf-btn-reply" data-cid="' + item.cid + '" data-username="' + item.username + '"><i class="mdui-icon material-icons">comment</i></button></div></footer></div>'
                                list.push(html);
                            })
                            next(list.join(''), page <= res.pages);
                        })
                    }
                })
            })
        }

    }
    return this;
}