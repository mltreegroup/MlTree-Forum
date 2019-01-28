class mtfUpload {
    constructor(option = {}) {
        def_option = {
            url: '',
            elem: '#file_upload',
            auto: true,
            btn: '#upload_start',
            success: function () {

            }
        };
        this.option = def_option;
        this.option = option;

    }


}
var xhr;

function uploadFile(option) {
    xhr = new XMLHttpRequest();
    xhr.open('POST', option.url, true);
    xhr.onload = option.success;
    xhr.onerror = option.error;
    xhr.send(option.data);
}