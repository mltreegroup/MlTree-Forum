<?php /*a:8:{s:64:"E:\sitecode\MlTree-Forum\application\index\view\index\index.html";i:1523986580;s:79:"E:\sitecode\MlTree-Forum\application\../template\view\default\forum_public.html";i:1527983558;s:74:"E:\sitecode\MlTree-Forum\application\/../template\view\default\header.html";i:1526806114;s:24:"template/fullscreen.html";i:1526198583;s:79:"E:\sitecode\MlTree-Forum\application\/../template\view\default\topbar_user.html";i:1525155713;s:74:"E:\sitecode\MlTree-Forum\application\/../template\view\default\topbar.html";i:1525155690;s:78:"E:\sitecode\MlTree-Forum\application\/../template\view\default\right_tool.html";i:1525227194;s:74:"E:\sitecode\MlTree-Forum\application\/../template\view\default\footer.html";i:1526805974;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MlTree Forum</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="MlTree Forum PHP 开源 轻论坛 轻社区 Material Design Thinkphp" />
    <meta name="description" content="本站是 MlTree Forum 论坛社区产品的官方交流站点。MlTree Forum是一款由Thinkphp构建、Material Design风格的轻论坛。" />
    <meta name="author" content="北林">
    <?php if($option['full'] == '1'): ?>
     <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,minimal-ui">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="screen-orientation" content="portrait">
<meta name="full-screen" content="yes">
<meta name="browsermode" content="application">
<meta name="x5-orientation" content="portrait">
<meta name="x5-fullscreen" content="true">
<meta name="x5-page-mode" content="app">
    <?php endif; ?>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <link rel="stylesheet" href="/static/css/mdui.min.css">
    <link rel="stylesheet" href="/static/css/common.css">
    <link rel="shortcut icon" href="//at.alicdn.com/t/font_579119_2arllyqcj9p8ehfr.css" type="image/x-icon">
    <script src="/static/js/jquery-3.3.1.min.js"></script>
    <script src="/static/layui/layui.js"></script>
    <script src="/static/js/mdui.min.js"></script>
</head>

<body class="mdui-theme-primary-pink mdui-theme-accent-pink mdui-appbar-with-toolbar">
    <?php if(!empty(session('uid'))): ?> <!-- 站点导航部分 -->
<header class="mdui-appbar mdui-appbar-fixed mdui-color-theme">
    <div class="mdui-toolbar mdui-color-theme mdui-container">
        <a href="javascript:;" class="mdui-btn mdui-btn-icon" mdui-drawer="{target:'#mobile-menu'}">
            <i class="mdui-icon material-icons">menu</i>
        </a>
        <a href="<?php echo url('index/index'); ?>" class="mdui-typo-title">MlTree Forum</a>
        <a href="<?php echo url('index/index'); ?>" class="mdui-hidden-xs" title="Home">首页</a>
        <div class="mdui-toolbar-spacer"></div>
        <a href="<?php echo url('index/topic/create'); ?>" class="mdui-btn mdui-btn-icon mdui-ripple mdui-hidden-sm-up">
            <i class="mdui-icon material-icons">create</i>
        </a>
        <a href="<?php echo url('index/user/index'); ?>" class="mdui-ripple mdui-hidden-xs">
            <img src="<?php echo htmlentities($userData['avatar']); ?>" class="mdui-img-circle" width="32" alt="<?php echo htmlentities($userData['username']); ?>"><?php echo htmlentities($userData['username']); ?>
        </a>
        <a href="<?php echo url('index/user/logout'); ?>" class="mdui-btn mdui-ripple mdui-hidden-xs">退出</a>
    </div>

</header>
<!-- 论坛左侧手机版菜单 -->
<div id="mobile-menu" class="mdui-drawer mdui-drawer-close mdui-color-light-blue">

    <div class="mdui-panel" mdui-panel>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a class="mdui-text-color-blue" href="<?php echo url('index/index'); ?>" title="Home">
                    <i class="mdui-icon material-icons">home</i> 首页</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <div>站点导航</div>
                <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
            </div>
            <div class="mdui-panel-item-body">
                <a class="mdui-text-color-blue" href="https://blog.kingsr.cc" title="北林博客">北林博客</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a class="mdui-text-color-blue" href="<?php echo url('index/user/logout'); ?>" title="Logout">退出</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a href="JavaScript:;" class="mdui-text-color-blue" onclick="enterFullScreen()">
                    <i class="mdui-icon material-icons">crop_free</i>全屏阅读(F11退出)</a>
            </div>
        </div>
    </div>

</div> <?php else: ?> <!-- 站点导航部分 -->
<header class="mdui-appbar mdui-appbar-fixed mdui-color-theme">
    <div class="mdui-toolbar mdui-color-theme mdui-container">
        <a href="javascript:;" class="mdui-btn mdui-btn-icon" mdui-drawer="{target:'#mobile-menu'}">
            <i class="mdui-icon material-icons">menu</i>
        </a>
        <a href="<?php echo url('index/index'); ?>" class="mdui-typo-title">MlTree Forum</a>
        <a href="<?php echo url('index/index'); ?>" class="mdui-hidden-xs" title="Home">首页</a>
        <div class="mdui-toolbar-spacer"></div>
        <a href="<?php echo url('index/topic/create'); ?>" class="mdui-btn mdui-btn-icon mdui-ripple mdui-hidden-sm-up">
            <i class="mdui-icon material-icons">create</i>
        </a>
        <a href="<?php echo url('user/login'); ?>" class="mdui-btn mdui-ripple mdui-hidden-xs">
            <i class="mdui-icon material-icons">person</i>登录</a>
        <a href="<?php echo url('user/reg'); ?>" class="mdui-btn mdui-ripple mdui-hidden-xs">
            <i class="mdui-icon material-icons">person_add</i>注册</a>
    </div>

</header>
<!-- 论坛左侧手机版菜单 -->
<div id="mobile-menu" class="mdui-drawer mdui-drawer-close mdui-color-light-blue">

    <div class="mdui-panel" mdui-panel>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a class="mdui-text-color-blue" href="<?php echo url('index/index'); ?>" title="Home">
                    <i class="mdui-icon material-icons">home</i> 首页</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <div>站点导航</div>
                <i class="mdui-panel-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
            </div>
            <div class="mdui-panel-item-body">
                <a class="mdui-text-color-blue" href="https://blog.kingsr.cc" title="北林博客">北林博客</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a href="<?php echo url('user/login'); ?>" class="mdui-text-color-blue">
                    <i class="mdui-icon material-icons">person</i>登录</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a href="<?php echo url('user/reg'); ?>" class="mdui-text-color-blue">
                    <i class="mdui-icon material-icons">person_add</i>注册</a>
            </div>
        </div>

        <div class="mdui-panel-item">
            <div class="mdui-panel-item-header">
                <a href="JavaScript:;" class="mdui-text-color-blue" onclick="enterFullScreen()">
                    <i class="mdui-icon material-icons">crop_free</i>全屏阅读(F11退出)</a>
            </div>
        </div>

    </div>

</div>
    <?php endif; ?>
    <div class="mdui-container">
        <div class="mdui-row">
            
<!-- 置顶的内容 -->

<div class="mdui-col-xs-12 mdui-m-y-1">
    <div class="mdui-card">
        <div class="mdui-card-media">
            <img src="/static/images/card.png" alt="" height="100">
            <div class="mdui-card-media-covered mdui-card-media-covered-top">
                <div class="mdui-card-primary">
                    <div class="mdui-card-primary-title">置顶</div>
                </div>
            </div>
        </div>

        <div class="mdui-card-content">

            <ul class="mdui-list">
                <?php if(is_array($tops) || $tops instanceof \think\Collection || $tops instanceof \think\Paginator): $i = 0; $__LIST__ = $tops;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="mdui-list-item mdui-ripple">
                    <div class="mdui-list-item-avatar">
                        <img src="<?php echo htmlentities($vo['userData']['avatar']); ?>" alt="<?php echo htmlentities($vo['userData']['username']); ?>" title="<?php echo htmlentities($vo['userData']['username']); ?>">
                    </div>
                    <div class="mdui-list-item-content">
                        <a class="mdui-list-item-title" href="<?php echo url('index/topic/index',['tid'=>$vo['tid']]); ?>"><?php echo htmlentities($vo['subject']); ?> <?php echo outBadge($vo); ?></a>
                        <div class="mdui-list-item-text mdui-list-item-one-line"><?php echo $vo['content']; ?></div>
                        <div class="mdui-list-item-text">
                            <a href="<?php echo url('index/user/inde',['uid'=>$vo['uid']]); ?>"><?php echo htmlentities($vo['userData']['username']); ?></a>
                            <span title="<?php echo htmlentities($vo['create_time']); ?>"> <?php echo htmlentities($vo['time_format']); ?></span>
                            <span class="mdui-float-right">
                                <i class="mdui-icon material-icons">looks</i><?php echo htmlentities($vo['views']); ?></span>
                            <span class="mdui-float-right">
                                <i class="mdui-icon material-icons">comment</i><?php echo htmlentities($vo['comment']); ?></span>
                        </div>
                    </div>
                </li>
                <li class="mdui-divider-inset mdui-m-y-0"></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>

        </div>
    </div>
</div>

<!-- 论坛最新内容列表页 -->
<div class="mdui-col-xs-12 mdui-col-sm-8">

    <!-- 最新内容 -->

    <div class="mdui-tab" mdui-tab>
        <a href="#topic-all" class="mdui-ripple">综合</a>
        <a href="#topic-essence" class="mdui-ripple">精华</a>
    </div>

    <div id="topic-all">
        <ul class="mdui-list" id="topic-cps">

        </ul>
    </div>

    <div id="topic-essence">
        <ul class="mdui-list" id="topic-ess">

        </ul>
    </div>

</div>
<!-- 论坛右侧各类信息展示 -->
<div class="mdui-hidden-xs mdui-col-sm-4 mdui-typo">
    <!-- 公告栏 -->
    <div class="mdui-card mdui-m-b-1">
        <div class="mdui-card-header">
            <div class="mdui-card-header-title">公告</div>
            <div class="mdui-card-header-subtitle">Notice</div>
        </div>

        <div class="mdui-card-media">
            <img src="/static/images/card.png" />
        </div>

        <div class="mdui-card-content"><?php echo $option['notice']; ?></div>
    </div>

    <!-- 发帖 -->
    <a href="<?php echo url('index/topic/create'); ?>" class="mdui-btn mdui-btn-block mdui-color-theme mdui-ripple">发帖</a>

    <!-- 搜索 -->
    <div class="mdui-m-b-1">
        <form action="<?php echo url('search'); ?>" method="GET">
            <div class="mdui-textfield mdui-textfield-floating-label">
                <i class="mdui-icon material-icons">search</i>
                <label class="mdui-textfield-label">Search</label>
                <input class="mdui-textfield-input" type="search" name="keyword"/>
            </div>
        </form>
    </div>

    <!-- 友情链接 -->
    <div class="mdui-m-b-1 ml-friend-panel">
        <header>
            <h5>友情链接</h5>
        </header>
        <?php if(is_array($links) || $links instanceof \think\Collection || $links instanceof \think\Paginator): $i = 0; $__LIST__ = $links;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <div class="mdui-chip">
            <img class="mdui-chip-icon" src="<?php echo htmlentities((isset($vo['picurl']) && ($vo['picurl'] !== '')?$vo['picurl']:'/static/images/link.jpg')); ?>" />
            <a class="mdui-chip-title" href="<?php echo htmlentities($vo['url']); ?>" target="_blank"><?php echo htmlentities($vo['title']); ?></a>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>

</div> 

        </div>
    </div>
    <!-- 右下角快捷方式 -->
    <div class="mdui-fab-wrapper" id="mf-fixbar" mdui-fab="{trigger: 'hover'}">
        <button class="mdui-fab mdui-ripple mdui-color-theme-accent" id="fixbar-top">
            <!-- 默认显示的图标 -->
            <i class="mdui-icon material-icons">arrow_upward</i>

            <i class="mdui-icon mdui-fab-opened material-icons" >arrow_upward</i>
        </button>
        <div class="mdui-fab-dial">
            <a href="<?php echo url('index/topic/create'); ?>" class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-pink" title="发帖">
                <i class="mdui-icon material-icons">create</i>
            </a>
            <?php if(app('request')->controller() === 'Topic' && app('request')->action() === 'index'): if(authCheck('top') || authCheck('admin')): ?>
            <a href="<?php echo url('index/topic/set',['type'=>'top','tid'=>$topicData['tid']]); ?>" class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-red" title="置顶">
                <i class="mdui-icon material-icons">vertical_align_top</i>
            </a>
            <?php endif; if(authCheck('essence') || authCheck('admin')): ?>
            <a href="<?php echo url('index/topic/set',['type'=>'essence','tid'=>$topicData['tid']]); ?>" class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-purple" title="精华">
                <i class="mdui-icon material-icons">star</i>
            </a>
            <?php endif; if(authCheck('delete') || authCheck('admin')): ?>
            <a href="<?php echo url('index/topic/set',['type'=>'delete','tid'=>$topicData['tid']]); ?>" class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-blue" title="删除">
                <i class="mdui-icon material-icons">delete</i>
            </a>
            <?php endif; if(authCheck('delete') || authCheck('admin')): ?>
            <a href="<?php echo url('index/topic/update',['tid'=>$topicData['tid']]); ?>" class="mdui-fab mdui-fab-mini mdui-ripple mdui-color-orange" title="修改">
                <i class="mdui-icon material-icons">update</i>
            </a>
            <?php endif; endif; ?>
        </div>
    </div>
    <footer class="mdui-bottom-nav">
    <div>©MlTree Forum</div>
  <div><br/>运行时间:<?php echo get_runtime(); ?>s</div>
</footer>
<script src="/static/js/mltree-function.js"></script>
<script>
    //返回顶部
    $("#fixbar-top").click(function () {
        console.log('OK');
        $('body,html').animate({ scrollTop: 0 }, 1000);
        return false;
    });
</script> 
<script src="/static/js/mltree-flow.js"></script>
<script>
    //调用flow加载
    var flow = new mfFlow('index');
    flow.flow();
</script>

    <?php echo $option['siteFooterJs']; ?>
</body>

</html>