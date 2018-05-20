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
        $$('#getCode').toggleClass('disabled');
        $$('#getCode').val('获取验证码');
        time = 60;
    } else {
        $$('#getCode').val('还有 ' + time + ' 再次获取');
        time--;
        setTimeout(() => {
            time_0
        }, 100);
    }
}