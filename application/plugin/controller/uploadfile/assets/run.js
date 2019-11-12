addRunEvent(function uploadfileAddBtn() {
    if (typeof tmp!="undefined") {
        editor.addBtn('upload', '<i class="mdui-icon material-icons">file_upload</i>', function () {
            window.open('/plugin/uploadfile.index/upfile.html');
        });
    }
});