// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | GPL v3 ( https://choosealicense.com/licenses/gpl-3.0/ )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------


//注册编辑组件
var E = window.wangEditor;
var editor = new E('#editor');
var $$ = mdui.JQ;

function reContent(cid) {
    $$.ajax({
        method: 'GET',
        url: '/api/api/commentConent/cid/' + cid,
        dataType: 'json',
        success: function (res) {
            layui.open({
                type: 1,
                content: res.message,
            })
        }
    });
}

function regEditor(type = 'def', option = {}) {
    if (option.reply == true) {

    }

    if (type == 'comment') {
        editor.customConfig.menus = [
            'bold',
            'italic',
            'underline',
            'emoticon',
            'image',
        ];
    }
    //配置图片上传
    editor.customConfig.uploadImgServer = option.uploadImg;
    editor.customConfig.uploadFileName = 'file';
    editor.customConfig.uploadImgMaxLength = 1;
    editor.customConfig.customAlert = function (info) {
        layer.msg(info, { icon: 5 });
    }

    editor.customConfig.onchangeTimeout = 1000;
    editor.customConfig.onchange = function (html) {
        $$('#content').val(html);
    }
    editor.create();

    layui.use(['upload', 'jquery'], function () {
        var upload = layui.upload,
            $ = layui.$;

        var files;
        var fileJson;
        var uploadInst = upload.render({
            elem: '#file'
            , method: 'POST'
            , url: option.uploadFile
            , accept: 'file'
            , auto: false
            , bindAction: '#create'
            , data: {
                uid: option.uid,
            }
            , choose: function (obj) {
                files = obj.pushFile();
                console.log(obj);
                obj.preview(function (index, file, res) {
                    var html = '<div class="mdui-chip">';
                    html += '<span class="mdui-chip-title" >' + file.name + '</span>';
                    html += '<span class="mdui-chip-delete"><i class="mdui-icon material-icons">cancel</i></span>';
                    html += '</div>';

                    var chip = $$('#fileList').append(html);
                    chip.removeClass('mdui-hidden-xs');

                    chip.find('.mdui-chip-delete').on('click', function () {
                        delete files[index];
                        chip.remove();
                        uploadInst.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                    })
                })
            }
            , before: function (obj) {
                fileJson = JSON.stringify(obj.pushFile());
                var list = $$('#files').val(fileJson);
            }

        });
    });

    if (type == 'def') {
        $$('#create').on('click', function () {
            //获取表单内容
            var data = $$('form').serialize();
            console.log(data);

            $$.ajax({
                method: 'post',
                url: createUrl,
                data: data,
                dataType: 'json',
                success: function (res) {
                    if (res.code == 1) {
                        mdui.snackbar({
                            message: res.message,
                            position: 'top',
                            onClosed: function () {
                                window.location.href = res.url;
                            }
                        })
                    } else {
                        mdui.snackbar({
                            message: '阿偶？出错了，请重试！\n' + res.message,
                            position: 'top',
                        })
                    }
                }
            });
            return false;
        });
    } else if (type == 'comment') {
        layui.use(['layer'], function () {
            var layer = layui.layer,
                $ = layui.jquery;

            $$('#reply').on('click', function () {
                $$('#replyPanel').toggleClass('mdui-hidden');
                var device = layui.device(),
                    k = '824px';
                if (device.weixin || device.android || device.ios) {
                    k = '100%';
                }
                layer.open({
                    type: 1,
                    anim: 2,
                    title: '回复『' + option.subject + '』',
                    area: k,
                    offset: 'b',
                    btn: '发布',
                    content: $('#replyPanel'),
                    cancel: function (index, layero) {
                        $$('#replyPanel').toggleClass('mdui-hidden');
                    },
                    yes: function (index, layero) {
                        var data = $$('#replyPanel').serialize();
                        layer.close(index);
                        $$.ajax({
                            method: 'post',
                            url: option.commentUrl,
                            data: data,
                            dataType: 'json',
                            success: function (res) {
                                console.log(res);
                                if (res.code == 1) {
                                    mdui.snackbar({
                                        message: res.message,
                                        position: 'top',
                                        onClosed: function () {

                                            location.reload();
                                        }
                                    })
                                } else {
                                    mdui.snackbar({
                                        message: '阿偶？出错了。<br/>' + res.message,
                                        position: 'top',
                                    })
                                }
                            }
                        });
                    }
                });
            });

            layer.photos({
                photos: '#mf-content,#mf-comments'
                , anim: 5
            });
        })
    }
}

function setContent(content = '') {
    var html = '回复 <a href="javascript:;" onmouseover="reContent(' + option.cid + ')" onmouseout="layer.closeAll();">@' + option.username + '</a>';
    if (content == '') {
        content = html;
    }
    editor.txt.html(content);
}