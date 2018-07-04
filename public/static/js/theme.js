/*
 * 基于MDL调色板的主题切换器，漂亮、易用。
 * 由 学神之女 制作。
 * GitHub项目: https://github.com/DFFZMXJ/mdui-colour=pad
 */
/*这必须凌驾于JS上*/
var $$ = mdui.JQ; //MDUI的选择器，不必引入MDUI了。
var done = true; //颜色是否全部选择
var theme = function() {
  var page = {
    primary: "blue",
    accent: "pink"
  };
  var preview = {
    primary: "blue",
    accent: "pink"
  };
  //适应MlTree自身主题系统。
  (function() {
    page.primary = localStorage.themePrimary || $$("meta[name=\"setPrimary\"]");
    page.accent = localStorage.themeAccent || $$("meta[name=\"setAccent\"]");
    preview.primary = localStorage.themePrimary || $$("meta[name=\"setPrimary\"]");
    preview.accent = localStorage.themeAccent || $$("meta[name=\"setAccent\"]");
  })();
  this.set = {
    page: function(primary = false, accent = false) {
      if (!primary || !accent) return false;
      if (typeof primary != "string" || typeof accent != "string") return false;
      $$("body").removeClass("mdui-theme-primary-" + page.primary);
      $$("body").removeClass("mdui-theme-accent-" + page.accent);
      $$("body").addClass("mdui-theme-primary-" + primary);
      $$("body").addClass("mdui-theme-accent-" + accent);
      page = {
        primary: primary,
        accent: accent
      };
      //适应MlTree自身主题系统
      localStorage.themePrimary = primary;
      localStorage.themeAccent = accent;
      return true;
    },
    preview: function(primary, accent) {
      if (!primary || !accent) return false;
      if (typeof primary != "string" || typeof accent != "string") return false;
      $$("[data-preview-primary]").removeClass("mdui-color-" + preview.primary);
      $$("[data-preview-accent]").removeClass("mdui-color-" + preview.accent);
      $$("[data-preview-primary]").addClass("mdui-color-" + primary);
      $$("[data-preview-accent]").addClass("mdui-color-" + accent);
      preview = {
        primary: primary,
        accent: accent
      };
    }
  };
  this.info = { //获取主题信息
    page: function() {
      return page;
    },
    preview: function() {
      return preview;
    }
  };
  $$("body").addClass("mdui-theme-primary-" + page.primary); //初始化
  $$("body").addClass("mdui-theme-accent-" + page.accent);
  $$("[data-preview-primary]").addClass("mdui-color-" + preview.primary);
  $$("[data-preview-accent]").addClass("mdui-color-" + preview.accent);
  return true;
};
theme = new theme();
var setting = {
  primary: theme.info.page().primary,
  accent: theme.info.page().accent
};
var unsupportedAccent = ["Grey", "Blue Grey", "Brown"]; //不受支持的强调色
$$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
  return $$1.toUpperCase();
}) + "\"]").addClass("selected selected--1");
$$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
  return $$1.toUpperCase();
}) + "\"]").children("g[filter]").attr("filter", "url(#drop-shadow)");
$$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
  return $$1.toUpperCase();
}) + "\"]").addClass("selected selected--2");
$$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
  return $$1.toUpperCase();
}) + "\"]").children("g[filter]").attr("filter", "url(#drop-shadow)");
$$("g[data-color]").on("click", function(e) {
  if (done) {
    $$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
      return $$1.toUpperCase();
    }) + "\"]").removeClass("selected selected--1");
    $$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
      return $$1.toUpperCase();
    }) + "\"]").children("g[filter]").attr("filter", "");
    $$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
      return $$1.toUpperCase();
    }) + "\"]").removeClass("selected selected--2");
    $$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
      return $$1.toUpperCase();
    }) + "\"]").children("g[filter]").attr("filter", "");
    setting.primary = $$(this).attr("data-color").toLowerCase().replace(/ /g, "-");
    console.log("您选择的主色：" + $$(this).attr("data-color"));
    $$(this).addClass("selected selected--1");
    $$(this).children("g[filter]").attr("filter", "url(#drop-shadow)");
    $$("#wheel svg").addClass("hide-nonaccents");
    done = !done;
  } else {
    if ($$(this).attr("data-color").toLowerCase().replace(/ /g, "-") != setting.primary && unsupportedAccent.indexOf($$(this).attr("data-color")) == -1) {
      setting.accent = $$(this).attr("data-color").toLowerCase().replace(/ /g, "-");
      console.log("您选择的强调色：" + $$(this).attr("data-color"));
      $$(this).addClass("selected selected--2");
      $$(this).children("g[filter]").attr("filter", "url(#drop-shadow)");
      console.log("主题色：" + JSON.stringify(setting));
      theme.set.preview(setting.primary, setting.accent);
      $$("#wheel svg").removeClass("hide-nonaccents");
      done = !done;
    }
  }
});
$$("#apply").on("click", function() {
  theme.set.page(setting.primary, setting.accent);
  mdui.snackbar("已应用");
});
var rTheme = function() {
  theme.set.preview(setting.primary, setting.accent);
  $$("[data-preview-primary]").addClass("mdui-color-" + setting.primary);
  $$("[data-preview-accent]").addClass("mdui-color-" + setting.accent);
  var unsupportedAccent = ["Grey", "Blue Grey", "Brown"]; //不受支持的强调色
  $$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
    return $$1.toUpperCase();
  }) + "\"]").addClass("selected selected--1");
  $$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
    return $$1.toUpperCase();
  }) + "\"]").children("g[filter]").attr("filter", "url(#drop-shadow)");
  $$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
    return $$1.toUpperCase();
  }) + "\"]").addClass("selected selected--2");
  $$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
    return $$1.toUpperCase();
  }) + "\"]").children("g[filter]").attr("filter", "url(#drop-shadow)");
  $$("g[data-color]").on("click", function(e) {
    if (done) {
      $$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
        return $$1.toUpperCase();
      }) + "\"]").removeClass("selected selected--1");
      $$("g[data-color=\"" + setting.primary.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
        return $$1.toUpperCase();
      }) + "\"]").children("g[filter]").attr("filter", "");
      $$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
        return $$1.toUpperCase();
      }) + "\"]").removeClass("selected selected--2");
      $$("g[data-color=\"" + setting.accent.replace(/\-/g, " ").replace(/^([a-z])| ([a-z])/g, function($$1) {
        return $$1.toUpperCase();
      }) + "\"]").children("g[filter]").attr("filter", "");
      setting.primary = $$(this).attr("data-color").toLowerCase().replace(/ /g, "-");
      console.log("您选择的主色：" + $$(this).attr("data-color"));
      $$(this).addClass("selected selected--1");
      $$(this).children("g[filter]").attr("filter", "url(#drop-shadow)");
      $$("#wheel svg").addClass("hide-nonaccents");
      done = !done;
    } else {
      if ($$(this).attr("data-color").toLowerCase().replace(/ /g, "-") != setting.primary && unsupportedAccent.indexOf($$(this).attr("data-color")) == -1) {
        setting.accent = $$(this).attr("data-color").toLowerCase().replace(/ /g, "-");
        console.log("您选择的强调色：" + $$(this).attr("data-color"));
        $$(this).addClass("selected selected--2");
        $$(this).children("g[filter]").attr("filter", "url(#drop-shadow)");
        console.log("主题色：" + JSON.stringify(setting));
        theme.set.preview(setting.primary, setting.accent);
        $$("#wheel svg").removeClass("hide-nonaccents");
        done = !done;
      }
    }
  });
  $$("#apply").on("click", function() {
    theme.set.page(setting.primary, setting.accent);
    mdui.snackbar("已应用");
  });
  $("#theme-error").addClass("mdui-hidden");
}
