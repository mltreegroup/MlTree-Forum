addRunEvent(function helloworld() {
    mdui.dialog({
        title: 'Hello World!',
        content: 'Hello World!我是一个插件<br>我已经启用缓存但是，我还是将网站的运行效率成功降低了0.2S。我真厉害！',
        buttons: [{
            text: '确认',
        }],
        history: false,
    });
});