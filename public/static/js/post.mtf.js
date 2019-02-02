class mtfPost {
    constructor(option = {}) {
        this.topicListUrl = option.topicListUrl;
        this.commentUrl = option.commentUrl;
        this.topicPage = 0;
        this.topicPages = 0;
        this.commentPage = 0;
        this.commentPages = 0;
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
            if (topicPage == topicPages) {
                if ($$('#tops').find('.end').length == 0 && type == 'tops') {
                    $('#tops').append('<div class="end">没有更多了</div>');
                }
                if ($$('#topicList').find('.end').length == 0 && type == 'common') {
                    $('#topicList').append('<div class="end">没有更多了</div>');
                }

                return;
            } else {
                mdui.JQ.each(list.data.data, (i, val) => {
                    html = `<div class="mtf-topic mdui-ripple mtf-Jump" data-tid="${val.tid}">
                                <div class="mtf-topic-avatar">
                                    <img src="${val.userData.avatar}"
                                        alt="${val.userData.username}">
                                </div>
                                <div class="mtf-topic-info">
                                    <div class="title">
                                    ${val.Badge} <a href="/Topic/${val.tid}.html">${val.subject.substr(0, 50)}</a>
                                    </div>
                                    <div class="connent mdui-list-item-one-line">${val.content}</div>
                                    <div class="info"><a href="/Member/${val.uid}.html" class="user">${val.userData.username}</a> 发表于 <a class="time">${val.create_time}</a>
                                        <span>${val.comment}个回复</span>
                                        <span>${val.views}次查看</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mdui-divider"></div>`;
                    $(dom).append(html);
                    console.log(val.Badge);
                });
                topicPage += 1;
                /**
                 * 添加一个跳转方法用于定义跳转
                 */
                $('.mtf-Jump').on('click', function () {
                    let data = $$.data(this);
                    location.href = `/Topic/${data.tid}.html`;
                })
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
            if (commentPage == commentPages && $$('#commentList').find('.end').length == 0) {
                $('#commentList').append('<div class="end">没有更多了</div>');
                return;
            } else {
                mdui.JQ.each(data.data.data, (i, val) => {
                    html = `<div class="mdui-card mtf-comment mdui-ripple" id="mtf-commentid-${val.cid}">
                    <div class="mtf-comment-info">
                        <div class="mtf-userinfo">
                            <div class="mtf-userinfo-avatar">
                                <img src="${val.avatar}" alt="${val.username}"
                                    class="avatar mdui-img-rounded">
                            </div>
                            <div class="mtf-userinfo-line">
                                <div class="mtf-comment-name">${val.username}</div>
                                <div class="mtf-comment-motto">${val.motto}</div>
                            </div>
                            <div style="float:right;font-size: 12px;color:#646464">${val.create_time}</div>
                        </div>
    
                        <div class="mtf-comment-content mdui-typo">
                            ${val.content}
                        </div>
                        <div class="mtf-comment-footer">
                            <div class="mtf-comment-muen">
                                <button title="Reply" data-cid="18" data-username="${val.username}" data-uid="8" class="mdui-btn mdui-btn-dense mdui-color-theme mdui-ripple Reply-comment"><i
                                        class="mdui-icon material-icons">reply</i> 回复</button>
                            </div>
                        </div>
                    </div>
                </div>`;
                    $('#commentList').append(html);

                });
                commentPage += 1;
            }
            status = true;

            $$('.Reply-comment').on('click', function () {
                
            });
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
        });

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
                console.table(res);
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
        return false
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
            let sign;
            $$.ajax({
                method: 'GET',
                url: '/forum/expand/getFotgetMailCode.html',
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

    createTopic(type) {
        var simplemde = new SimpleMDE({
            autosave: {
                enabled: true,
                uniqueId: "MlTreeForum" + type,
                delay: 1000,
            },
            spellChecker: false,
            forceSync: true,
            placeholder: '开始你的创作吧',
        });

        if ($$('#editor').val() !== null && type == 'Editor') {
            let data = $$('#editor').val();
            simplemde.value('');
            simplemde.value(data);
        }
        /*上传方法*/
        $$("#post").on('click', () => {
            var data = $$('form').serialize();
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
                            position: 'top',
                            onClosed: function () {
                                simplemde.value('');
                                location.href = res.url;
                            }
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


        return simplemde;
    }

    postComment(tid) {
        /**
         * 回复框载入
         */
        $$('.reply').on('click', function () {
            let data = $$(this).data();
            let html = `
    <div class="mdui-dialog" id="replypanel">
    <div class="mdui-dialog-title">回复【${data.subject}】</div>
    <div class="mdui-dialog-content">
    <textarea name="content" id="mtf-comment-content" class="textarea-fixed" rows="10" cols="50"></textarea>
    </div>
    <div class="mdui-dialog-actions">
    <button class="mdui-btn mdui-ripple mtf-post-comment">评论</button>
    </div>
    </div>
    `
            $$(this).after(html);
            var inst = new mdui.Dialog('#replypanel', {
                history: false,
            });
            inst.toggle();

            $$('.mtf-post-comment').on('click', function () {
                var content = $$('#mtf-comment-content').val();
                post(content);
            })
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
                success: function (res) {
                    if (res.code == 0) {
                        mdui.snackbar({
                            message: res.msg,
                            position: 'top',
                            onClosed: function () {
                                location.href = res.url;
                            }
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

    setTopic(tid, subject) {
        var html = `<form id="setForm">
        <div class="mtf-block">
            <label class="mdui-textfield-label">欲操作项目(二次操作为取消)</label>
            <select name="type" class="mdui-select" mdui-select>
                <option value="top">置顶</option>
                <option value="essence">精华</option>
                <option value="closed">关闭</option>
            </select>
        </div>
        <div class="mtf-block">
            <label class="mdui-checkbox">
                <input name="msg" type="checkbox" />
                <i class="mdui-checkbox-icon"></i>
                是否通知作者
            </label>
        </div>
        <input type="hidden" name="tid" value="${tid}">
    </form>`;

        mdui.dialog({
            title: `设置帖子【${subject}】`,
            content: html,
            buttons: [{
                    text: '取消'
                },
                {
                    text: '确认',
                    onClick: function (inst) {
                        let data = $$('#setForm').serialize();
                        $$.ajax({
                            method: 'POST',
                            data: data,
                            url: '/forum/topic/set.html',
                            dataType: 'json',
                            success: function (res) {
                                mdui.snackbar({
                                    message: res.msg,
                                    position: 'right-top',
                                });
                            }
                        });
                    }
                }
            ],
            onOpen: function () {
                //开始时执行一次初始化
                mdui.mutation();
            }
        });

    }

    postAvatar(uid) {
        if ($$('body').find('#mtf-avatar-upload').length == 0) {
            $$('body').after('<input class="mdui-hidden" type="file" name="avatar" id="mtf-avatar-upload">');
            $$('#mtf-avatar-upload').on('change', function () {
                uploadFile();
            })
        };
        $$('#mtf-avatar-upload').trigger('click');

        function uploadFile() {
            var myform = new FormData();
            let files = document.querySelector("#mtf-avatar-upload").files[0];
            console.log();

            myform.append('avatar', files);
            myform.append('uid', uid);
            $$.ajax({
                method: 'POST',
                url: '/forum/expand/uploadAvatar.html',
                data: myform,
                dataType: 'json',
                contentType: false,
                success: function (res) {
                    if (res.code == 0) {
                        mdui.snackbar({
                            message: res.msg,
                            position: 'right-top',
                            onClosed: () => {
                                location.reload();
                            }
                        });
                    } else {
                        mdui.snackbar({
                            message: res.msg,
                            position: 'right-top',
                        });
                    }
                }
            })
        }
    }
}