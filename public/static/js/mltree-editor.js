// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | Apache License v2 ( https://www.apache.org/licenses/LICENSE-2.0.html )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------


//注册编辑组件
var E = window.wangEditor;
var editor = new E('#editor');
var $$ = mdui.JQ;

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
            'link',
        ];
    }
    //配置图片上传
    editor.customConfig.debug = true;
    editor.customConfig.uploadImgServer = option.uploadImg;
    editor.customConfig.uploadFileName = 'file';
    editor.customConfig.uploadImgMaxLength = 1;
    editor.customConfig.customAlert = function (info) {
        layer.msg(info, {
            icon: 5
        });
    }

    editor.customConfig.onchangeTimeout = 1000;
    editor.customConfig.onchange = function (html) {
        $$('#content').val(html);
    }
    editor.create();
}

layui.use(['upload', 'jquery'], function () {
    var upload = layui.upload,
        $ = layui.$;

    var files;

    var uploadInst = upload.render({
        elem: '#file',
        method: 'POST',
        url: option.uploadFile,
        accept: 'file',
        auto: false,
        bindAction: '#create',
        data: {
            uid: option.uid,
            sign: option.sign,
        },
        choose: function (obj) {
            files = obj.pushFile();
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
        },

    });

    layer.photos({
        photos: '#mf-content,#mf-comments',
        anim: 5
    });
});

layui.use(['layer'], function () {
    var layer = layui.layer;

    $$('#reply').on('click', function () {
        $$('#replyPanel').toggleClass('mdui-hidden');
        $$('#editor').toggleClass('mdui-hidden');
        document.getElementById(editor.textElemId).focus();

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
                $$('#editor').toggleClass('mdui-hidden');
            },
            yes: function (index, layero) {
                var data = $$('#replyPanel').serialize();
                data.reCid = re_cid;
                $$('#editor').toggleClass('mdui-hidden');
                $$('#replyPanel').toggleClass('mdui-hidden');
                editor.txt.clear()
                layer.close(index);
                $$.ajax({
                    method: 'post',
                    url: option.commentUrl,
                    data: data,
                    dataType: 'json',
                    success: function (res) {
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
                                message: res.message,
                                position: 'top',
                            })
                        }
                    }
                });
            }
        });
    });
})

$$('#create').on('click', function () {
    //获取表单内容
    var formdata = $$('form').serialize();
    
    $$.ajax({
        method: 'post',
        url: option.createUrl,
        data: formdata,
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
                    message: res.message,
                    position: 'top',
                    onClosed: function () {
                        location.reload();
                    }
                })
            }
        }
    });
    return false;
});