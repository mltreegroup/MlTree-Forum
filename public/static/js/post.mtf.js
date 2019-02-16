class mtfPost {
    constructor(option = {}) {
        this.topicListUrl = option.topicListUrl;
        this.commentUrl = option.commentUrl;
        this.topicPage = 1;
        this.topicPages = 1;
        this.commentPage = 1;
        this.commentPages = 1;
    }

    getTopicList(dom, fid, type = "common") {
        var url = this.topicListUrl,
            topicPage = this.topicPage,
            topicPages = this.topicPages,
            status = false;

        function getData() {
            status = false;
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    fid: fid,
                    type: type,
                    page: topicPage,
                },
                dataType: 'json',
                success: (res) => {
                    topicPages = res.data.pages;
                    insertList(res, type);
                },
            })
        };

        function insertList(list, type) {
            var html = '';
            $(dom).find('div.mdui-progress').remove();
            if (topicPage > topicPages) {
                return;
            } else {
                mdui.JQ.each(list.data.data, (i, val) => {
                    html = $('<li class="mdui-list-item mdui-ripple"></li>')
                        .attr('data-tid', val.tid)
                        .on('click', function () {
                            window.location.href = `/Topic/${$$.data(this).tid}.html`;
                        })
                        .append(
                            $('<div class="mdui-list-item-avatar"></div>')
                            .append(
                                $('<img />')
                                .attr('src', val.userData.avatar)
                                .attr('alt', val.userData.username)
                            )
                        )
                        .append(
                            $('<div class="mdui-list-item-content"></div>')
                            .append(
                                $('<a class="mdui-list-item-title"></a>').attr('href', `/Topic/${val.tid}.html`).text(val.subject)
                            )
                            .append(
                                $('<div class="mdui-list-item-text mdui-list-item-one-line"></div>')
                                .append(
                                    $('<span class="mdui-text-color-theme-text"></span>').text(val.userData.username)
                                )
                                .append(` · ${val.create_time} · ${val.comment}回复 · ${val.views}阅读`)
                            )
                        );
                    $(dom).append(html).append('<li class="mdui-divider-inset mdui-m-y-0"></li>');
                });
                topicPage += 1;
            }
            status = true;
            return;
        }

        getData();

        //获取滚动条当前的位置
        function getScrollTop() {
            var scrollTop = 0;
            if (document.documentElement && document.documentElement.scrollTop) {
                scrollTop = document.documentElement.scrollTop;
            } else if (document.body) {
                scrollTop = document.body.scrollTop;
            }
            return scrollTop;
        }

        //获取当前可视范围的高度
        function getClientHeight() {
            var clientHeight = 0;
            if (document.body.clientHeight && document.documentElement.clientHeight) {
                clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight);
            } else {
                clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight);
            }
            return clientHeight;
        }

        //获取文档完整的高度
        function getScrollHeight() {
            return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        }

        $(window).scroll(() => {
            if (getScrollTop() + getClientHeight() + 50 > getScrollHeight() && status) {
                getData();
            }
        })
    }

    getCommentList(tid) {
        var url = this.commentUrl,
            commentPage = this.commentPage,
            commentPages = this.commentPages,
            status = false;

        function getData() {
            status = false;
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    tid: tid,
                    page: commentPage,
                },
                dataType: 'json',
                success: (res) => {
                    commentPages = res.data.pages;
                    insertList(res);
                },
            })
        };

        function insertList(data) {
            var html = '';
            $('.replies').find('div.mdui-progress').remove();
            if (commentPage > commentPages && $$('#replies').find('.end').length == 0) {
                //$('#commentList').append('<div class="end">没有更多了</div>');
                return;
            } else {
                mdui.JQ.each(data.data.data, (i, val) => {
                    html = $('<div class="mdui-card"></div>')
                        .append(
                            $('<div class="mdui-card-primary"></div>').append($('<date class="mdui-primary-subtitle mdui-float-right"></date>').text(val.create_time))
                        )
                        .append(
                            $('<header class="mdui-card-header""></header>')
                            .attr('id', `reply-content-${val.cid}`)
                            .append('<img class="mdui-card-header-avatar" />').attr('src', val.avatar).attr('src', val.username)
                            .append($('<div class="mdui-card-header-title"></div>').text(val.username))
                            .append($('<div class="mdui-card-header-subtitle"></div>').text(val.motto))
                        )
                        .append(
                            $('<main class="mdui-card-content mdui-typo"></main>').html(marked(val.content))
                        )
                        .append(
                            $('<footer class="mdui-card-actions"></footer>')
                            .append(
                                $('<button class="mdui-btn mdui-btn-icon mdui-ripple"><i class="mdui-icon material-icons">reply</i></button>')
                                .attr('data-cid', val.cid)
                                .attr('data-uid', val.uid)
                                .attr('data-username', val.username)
                                .on('click', function () {
                                    $$('.reply').trigger('click');
                                    $$('input[name="title"]').val(`回复至 ${val.username}`);
                                    $$('#mtf-reply-content').val(`{@${val.uid}/${val.cid}}`);
                                    mdui.mutation(); //刷新一下页面的组件值
                                })
                            )
                        );
                    $('#replies').append(html).append('<div class="mainstream-division"></div>');

                });
                commentPage += 1;
            }
            status = true;
        };

        getData();

        //获取滚动条当前的位置
        function getScrollTop() {
            var scrollTop = 0;
            if (document.documentElement && document.documentElement.scrollTop) {
                scrollTop = document.documentElement.scrollTop;
            } else if (document.body) {
                scrollTop = document.body.scrollTop;
            }
            return scrollTop;
        }

        //获取当前可视范围的高度
        function getClientHeight() {
            var clientHeight = 0;
            if (document.body.clientHeight && document.documentElement.clientHeight) {
                clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight);
            } else {
                clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight);
            }
            return clientHeight;
        }

        //获取文档完整的高度
        function getScrollHeight() {
            return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight);
        }

        $(window).scroll(() => {
            if (getScrollTop() + getClientHeight() + 50 > getScrollHeight() && status) {
                getData();
            }
        })
    }

    Register(url = null) {
        url == null ? url = '' : url;
        var data = $$('form').serialize();
        $$.ajax({
            method: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                        onClosed: () => {
                            location.href = res.url;
                        }
                    });
                } else {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                    });
                    $$('#code').trigger('click');
                }
            },
        })
    }

    Login(url = null) {
        url == null ? url = '' : url;
        var data = $$('form').serialize();
        $$.ajax({
            method: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                        onClosed: () => {
                            location.href = res.url;
                        }
                    });
                } else {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                        buttonText: '再次获取激活邮件',
                        onButtonClick: function () {
                            let mail = main.toBase64($$('#email').val());
                            location.href = `/User/ReActive/${mail}.html`;
                        },
                    });
                    $$('#code').trigger('click');
                }
            }
        });
    }

    ForgetPwd(url = null) {
        url == null ? url = '' : url;
        var data = $$('form').serialize();
        $$.ajax({
            method: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                        onClosed: function () {
                            location.href = res.url;
                        }
                    })
                } else {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                    });
                }
            }
        })
    }

    /**
     * 获取找回密码邮件验证码
     * @param {string} dom 按钮标识
     */
    getForgetCode(dom) {
        let time = 60;
        $$(dom).on('click', function () {
            $$.ajax({
                method: 'GET',
                url: '/forum/expand/getForgetMailCode.html',
                data: {
                    mail: $$('input[name="email"]').val(),
                },
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        setTime();
                        $$('.mtf-newpwd-div').removeClass('mdui-hidden');
                        mdui.snackbar({
                            message: res.msg,
                            position: 'top',
                        });
                    } else {
                        mdui.snackbar({
                            message: res.msg,
                            position: 'top',
                        });
                    }
                }
            })
        });

        function setTime() {
            var timer = setTimeout(() => {
                if (time > 0) {
                    $$(dom).prop('disabled', true);
                    $$(dom).text('请' + time + '秒后再获取');
                    time--;
                    setTime();
                } else {
                    $$(dom).prop('disabled', false);
                    $$(dom).text('获取验证码');
                    time = 60;
                }
            }, 1000);
        }
    }
    /**
     * Rewrited function to request email verification code to recover password.
     */
    sendPasswordRecoveryCode(email, onSent) {
        $$.ajax({
            method: 'GET',
            url: '/forum/expand/getForgetMailCode.html', //Declaration: This incorrect spell isn't made by me.
            data: {
                mail: email //Mail===the real mail.
            },
            dataType: 'json',
            complete: onSent //Whatever succeed or fail, just call the function.
        });
    }

    createTopic() {
        /*上传方法*/
        $$("form").on('submit', () => {
            var data = $$('form').serialize();
            //console.debug(data);//Not applicable to Chrome.
            $$.ajax({
                method: 'POST',
                url: '',
                data: data,
                dataType: 'json',
                success: function (res) {
                    console.table(res);
                    if (res.code == 0) {
                        mdui.snackbar({
                            message: res.msg,
                            position: 'top'
                        });
                    } else {
                        mdui.snackbar({
                            message: res.msg,
                            position: 'top',
                            onClosed: () => {
                                location.reload();
                            }
                        })
                    }
                }
            });
            return false;
        })


        //return simplemde;
    }

    postComment(tid) {
        /**
         * 回复框载入
         */
        $$('.reply').on('click', function () {
                let data = $$(this).data();
                if (document.querySelector('.bottom-dialog')) return $$('.bottom-dialog').addClass('expand'), $$('#mtf-reply-content').val(''), $$('input[name="title"]').val(`回复至 ${data.subject}`); //Prevent duplicated dialog.
            let replyBox = $$('<div class="bottom-dialog mdui-shadow-2"></div>')
                .append(
                    $$('<header></header>')
                    .append(
                        $$('<div class="mdui-textfield mdui-col-xs-10"></div>')
                        .append($$('<input name="title" class="mdui-textfield-input" disabled />').val(`回复至 ${data.subject}`))
                    )
                    .append(
                        $$('<button class="mdui-btn mdui-btn-icon mdui-ripple mdui-float-right" id="dialog-close"></button>')
                        .on('click', function () {
                            $$('.bottom-dialog').toggleClass('expand')
                        })
                        .append($$('<i class="mdui-icon material-icons">add</i>'))
                    )
                )
                .append(
                    $$('<main></main>')
                    .append(
                        $$('<div class="mdui-textfield mdui-textfield-floating-label"></div>')
                        .append($$('<label class="mdui-textfield-label">内容</label>'))
                        .append($$('<textarea class="mdui-textfield-input" rows="5" id="mtf-reply-content"></textarea>'))
                    )
                )
                .append(
                    $$('<footer></footer>')
                    .append($$('<button class="mdui-btn mdui-text-color-theme-accent mdui-float-right mdui-ripple mtf-post-comment">回复</button>'))
                )
            $$('body').append(replyBox); setTimeout(function () {
                replyBox.addClass('expand-half expand')
            }, 10); $$('.mtf-post-comment').on('click', function () {
                var content = $$('#mtf-reply-content').val();
                post(content);
            });
        });

    function post(content) {
        $$.ajax({
            method: 'POST',
            url: '/Api/postComment.html',
            data: {
                content: content,
                tid: tid,
                time: time(),
            },
            dataType: 'json',
            complete: xhr => {
                if (xhr.status !== 200) return mdui.snackbar('Unknown error has occurred. Check your internet connection or contact the developer to solve.');
                let res = JSON.parse(xhr.responseText);
                if (res.code == 0) {
                    $$('.bottom-dialog').remove(); //Destruct dialog.
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top'
                    });
                } else {
                    mdui.snackbar({
                        message: res.msg,
                        position: 'top',
                    });
                }
            }
        })
    }


}
}