// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | Apache License v2 ( https://www.apache.org/licenses/LICENSE-2.0.html )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------

//注册一个全局变量

class MLTeditor {
    constructor(option) {
        this.option = option;
        this.Meditor = new SimpleMDE({
            element: document.getElementById("editor"),
            spellChecker: false,
            autofocus: true,
            autosave: {
                enabled: true,
                uniqueId: "MLTFEditor",
                delay: 1000,
            },
            placeholder: "请开始你的创作吧~",
            prompturls: true,
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
        })
    }

    submitData(dataObj, callback) {
        $$.ajax({
            method: 'post',
            url: option.createUrl,
            data: dataObj,
            dataType: 'json',
            success: function(res) {
                callback(res);
            }
        });
    }

    setValue(content = '') {
        this.Meditor.value(content)
    }

    getValue() {
        return this.Meditor.value();
    }

    clearValue() {
        this.setValue();
    }
}

//绑定上传附件事件
$$('#file').on('click', () => {
    mdui.dialog({
        content: "请注册账号，然后将附件上传至网盘后，再将网址粘贴过来。谢谢。<br>推荐MLT网盘、天翼云盘",
        buttons: [{
                text: 'MLT网盘',
                close: true,
                onClick: function() {
                    window.open('https://pan.kingsr.cc')
                }
            },
            {
                text: '百度网盘',
                close: true,
                onClick: function() {
                    window.open('https://pan.baidu.com')
                }
            },
            {
                text: '天翼云盘',
                close: true,
                onClick: function() {
                    window.open('https://cloud.189.cn/')
                }
            },
        ]
    })
});