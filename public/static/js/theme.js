/*!
 * 一个适用于 MDUI、仿官网变色功能的 JavaScript 插件
 * https://www.mdui.org
 * 
 * 使用方法：在 body 标签内的最顶部引入即可。
 */

/**
 * 主题类
 * 目前的用途大概是变色
 */
var theme = {
	/*! 可自定义以下内容 */
	primaryList: { // 允许使用的主题色
		'姨妈红': 'red',
		'少女粉': 'pink',
		'基佬紫': 'purple',
		'胖次蓝': 'blue',
		'早苗绿': 'green',
		'伊藤橙': 'orange',
		'呆毛黄': 'yellow',
		'远坂棕': 'brown',
		'靛': 'indigo',
		'青': 'cyan',
		'水鸭': 'teal',
		'性冷淡': 'grey',
	},
	accentList: { // 允许使用的强调色
		'姨妈红': 'red',
		'少女粉': 'pink',
		'基佬紫': 'purple',
		'胖次蓝': 'blue',
		'早苗绿': 'green',
		'伊藤橙': 'orange',
		'呆毛黄': 'yellow',
		'远坂棕': 'brown',
		'靛': 'indigo',
		'青': 'cyan',
		'水鸭': 'teal',
		'性冷淡': 'grey',
	},
	layoutList: { // 允许使用的模式
		'日间模式': 'light',
		'夜间模式': 'black',
	},
	/*! 可自定义以上内容 */
	primary: localStorage.getItem("themePrimary"),
	accent: localStorage.getItem("themeAccent"),
	layout: localStorage.getItem("themeLayout"),
	/**
	 * 开始设定
	 */
	startup: function () {		
		this.setPrimary(this.primary == null ? mdui.JQ('meta[name="setPrimary"]').attr('content') : this.primary);
		this.setAccent(this.accent == null ? mdui.JQ('meta[name="setAccent"]').attr('content') : this.accent);
		this.setLayout(this.layout == null ? mdui.JQ('meta[name="setLayout"]').attr('content') : this.layout);

		var primary = '#primary-' + mdui.JQ('meta[name="setPrimary"]').attr('content');;
		var accent = '#accent-' + mdui.JQ('meta[name="setAccent"]').attr('content');
		var layout = '#layout-' + mdui.JQ('meta[name="setLayout"]').attr('content');
		
		mdui.JQ(primary).prop('checked', 'checked');
		mdui.JQ(accent).prop('checked', 'checked');
		mdui.JQ(layout).prop('checked', 'checked');
	},
	/**
	 * 初始化设定
	 */
	reset: function () {
		this.primary = null;
		this.accent = null;
		this.layout = null;
		this.startup();
	},
	/**
	 * 设定主题色
	 * @param {Object} color
	 */
	setPrimary: function (color) {
		this.updateTheme(color, "primary");
		localStorage.setItem("themePrimary", color);
	},
	/**
	 * 设定强调色
	 * @param {Object} color
	 */
	setAccent: function (color) {
		this.updateTheme(color, "accent");
		localStorage.setItem("themeAccent", color);
	},
	/**
	 * 设定模式
	 * @param {Object} layout
	 */
	setLayout: function (layout) {
		var classs = mdui.JQ("body").attr("class");
		if (layout == "light") {
			classs = classs.replace(/mdui-theme-layout-dark/i, "");
		} else {
			classs = classs + " mdui-theme-layout-dark";
		}
		mdui.JQ("body").attr("class", classs);
		localStorage.setItem("themeLayout", layout);
	},
	/**
	 * 更新主题
	 * @param {Object} color
	 * @param {Object} theme
	 */
	updateTheme: function (color, theme) {
		var list = mdui.JQ("body").attr("class").split(" ");
		list = this.removeTheme(list, theme) + "mdui-theme-" + theme + "-" + color;
		mdui.JQ("body").attr("class", list);
	},
	/**
	 * 删除主题
	 * @param {Object} list
	 * @param {Object} theme
	 */
	removeTheme: function (list, theme) {
		for (var i = 0; i < list.length; i++) {
			if (new RegExp("mdui-theme-" + theme + "-").test(list[i])) {
				list[i] = "";
			}
		}
		var classs = "";
		for (var i = 0; i < list.length; i++) {
			classs = classs + list[i] + " ";
		}
		return classs;
	},
	/**
	 * 设置html
	 */
	setHtml: function () {
		for (var key in this.primaryList) {
			if (this.primaryList[key] == this.primary) {
				mdui.JQ('#primary-list').append('<div class="mdui-col"><label class="mdui-radio mdui-text-color-' + this.primaryList[key] + '" onclick=\'theme.setPrimary("' + this.primaryList[key] + '")\'><input id="primary-' + this.primaryList[key] + '" value="' + this.primaryList[key] + '" type="radio" checked name="themePrimary"/><i class="mdui-radio-icon"></i>' + key + '</label></div>');
			} else {
				mdui.JQ('#primary-list').append('<div class="mdui-col"><label class="mdui-radio mdui-text-color-' + this.primaryList[key] + '" onclick=\'theme.setPrimary("' + this.primaryList[key] + '")\'><input id="primary-' + this.primaryList[key] + '" value="' + this.primaryList[key] + '" type="radio" name="themePrimary"/><i class="mdui-radio-icon"></i>' + key + '</label></div>');
			}
		}
		for (var key in this.accentList) {
			if (this.accentList[key] == this.accent) {
				mdui.JQ('#accent-list').append('<div class="mdui-col"><label class="mdui-radio mdui-text-color-' + this.accentList[key] + '" onclick=\'theme.setAccent("' + this.accentList[key] + '")\'><input id="accent-' + this.accentList[key] + '" value="' + this.accentList[key] + '" type="radio" checked name="themeAccent"/><i class="mdui-radio-icon"></i>' + key + '</label></div>');
			} else {
				mdui.JQ('#accent-list').append('<div class="mdui-col"><label class="mdui-radio mdui-text-color-' + this.accentList[key] + '" onclick=\'theme.setAccent("' + this.accentList[key] + '")\'><input id="accent-' + this.accentList[key] + '" value="' + this.accentList[key] + '" type="radio" name="themeAccent"/><i class="mdui-radio-icon"></i>' + key + '</label></div>');
			}
		}
		for (var key in this.layoutList) {
			if (this.layoutList[key] == this.layout) {
				mdui.JQ('#layout-list').append('<div class="mdui-col"><label class="mdui-radio mdui-text-color-' + this.layoutList[key] + '" onclick=\'theme.setLayout("' + this.layoutList[key] + '")\'><input id="layout-' + this.layoutList[key] + '" value="' + this.layoutList[key] + '" type="radio" checked name="themeLayout"/><i class="mdui-radio-icon"></i>' + key + '</label></div>');
			} else {
				mdui.JQ('#layout-list').append('<div class="mdui-col"><label class="mdui-radio mdui-text-color-' + this.layoutList[key] + '" onclick=\'theme.setLayout("' + this.layoutList[key] + '")\'><input id="layout-' + this.layoutList[key] + '" value="' + this.layoutList[key] + '" type="radio" name="themeLayout"/><i class="mdui-radio-icon"></i>' + key + '</label></div>');
			}
		}
	}
};

theme.startup();

window.onload = function () {
	theme.setHtml(); // 设置 html 代码结构
}