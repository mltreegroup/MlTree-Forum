let $$ = mdui.JQ;
class mtfUpload {
    constructor(option = {}) {
        option = {
            url: option.url || '',
            elem: option.elem || 'file_upload',
            auto: option.auto || true,
            selectBtn: option.selectBtn || '#selectBtn',
            uploadBtn: option.uploadBtn || '#uploadBtn',
            sign: option.sign || 'file',
            data: option.data || {},
            beforeSend: option.beforeSend || function (xhr, files) {},
            success: option.success || function (res, status, xhr) {},
            error: option.error || function (xhr, status) {},
            complete: option.complete || function (xhr, status) {}
        }
        this.option = option;
        this.fileSign = $$.guid();
        this.init();
    }

    init() {
        if ($$('body').find(this.option.elem).length == 0) {
            $$('body').after('<input style="display:none" type="file" name="' + this.option.sign + '" id="' + this.option.elem + '">');
        };
        let that = this;
        $$(this.option.selectBtn).on('click', function () {
            that.trigger();
            that.fileSign = $$.guid();
        });
    }

    trigger() {
        let option = this.option,
            status = false,
            that = this;
        $$('#' + this.option.elem).on('change', function () {
            var myform = new FormData();
            let files = document.querySelector('#' + option.elem).files[0];
            that.files = files;
            myform.append(option.sign, files);
            if (status) return;
            $$('#' + option.elem).val(""); //清空file的内容
            uploadFile(myform, files);
        });

        $$('#' + this.option.elem).trigger('click');

        function uploadFile(param, files) {
            status = true;
            $$.ajax({
                method: 'POST',
                url: option.url,
                data: param,
                dataType: 'json',
                contentType: false,
                beforeSend: function (xhr) {
                    status = false;
                    option.beforeSend(xhr, files);
                },
                success: function (res) {
                    status = false;
                    option.success(res);
                },
                error: function (res) {
                    status = false;
                    option.error(res);
                },
                complete: function (xhr, status) {
                    status = false;
                    option.complete(xhr, status);
                }
            });
        }
    }
}