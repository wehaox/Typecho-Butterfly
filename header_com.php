<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html class="no-js">
<head>
    <link rel="icon" type="image/png" href="<?php $this->options->Sitefavicon() ?>">
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>
    <!-- 使用url函数转换相关路径 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/justifiedGallery/dist/css/justifiedGallery.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@latest/dist/jquery.fancybox.min.css">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/GrayMac.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('index.css'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css?v1.0.3.0'); ?>">
    <?php if (!empty($this->options->beautifyBlock) && in_array('ShowBeautifyChange',$this->options->beautifyBlock)): ?> 
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/custom.css?v1.0'); ?>">
    <?php endif; ?>
    <link rel="preconnect" href="//cdn.jsdelivr.net" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@latest/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <?php if (!empty($this->options->beautifyBlock) && in_array('showSnackbar',$this->options->beautifyBlock)): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/node-snackbar/dist/snackbar.min.css" media="print" onload="this.media='all'">
    <script src="https://cdn.jsdelivr.net/npm/node-snackbar/dist/snackbar.min.js"></script>
    <?php endif; ?>
    <?php if (!empty($this->options->beautifyBlock) && in_array('showLazyloadBlur',$this->options->beautifyBlock)): ?>
    <style>
        img[data-lazy-src]:not(.loaded) {filter: blur(10px) brightness(1);}img[data-lazy-src].error {filter: none;}
        <?php $this->options->CustomCSS() ?>
    </style>
    <?php endif; ?>
<!--额外的-->
<script>
    var GLOBAL_CONFIG = {
      root: '/',
      algolia: undefined,
      localSearch: { "path": "https://blog.wehao.xyz/search.xml", "languages": { "hits_empty": "找不到您查询的内容：${query}" } },
      translate: { "defaultEncoding": 2, "translateDelay": 0, "msgToTraditionalChinese": "繁", "msgToSimplifiedChinese": "簡" },
      noticeOutdate: undefined,
      highlight: { "plugin": "highlighjs", "highlightCopy": true, "highlightLang": true },
      copy: {
        success: '复制成功',
        error: '复制错误',
        noSupport: '浏览器不支持'
      },
      relativeDate: {
        homepage: false,
        post: false
      },
      copyright: undefined,
      lightbox: 'mediumZoom',
      Snackbar: {"chs_to_cht":"你已切换为繁体","cht_to_chs":"你已切换为简体","day_to_night":"你已切换为深色模式","night_to_day":"你已切换为浅色模式","bgLight":"#49b1f5","bgDark":"#121212","position":"<?php $this->options->SnackbarPosition() ?>"},
      source: {
        jQuery: 'https://cdn.jsdelivr.net/npm/jquery@latest/dist/jquery.min.js',
        justifiedGallery: {
          js: 'https://cdn.jsdelivr.net/npm/justifiedGallery/dist/js/jquery.justifiedGallery.min.js',
          css: 'https://cdn.jsdelivr.net/npm/justifiedGallery/dist/css/justifiedGallery.min.css'
        },
        fancybox: {
          js: 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@latest/dist/jquery.fancybox.min.js',
          css: 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@latest/dist/jquery.fancybox.min.css'
        }
      },
      isPhotoFigcaption: true,
      islazyload: true,
      isanchor: true
    };
    var saveToLocal = {
      set: function setWithExpiry(key, value, ttl) {
        const now = new Date()
        const expiryDay = ttl * 86400000
        const item = {
          value: value,
          expiry: now.getTime() + expiryDay,
        }
        localStorage.setItem(key, JSON.stringify(item))
      },
      get: function getWithExpiry(key) {
        const itemStr = localStorage.getItem(key)

        if (!itemStr) {
          return undefined
        }
        const item = JSON.parse(itemStr)
        const now = new Date()

        if (now.getTime() > item.expiry) {
          localStorage.removeItem(key)
          return undefined
        }
        return item.value
      }
    }
    const getScript = url => new Promise((resolve, reject) => {
      const script = document.createElement('script')
      script.src = url
      script.async = true
      script.onerror = reject
      script.onload = script.onreadystatechange = function () {
        const loadState = this.readyState
        if (loadState && loadState !== 'loaded' && loadState !== 'complete') return
        script.onload = script.onreadystatechange = null
        resolve()
      }
      document.head.appendChild(script)
    })
  </script>
<script id="config-diff">var GLOBAL_CONFIG_SITE = { 
		    isPost: !0, 
		    isHome: !0, 
		    isHighlightShrink: !0, 
		    isToc: !0, 
		   }
</script>
<script id="config_change">var GLOBAL_CONFIG_SITE = {
    isPost: !1, 
    isHome: !0, 
    isHighlightShrink: !1, 
    isToc: !0, 
    }
</script>
<noscript>
    <style type="text/css">
      #nav {
        opacity: 1
      }
      .justified-gallery img {
        opacity: 1
      }
      #recent-posts time,
      #post-meta time {
        display: inline !important
      }
    </style>
</noscript>
<script>(function () {
        window.activateDarkMode = function () {
          document.documentElement.setAttribute('data-theme', 'dark')
          if (document.querySelector('meta[name="theme-color"]') !== null) {
            document.querySelector('meta[name="theme-color"]').setAttribute('content', '#0d0d0d')
          }
        }
        window.activateLightMode = function () {
          document.documentElement.setAttribute('data-theme', 'light')
          if (document.querySelector('meta[name="theme-color"]') !== null) {
            document.querySelector('meta[name="theme-color"]').setAttribute('content', '#ffffff')
          }
        }
        const autoChangeMode = 'true'
        const t = saveToLocal.get('theme')
        if (autoChangeMode === '1') {
          const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches
          const isLightMode = window.matchMedia('(prefers-color-scheme: light)').matches
          const isNotSpecified = window.matchMedia('(prefers-color-scheme: no-preference)').matches
          const hasNoSupport = !isDarkMode && !isLightMode && !isNotSpecified
          if (t === undefined) {
            if (isLightMode) activateLightMode()
            else if (isDarkMode) activateDarkMode()
            else if (isNotSpecified || hasNoSupport) {
              const now = new Date()
              const hour = now.getHours()
              const isNight = hour <= 6 || hour >= 18
              isNight ? activateDarkMode() : activateLightMode()
            }
            window.matchMedia('(prefers-color-scheme: dark)').addListener(function (e) {
              if (saveToLocal.get('theme') === undefined) {
                e.matches ? activateDarkMode() : activateLightMode()
              }
            })
          } else if (t === 'light') activateLightMode()
          else activateDarkMode()
        } else if (autoChangeMode === '2') {
          const now = new Date()
          const hour = now.getHours()
          const isNight = hour <= 6 || hour >= 18
          if (t === undefined) isNight ? activateDarkMode() : activateLightMode()
          else if (t === 'light') activateLightMode()
          else activateDarkMode()
        } else {
          if (t === 'dark') activateDarkMode()
          else if (t === 'light') activateLightMode()
        } const asideStatus = saveToLocal.get('aside-status')
        if (asideStatus !== undefined) {
          if (asideStatus === 'hide') {
            document.documentElement.classList.add('hide-aside')
          } else {
            document.documentElement.classList.remove('hide-aside')
          }
        } const fontSizeVal = saveToLocal.get('global-font-size')
        if (fontSizeVal !== undefined) {
          document.documentElement.style.setProperty('--global-font-size', fontSizeVal + 'px')
        }
      })()</script>
<style type="text/css" data-typed-js-css="true">
    .typed-cursor {
      opacity: 1;
    }

    .typed-cursor.typed-cursor--blink {
      animation: typedjsBlink 0.7s infinite;
      -webkit-animation: typedjsBlink 0.7s infinite;
      animation: typedjsBlink 0.7s infinite;
    }

    @keyframes typedjsBlink {
      50% {
        opacity: 0.0;
      }
    }

    @-webkit-keyframes typedjsBlink {
      0% {
        opacity: 1;
      }

      50% {
        opacity: 0.0;
      }

      100% {
        opacity: 1;
      }
    }
  </style>
<!--额外的-->
    <!--[if lt IE 9]>
    <script src="//cdnjscn.b0.upaiyun.com/libs/html5shiv/r29/html5.min.js"></script>
    <script src="//cdnjscn.b0.upaiyun.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
    <?php $this->options->CustomHead() ?>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/jquery@latest/dist/jquery.min.js"></script>
<script src="<?php $this->options->themeUrl('/js/local-search.js'); ?>"> </script>
<script src="<?php $this->options->themeUrl('/js/tw_cn.js'); ?>"> </script>
<script src="<?php $this->options->themeUrl('/js/main.js?v1.1'); ?>"> </script>
<script src="<?php $this->options->themeUrl('/js/utils.js?v1.1'); ?>"> </script>
<script src="https://cdn.jsdelivr.net/npm/instant.page/instantpage.min.js" type="module"> </script>
<script src="https://cdn.jsdelivr.net/npm/medium-zoom/dist/medium-zoom.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload/dist/lazyload.iife.min.js"></script>
<!--[if lt IE 8]>
    <div class="browsehappy" role="dialog"><?php _e('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="http://browsehappy.com/">升级你的浏览器</a>'); ?>.</div>
<![endif]-->
<!--移动导航栏-->
  <div id="sidebar">
    <div id="menu-mask" style="display: none;"></div>
    <div id="sidebar-menus" class="">
      <div class="author-avatar"><img class="avatar-img"
          src="<?php $this->options->logoUrl() ?>"
          onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/hexo-butterfly@1.0.0/themes/butterfly/source/img/friend_404.gif'" alt="avatar"></div>
      <div class="site-data">
        <div class="data-item is-center">
          <div class="data-item-link"><a href="<?php $this->options->archivelink() ?>">
              <div class="headline">文章</div>
              <div class="length-num"><?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->publishedPostsNum() ?></div>
            </a></div>
        </div>
        <div class="data-item is-center">
          <div class="data-item-link"><a href="<?php $this->options->tagslink() ?>">
              <div class="headline">标签</div>
              <div class="length-num"><?php echo tagsNum(); ?></div>
            </a></div>
        </div>
        <div class="data-item is-center">
          <div class="data-item-link"><a href="<?php $this->options->categorylink() ?>">
              <div class="headline">分类</div>
              <div class="length-num"><?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->categoriesNum() ?></div>
            </a></div>
        </div>
      </div>
      <hr>
      <div class="menus_items">
        <div class="menus_item">
        <a class="site-page" href="<?php $this->options->siteUrl(); ?>"><i
              class="fa-fw fas fa-home"></i><span> 首页</span></a></div>
      <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?> <?php while($pages->next()): ?>
        <div class="menus_item">
               <a<?php if($this->is('page', $pages->slug)): ?><?php endif; ?> class="site-page" href="<?php $pages->permalink(); ?>">
                        <?php if($this->is($pages->title == "友链")){
                            echo"<i class='fa-fw fas fa-link'></i>";
                        }
                        elseif($this->is($pages->title == "关于")){
                             echo"<li class='fa-fw fas fa-heart' style='width: 25%;text-align: left;'></li>";
                        }
                        elseif($this->is($pages->title=="留言")){
                             echo"<i class='fa-fw fas fa-comment-dots'></i>";
                        }
                        elseif($this->is($pages->title=="归档")){
                             echo"<i class='fa-fw fas fa-archive'></i>";
                        }
                        elseif($this->is($pages->title=="标签")){
                             echo"<i class='fa-fw fas fa-tags'></i>";
                        }elseif($this->is($pages->title=="分类")){
                             echo"<i class='fa-fw fas fa-folder-open'></i>";
                        }elseif($this->is($pages->title=="留言板")){
                             echo"<i class='fa-fw fa fa-comment-dots'></i>";
                        }else{
                            echo"<i class='fa-fw fa fa-coffee'></i>";
                        }                        
                        ?>
                         <span><?php $pages->title(); ?></span>
                    </a>
              </div>
         <?php endwhile; ?>
      </div>
    </div>
  </div>
<!--移动导航栏-->