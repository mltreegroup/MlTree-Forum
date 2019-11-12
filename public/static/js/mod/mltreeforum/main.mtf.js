// 暴露一个mduiJQ变量供全局使用
var $$ = mdui.JQ;
mdui.mutation()

// 定义Vue函数
// 2019/02/08 暂时移除Vue
// var mtfMenu = new Vue({
//     el: '#app-menu',
//     data: {

//     }
// });

// var mtfContent = new Vue({
//     el: '#app-content',
//     data: {
//         msg: 'Hello MTF!'
//     }
// })

// 定义mltMain主函数类
class mtfMain {
    constructor() {
        this.isShare = false;
    }

    insertMenu(option) {
        option.menuType == null ? option.menuType = 'Forum' : option.menuType = '{$topicData.subject}';
        var menu = `<div class="mdui-toolbar mdui-color-theme">
        <a href="javascript:;" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">menu</i></a>
        <a href="javascript:;" class="mdui-typo-headline mdui-hidden-xs">{$site.siteTitle}</a>
        <a href="javascript:;" class="mdui-typo-title">${option.menuType}</a>
        <div class="mdui-toolbar-spacer"></div>
        <a href="javascript:;" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">search</i></a>
        <a href="javascript:;" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">more_vert</i></a>
        </div>`;
    }

    /**
     * 分享菜单创建
     * @param {dom} dom 
     * @param {object} data 
     */
    shareTopic(dom = '#share', data) {
        var Url = {
            qq: `https://connect.qq.com/widget/shareqq/index.html?url=${data.url}&title=${data.subject}&source=${data.siteTitle} by MlTreeForum&summary=${data.content}`,
            weibo: `http://service.weibo.com/share/share.php?url=${data.url}&sharesource=weibo&title=${data.subject}&pic=${data.picurl}&appkey=`,
        };
        if (!this.isShare) {
            var html = `<ul class="mdui-menu mtf-share" id="mlt_share" style="z-index:200508">
            <li class="mdui-menu-item">
                <a href="${Url.qq}" class="mdui-ripple" id="share_qq"><i class="mdui-text-color-blue mdui-menu-item-icon mdui-icon iconfont icon-qq"></i> QQ分享</a>
            </li>
            <li class="mdui-menu-item">
                <a href="javascript:;" class="mdui-ripple" id="share_wechat" mdui-dialog="{target: '#wechat-panel'}"><i class="mdui-text-color-green mdui-menu-item-icon mdui-icon iconfont icon-wechat"></i> 微信分享</a>
            </li>
            <li class="mdui-menu-item">
                <a href="${Url.weibo}" class="mdui-rippleo" id="share_weibo"><i class="mdui-text-color-red mdui-menu-item-icon mdui-icon iconfont icon-weibo"></i> 微博分享</a>
            </li>
            </ul>`;
            var wechat_panel = `
            <div class="mdui-dialog" id="wechat-panel">
            <div class="mdui-dialog-content" style="text-align:center">
                <div class="mdui-dialog-title">分享到微信</div>
                <div id="wechat-qrcode" class="mdui-center" style="width:260px"></div>
                <div>
                    <p>微信扫一扫，点击页面右上角进行分享</p>
                </div>
            </div>
            </div>
            `;
            $$(dom).after(html);
            $$('body').after(wechat_panel);
            new QRCode(document.getElementById("wechat-qrcode"), data.url);
            this.isShare = true;
        };
        var inst = new mdui.Menu(dom, '#mlt_share', {
            position: 'top',
            history: false,
        });
        inst.open();
    }

    /**
     * 管理主题
     */
    managementTopic(dom) {

    }

    /**
     * Base64编码
     * @param {str} str 
     */
    toBase64(str) {
        return window.btoa(unescape(encodeURIComponent(str)));
    }

    /**
     * Base64解码
     * @param {str} base64 
     */
    Base64toStr(base64) {
        return decodeURIComponent(escape(window.atob(base64)))
    }

    /**
     * 要上传的图片数据
     * @param {file} data 
     */
    uploadPictrue(data) {

    }

    /**
     * 在调用时将topbar的menu替换为back
     * @param {int} size 显示隐藏分界的大小,数字标识
     * @param {bool} Greedy 是否向上隐藏
     */
    arrowBack(size = 0, Greedy = false) {
        let sizearray = ['xs', 'sm', 'md', 'lg', 'xl'];
        $$('#mtf-menu-back').removeClass('mdui-hidden');
        if (Greedy) {
            $$('#d-drawer-open').addClass('mdui-hidden-' + sizearray[size] + '-down');
            $$('#mtf-menu-back').addClass('mdui-hidden-' + sizearray[size + 1] + "-up");
        } else {
            $$('#d-drawer-open').addClass('mdui-hidden-' + sizearray[size]);
            $$('#mtf-menu-back').addClass('mdui-hidden-' + sizearray[size]);
        }
    }
}


/**
 *  -----------------------------------------
 * |    基础JS部分，用于操控页面的大部分功能    
 *  -----------------------------------------
 * |    作者：MlTree(Kingsr)                  
 *  -----------------------------------------
 * |    DateTime：2018.12.14                 
 *  -----------------------------------------
 */

/**
 * 添加一个跳转方法用于定义跳转
 */
$$('.mlt-Jump').on('click', function () {
    let data = $$.data(this);
    location.href = `/Topic/${data.tid}.html`;
});


/**
 * 获取时间戳
 */
function time() {
    return Math.round(new Date().getTime() / 1000);
}

/**
 * 刷新页面Token值
 */
function createToken() {
    $$.ajax({
        method: 'GET',
        url: '',
        data: data,
        dataType: 'json',
        success: function (res) {
            $$('input[name="__token__"]').val(res.token);
        }
    });
}

/**
 * 页面跳转函数
 */
$$('.mtf-open').on('click', function () {
    this.style.cursor = 'pointer';
    let data = $$(this).data();
    window.open(data.url, data.target);
});

window.addEventListener('scroll', function () {
    if (window.scrollY < 240 - $$('#app-menu').offset().height) {
        $$('#app-menu').addClass('mdui-shadow-0');
        $$('#app-menu>.mdui-toolbar').removeClass('mdui-color-theme');
        $$('.mdui-fab').removeClass('top-scrolled');
    } else {
        $$('#app-menu').removeClass('mdui-shadow-0');
        $$('#app-menu>.mdui-toolbar').addClass('mdui-color-theme');
        $$('.mdui-fab').addClass('top-scrolled');
    }
});