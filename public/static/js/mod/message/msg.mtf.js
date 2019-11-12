if (typeof $$ == 'undefined') {
    var $$ = mdui.JQ;
}
class mtfMessage {
    constructor(option = {}) {
        option = {
            elem: option.elem || '#messageBtn',
            max_height: option.max_height || '600px',
            max_width: option.max_width || '200px',
            trigger: option.trigger || 'click',
        }
        this.option = option;
        this.boxHidden = true;
        this.init();
    }

    _crateBox() {
        let html = `
        <div id="mltree-msg-box" class="mdui-col-xs-0 mdui-col-md-3" style="max-height:${this.option.max_height};max-width:${this.option.max_width};z-index:200508;min-width:10%">
            <iframe src="/forum/index/msg.html" frameborder="0"></iframe>
        </div>
        `;
        let elinfo = {
            height: $$(this.option.elem).height() + 'px',
        }
        $$(this.option.elem).css('position', 'relative');
        $$(this.option.elem).after(html);
        $$('#mltree-msg-box').css('top', elinfo.height);
        $$('#mltree-msg-box').hide();
    }

    init() {
        this._crateBox();
        if (this.option.trigger == 'click') {
            console.log(this.boxHidden);
            $$(this.option.elem).on('click', function () {
                console.log(mtfMessage.boxHidden);
                
                if (mtfMessage.boxHidden) {
                    $$('#mltree-msg-box').show();
                    console.log('SHOW');
                } else {
                    $$('#mltree-msg-box').hide();
                }
                mtfMessage.boxHidden = !mtfMessage.boxHidden;
            });
        }
    }
}