class mtfMessage {
    constructor(option = {}) {
        option = {
            elem: option.elem || '#messageBox',
            position: option.position || 'auto',
            asyn: option.asyn || true,
            title: option.title || '',
            content: option.content || '',
            max_height: option.max_height || '600px',
            max_width: option.max_width || '200px',
            cache: option.cache || false,
            url: option.url || null,
            trigger: option.trigger || 'cilck',
        }
        this.option = option;
    }

    _crateBox() {
        let html = `
        
        `;
    }

    init() {

        if (this.option.url == null) {

        }
    }
}