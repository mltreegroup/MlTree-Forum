/**
 * 自制Markdown编辑器
 * Version: 1.0.1
 */
class mtfEditor {
    constructor(dom, option = {}) {
        this.dom = dom;
        this.editorDom = option.editorDom || "#editor";
        this.autoSave = null;
        this.key = option.key || 'mtf-editor';
        this.init();
    }

    init() {

        if (localStorage.getItem(this.key)) {
            let key = this.key,
                dom = this.editorDom;
            mdui.dialog({
                title: '询问-尚未保存的编辑',
                content: '有尚未保存的编辑，是否恢复？',
                history: false,
                buttons: [{
                        text: '否'
                    },
                    {
                        text: '是',
                        onClick: function (inst) {
                            $$(dom).val(localStorage.getItem(key));
                        }
                    }
                ]
            });
        };

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

        $$('form').on('submit', function () {
            this.cleanAutoSave();
        });

        $$(this.editorDom).on('input propertychange', () => {
            this.saveContent();
        })
    }

    addBtn(id, val, func) {
        $$(this.dom).append(
            $$('<button type="button" class="mdui-btn" id="mtf-editor-' + id + '"></button>')
            .html(val).on('click', function (e) {
                func(e);
            })
        );
        return true;
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

    saveContent() {
        let content = $$(this.editorDom).val();
        localStorage.setItem(this.key, content);
    }

    cleanAutoSave() {
        localStorage.removeItem(this.key);
    }
}