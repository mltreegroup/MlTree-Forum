// +----------------------------------------------------------------------
// | MlTreeForum [ THE BEST FORUM ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 https://mltree.top All rights reserved.
// +----------------------------------------------------------------------
// | GPL v3 ( https://choosealicense.com/licenses/gpl-3.0/ )
// +----------------------------------------------------------------------
// | Author: Kingsr <kingsrml@vip.qq.com>
// +----------------------------------------------------------------------

//返回顶部
$("#fixbar-top").click(function () {
    console.log('OK');
    $('body,html').animate({ scrollTop: 0 }, 1000);
    return false;
});

//判断是否为手机版，是则自动进入全屏模式
var device = layui.device();
if (device.weixin || device.android || device.ios) {
    enterFullScreen();
}

function enterFullScreen() {//进入全屏
    var de = document.documentElement;
    if (de.requestFullscreen) {
        de.requestFullscreen();
    } else if (de.mozRequestFullScreen) {
        de.mozRequestFullScreen();
    } else if (de.webkitRequestFullScreen) {
        de.webkitRequestFullScreen();
    }
}

function exitFullScreen() {//退出全屏
    var de = document;
    if (de.exitFullscreen) {
        de.exitFullscreen();
    } else if (de.mozCancelFullScreen) {
        de.mozCancelFullScreen();
    } else if (de.webkitCancelFullScreen) {
        de.webkitCancelFullScreen();
    }
}
