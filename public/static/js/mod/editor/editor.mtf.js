/**
 * 自制Markdown编辑器
 */
class mtfEditor {
    constructor(dom, option = {}) {
        this.dom = dom;
        this.editorDom = option.editorDom || "#editor";
        this.init();
    }

    init() {
        $$(this.dom + '-link').on('click', () => {
            mdui.dialog({
                title: '插入链接',
                content: `
                <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">链接名称</label>
                <input class="mdui-textfield-input" id="link-name" type="text"/>
                </div>
                <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">链接地址</label>
                <input class="mdui-textfield-input" id="link-value" type="url"/>
                </div>
                `,
                history: false,
                buttons: [{
                    text: '关闭'
                }, {
                    text: '确定',
                    onClick: () => {
                        let name = $$('#link-name').val(),
                            url = $$('#link-value').val(),
                            content = "[" + name + "](" + url + ")";
                        this.addContent(content);
                    }
                }],
            });
        });

        $$(this.dom + '-photo').on('click', () => {
            mdui.dialog({
                title: '插入图片',
                content: `
                <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">图片名称</label>
                <input class="mdui-textfield-input" id="link-name" type="text"/>
                </div>
                <div class="mdui-textfield mdui-textfield-floating-label">
                <label class="mdui-textfield-label">图片地址</label>
                <input class="mdui-textfield-input" id="link-value" type="url"/>
                </div>
                `,
                history: false,
                buttons: [{
                    text: '关闭'
                }, {
                    text: '确定',
                    onClick: () => {
                        let name = $$('#link-name').val(),
                            url = $$('#link-value').val(),
                            content = "![" + name + "](" + url + ")";
                        this.addContent(content);
                    }
                }],
            });
        });

        $$(this.dom + '-preview').on('click', () => {
            let content = $$(this.editorDom).val();
            content = marked(content);
            mdui.dialog({
                title: '预览编辑',
                content: content,
                history: false,
                buttons: [{
                    text: '关闭'
                }],
                onOpen: function (el) {
                    $$('.mdui-dialog').addClass('mdui-typo');
                    Prism.highlightAll();
                }
            });
        });

        $$(this.dom + '-code').on('click', () => {
            mdui.dialog({
                title: '插入代码',
                content: `
                <select class="mdui-select" mdui-select="{position: 'bottom'}" id="language">
                <option value="c">c</option>
                <option value="php">php</option>
                <option value="javascript">javascript</option>
                <option value="python">python</option>
                <option value="请输入语言名称">其他</option>
                </select>
                <div class="mdui-textfield">
                <label class="mdui-textfield-label">代码内容</label>
                <textarea rows="10" class="mdui-textfield-input" id="code-content"></textarea>
                </div>
                `,
                history: false,
                buttons: [{
                        text: '关闭'
                    },
                    {
                        text: '确定',
                        onClick: () => {
                            let lang = $$('#language').val();
                            let content = "\n" + "\`\`\`" + lang + "\n";
                            content += $$('#code-content').val();
                            content += "\n" + "\`\`\`" + "\n";
                            this.addContent(content);
                        }
                    }
                ],
                onOpen: () => {
                    mdui.mutation();
                }
            });
        });

        $$(this.dom + '-info').on('click', () => {
            window.open('http://www.markdown.cn');
        });
    }

    addContent(value, position = 'after') {
        let content = $$(this.editorDom).val();
        switch (position) {
            case 'before':
                content = value + content;
                $$(this.editorDom).val(content);
                break;

            default:
                content = content + value;
                $$(this.editorDom).val(content);
                break;
        }
    }
}