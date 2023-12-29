<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('public/noqq.php'); ?>
<?php if (!$this->user->hasLogin()) : ?>
  <?php $this->need('public/defend.php'); ?>
<?php endif; ?>
<!DOCTYPE HTML>
<html data-theme="light" class="">

<head>
  <meta content="always" name="referrer">
  <link rel="icon" type="image/png" href="<?php $this->options->Sitefavicon() ?>">
  <meta charset="<?php $this->options->charset(); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="renderer" content="webkit">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="theme-color" content="<?php $this->options->themeColor() ?>">
  <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
          ), '', ' - '); ?><?php $this->options->title(); ?></title>
  <!-- 使用url函数转换相关路径 -->
  <link rel="preconnect" href="//<?php $this->options->jsdelivrLink() ?>" />
  <!--<link rel="stylesheet" href="https://gcore.jsdelivr.net/npm/justifiedGallery/dist/css/justifiedGallery.min.css">-->
  <link rel="stylesheet" href="<?php $this->options->themeUrl('index.css?v1.7.3'); ?>">
  <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css?v1.7.8'); ?>">
  <!--魔改美化-->
  <?php if (!empty($this->options->beautifyBlock) && in_array('ShowBeautifyChange', $this->options->beautifyBlock)) : ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/custom.css?v1.5.9'); ?>">
  <?php endif; ?>
  <!--百度统计-->
  <?php if ($this->options->baidustatistics != "") : ?>
    <script>
      var _hmt = _hmt || [];
      (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?<?php $this->options->baidustatistics(); ?>";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
      })();
    </script>
  <?php endif; ?>
  <!--谷歌AdSense广告-->
  <?php if ($this->options->googleadsense != "") : ?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php $this->options->googleadsense(); ?>" crossorigin="anonymous"></script>
  <?php endif; ?>
  <!--图标库-->
  <link href="https://at.alicdn.com/t/font_3159629_5bvsat8p5l.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://lib.baomitu.com/font-awesome/6.5.1/css/all.min.css">
  <!--其余静态文件-->
  <link rel="stylesheet" href="<?php cdnBaseUrl() ?>/css/fancybox.css">
  <link rel="stylesheet" href="<?php cdnBaseUrl() ?>/css/OwO.min.css">
  <?php if (!empty($this->options->beautifyBlock) && in_array('showSnackbar', $this->options->beautifyBlock)) : ?>
    <link rel="stylesheet" href="<?php $this->options->themeUrl('/css/snackbar.min.css') ?>" media="print" onload="this.media='all'">
    <script src="<?php $this->options->themeUrl('js/snackbar.min.js') ?>"></script>
  <?php endif; ?>
  <?php if (!empty($this->options->beautifyBlock) && in_array('showLazyloadBlur', $this->options->beautifyBlock)) : ?>
    <style>
      <?php if ($this->options->themeFontSize != "") : ?> :root {
        --global-font-size: <?php $this->options->themeFontSize() ?>;
      }

      <?php endif ?>img[data-lazy-src]:not(.loaded) {
        filter: blur(10px) brightness(1);
      }

      img[data-lazy-src].error {
        filter: none;
      }

      <?php $this->options->CustomCSS() ?>
    </style>
  <?php endif; ?>
  <?php if (!empty($this->options->sidebarBlock) && !in_array('ShowMobileSide', $this->options->sidebarBlock)) : ?>
    <style>
      @media screen and (max-width:900px) {

        #aside-content .card-info,
        #aside-content .card-announcement,
        #aside-content .card-recent-post,
        #aside-content #card-newest-comments,
        #aside-content .card-categories,
        #aside-content .card-tags,
        #aside-content .card-archives,
        #aside-content .card-webinfo {
          display: none;
        }
      }

      ins.adsbygoogle[data-ad-status="unfilled"] {
        display: none !important;
      }
    </style>
  <?php endif; ?>
  <!--额外的-->
  <script>
    const GLOBAL_CONFIG = {
      root: "/",
      algolia: void 0,
      localSearch: {
        path: undefined,
        languages: {
          hits_empty: "回车查询：${query}"
        }
      },
      translate: {
        defaultEncoding: <?php $this->options->DefaultEncoding() ?>,
        translateDelay: 0,
        msgToTraditionalChinese: "繁",
        msgToSimplifiedChinese: "简"
      },
      noticeOutdate: void 0,
      highlight: {
        plugin: "highlighjs",
        highlightCopy: !0,
        highlightLang: !0,
        highlightHeightLimit: 400
      },
      copy: {
        success: "复制成功",
        error: "复制错误",
        noSupport: "浏览器不支持"
      },
      relativeDate: {
        homepage: !0,
        post: !0
      },
      runtime: "天",
      date_suffix: {
        just: "",
        min: "",
        hour: "",
        day: "",
        month: ""
      },
      copyright: undefined,
      lightbox: "fancybox",
      Snackbar: {
        "chs_to_cht": "你已切换为繁体",
        "cht_to_chs": "你已切换为简体",
        "day_to_night": "你已切换为深色模式",
        "night_to_day": "你已切换为浅色模式",
        "bgLight": "#49b1f5",
        "bgDark": "#121212",
        "position": "<?php $this->options->SnackbarPosition() ?>"
      },
      source: {
        justifiedGallery: {
          js: "https://cdn.bootcdn.net/ajax/libs/flickr-justified-gallery/2.1.2/fjGallery.min.js",
          css: "https://cdn.bootcdn.net/ajax/libs/flickr-justified-gallery/2.1.2/fjGallery.min.css"
        }
      },
      isPhotoFigcaption: !1,
      islazyload: !0,
      isAnchor: !0,
      percent: {
        toc: !0,
        rightside: !0
      },
      coverPosition: '<?php $this->options->coverPosition() ?>',
    }
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
      script.onload = script.onreadystatechange = function() {
        const loadState = this.readyState
        if (loadState && loadState !== 'loaded' && loadState !== 'complete') return
        script.onload = script.onreadystatechange = null
        resolve()
      }
      document.head.appendChild(script)
    })
  </script>
  <script id="config-diff">
    var GLOBAL_CONFIG_SITE = {
      isPost: !0,
      isHome: !0,
      isHighlightShrink: !0,
      isToc: !0,
    }
  </script>
  <?php if ($this->is('post')) : ?>
    <script id="config_change">
      var GLOBAL_CONFIG_SITE = {
        isPost: !0,
        isHome: !0,
        isHighlightShrink: !1,
        isToc: !0,
      }
    </script>
  <?php else : ?>
    <script id="config_change">
      var GLOBAL_CONFIG_SITE = {
        isPost: !1,
        isHome: !0,
        isHighlightShrink: !1,
        isToc: !0,
      }
    </script>
  <?php endif; ?>
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
  <script>
    (e => {
      e.saveToLocal = {
        set: (e, t, a) => {
          if (0 === a) return;
          const o = {
            value: t,
            expiry: Date.now() + 864e5 * a
          };
          localStorage.setItem(e, JSON.stringify(o))
        },
        get: e => {
          const t = localStorage.getItem(e);
          if (!t) return;
          const a = JSON.parse(t);
          if (!(Date.now() > a.expiry)) return a.value;
          localStorage.removeItem(e)
        }
      }, 
      e.getScript = (e, t = {}) => new Promise(((a, o) => {
        const c = document.createElement("script");
        c.src = e, c.async = !0, c.onerror = o, c.onload = c.onreadystatechange = function() {
          const e = this.readyState;
          e && "loaded" !== e && "complete" !== e || (c.onload = c.onreadystatechange = null, a())
        }, Object.keys(t).forEach((e => {
          c.setAttribute(e, t[e])
        })), document.head.appendChild(c)
      })), 
      e.getCSS = (e, t = !1) => new Promise(((a, o) => {
        const c = document.createElement("link");
        c.rel = "stylesheet", c.href = e, t && (c.id = t), c.onerror = o, c.onload = c.onreadystatechange = function() {
          const e = this.readyState;
          e && "loaded" !== e && "complete" !== e || (c.onload = c.onreadystatechange = null, a())
        }, document.head.appendChild(c)
      })), 
      e.activateDarkMode = () => {
        document.documentElement.setAttribute("data-theme", "dark"), null !== document.querySelector('meta[name="theme-color"]') && document.querySelector('meta[name="theme-color"]').setAttribute("content", "#0d0d0d")
      }, 
      e.activateLightMode = () => {
        document.documentElement.setAttribute("data-theme", "light"), null !== document.querySelector('meta[name="theme-color"]') && document.querySelector('meta[name="theme-color"]').setAttribute("content", "#ffffff")
      };
      const t = saveToLocal.get("theme"),
        a = <?php $this->options->darkModeSelect() ?> === 4,
        o = <?php $this->options->darkModeSelect() ?> === 1,
        c = <?php $this->options->darkModeSelect() ?> === 2,
        n = !a && !o && !c;
      if (void 0 === t) {
        if (o) activateLightMode();
        else if (a) activateDarkMode();
        else if (n) {
          const e = (new Date).getHours();
          <?php darkTimeFunc() ?> ? activateDarkMode() : activateLightMode()
        }
        window.matchMedia("(prefers-color-scheme: dark)").addListener((e => {
          void 0 === saveToLocal.get("theme") && (e.matches ? activateDarkMode() : activateLightMode())
        }))
      } else "light" === t ? activateLightMode() : activateDarkMode();
      const d = saveToLocal.get("aside-status");
      void 0 !== d && ("hide" === d ? document.documentElement.classList.add("hide-aside") : document.documentElement.classList.remove("hide-aside"));
      /iPad|iPhone|iPod|Macintosh/.test(navigator.userAgent) && document.documentElement.classList.add("apple")
    })(window)
  </script>
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

  <?php if ($this->options->EnableCustomColor === 'true') : ?>
    <style>
      ::-webkit-scrollbar-thumb {
        background-color: <?php $this->options->CustomColorMain() ?> !important;
      }

      :root {
        --btn-hover-color: <?php $this->options->CustomColorButtonHover() ?>;
        --btn-bg: <?php $this->options->CustomColorButtonBG() ?>;
        --text-bg-hover: <?php $this->options->CustomColorButtonBG() ?>;
        --hr-before-color: <?php $this->options->CustomColorButtonBG() ?>;
        --text-bg-hover: <?php $this->options->CustomColorMain() ?>;
        --hr-border: <?php $this->options->CustomColorMain() ?>;
      }

      ::selection,
      #aside-content #card-toc .toc-content .toc-link.active {
        background: <?php $this->options->CustomColorSelection() ?>;
      }

      #page-header.nav-fixed #nav #site-name:hover,
      #page-header.nav-fixed #nav #toggle-menu:hover,
      #page-header.nav-fixed #nav #menus .menus_items .menus_item a:hover,
      #aside-content #card-toc .toc-content .toc-link:hover,
      #recent-posts>.recent-post-item>.recent-post-info>.article-title:hover,
      #aside-content .aside-list>.aside-list-item .content>.comment:hover,
      #aside-content .aside-list>.aside-list-item .content>.title:hover,
      .widget-list a:hover,
      .post-copyright-info a:hover,
      .article-sort-item-title:hover,
      .search-dialog .search-nav,
      #page-header.nav-fixed #nav a:hover,
      .search-dialog .search-nav .search-close-button:hover {
        color: <?php $this->options->CustomColorMain() ?>;
      }

      #nav .site-page:not(.child):after {
        background-color: <?php $this->options->CustomColorMain() ?>
      }

      #local-search .search-dialog .local-search-box input {
        border: 2px solid <?php $this->options->CustomColorMain() ?> !important;
      }

      #aside-content .card-archives ul.card-archive-list>.card-archive-list-item a:hover,
      #aside-content .card-categories ul.card-category-list>.card-category-list-item a:hover {
        background-color: var(--btn-bg);
      }

      #aside-content .card-tag-cloud a:hover {
        color: <?php $this->options->CustomColorMain() ?> !important;
      }
    </style>
  <?php endif ?>
  <?php $this->header('generator=&'); ?>
  <?php $this->options->CustomHead() ?>
  <?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch', $this->options->beautifyBlock)) : ?>
    <style>
      #dSearch {
        display: inline-block;
      }

      #dSearch>input {
        border: none;
        opacity: 1;
        outline: none;
        width: 35px;
        text-indent: 2px;
        transition: all .5s;
        background: transparent;
      }

      #page-header.nav-fixed #nav ::placeholder,
      #page-header.nav-fixed #nav input {
        color: var(--font-color);
      }

      #nav ::placeholder,
      #nav input {
        color: var(--light-grey);
      }

      #page-header.not-top-img #nav ::placeholder,
      #page-header.not-top-img #nav input {
        color: var(--font-color);
        text-shadow: none;
      }

      #page-header.nav-fixed #nav a:hover {
        color: unset;
      }
    </style>
  <?php endif ?>
</head>

<body>
  <script src="<?php $this->options->themeUrl('/js/main.js?v1.7.3'); ?>"> </script>
  <script src="<?php $this->options->themeUrl('/js/utils.js?v1.7.3'); ?>"> </script>
  <script src="<?php $this->options->themeUrl('/js/tw_cn.js?v1.7.3'); ?>"> </script>
  <?php if (is_array($this->options->beautifyBlock) && !in_array('showNoAlertSearch', $this->options->beautifyBlock)) : ?>
    <script src="<?php $this->options->themeUrl('/js/local-search.js'); ?>"> </script>
  <?php endif ?>

  <script src="<?php cdnBaseUrl() ?>/js/jquery.min.js"></script>
  <script src="<?php cdnBaseUrl() ?>/js/instantpage.min.js">
    type = "module" >
  </script>
  <script src="<?php cdnBaseUrl() ?>/js/medium-zoom.min.js"> </script>
  <script src="<?php cdnBaseUrl() ?>/js/dream-msg.min.js"></script>
  <script src="<?php cdnBaseUrl() ?>/js/lazyload.iife.min.js"></script>
  <script src="<?php cdnBaseUrl() ?>/js/fancybox.umd.js"></script>
  <script src="<?php cdnBaseUrl() ?>/js/OwO.min.js"></script>
  <script src="<?php cdnBaseUrl() ?>/js/artplayer.js"> </script>

  <!--[if lt IE 8]>
    <div class="browsehappy" role="dialog"><?php _e('当前网页 <strong>不支持</strong> 你正在使用的浏览器. 为了正常的访问, 请 <a href="http://browsehappy.com/">升级你的浏览器</a>'); ?>.</div>
<![endif]-->
  <!--移动导航栏-->
  <div id="sidebar">
    <div id="menu-mask" style="display: none;"></div>
    <div id="sidebar-menus" class="">
      <div class="avatar-img is-center">
        <img src="<?php $this->options->logoUrl() ?>" onerror="this.onerror=null;this.src='https://<?php $this->options->jsdelivrLink() ?>/npm/hexo-butterfly@1.0.0/themes/butterfly/source/img/friend_404.gif'" alt="avatar">
      </div>
      <div class="site-data">
        <div class="card-info-data site-data is-center">
          <a href="<?php $this->options->archivelink() ?>">
            <div class="headline">文章</div>
            <div class="length-num">
              <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->publishedPostsNum() ?>
            </div>
          </a>
          <a href="<?php $this->options->tagslink() ?>">
            <div class="headline">标签</div>
            <div class="length-num"><?php echo tagsNum(); ?></div>
          </a>
          <a href="<?php $this->options->categorylink() ?>">
            <div class="headline">
              分类
            </div>
            <div class="length-num">
              <?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->categoriesNum() ?>
            </div>
          </a>
        </div>
      </div>
      <hr>
      <div class="menus_items">
        <div class="menus_item">
          <a class="site-page" href="<?php $this->options->siteUrl(); ?>"><i class="fa-fw fas fa-home"></i><span> 首页</span></a>
        </div>
        <?php if ($this->options->EnableAutoHeaderLink === 'on') : ?>
          <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?> <?php while ($pages->next()) : ?>
            <div class="menus_item">
              <a<?php if ($this->is('page', $pages->slug)) : ?><?php endif; ?> class="site-page" href="<?php $pages->permalink(); ?>">
                <?php switch ($pages->title) {
                                                                                case "友链":
                                                                                  echo "<i class='fa-fw fas fa-link'></i>";
                                                                                  break;
                                                                                case "关于":
                                                                                  echo "<i class='fa-fw fas fa-user'></i>";
                                                                                  break;
                                                                                case "留言":
                                                                                  echo "<i class='fa-fw fas fa-comment-dots'></i>";
                                                                                  break;
                                                                                case "归档":
                                                                                  echo "<i class='fa-fw fas fa-archive'></i>";
                                                                                  break;
                                                                                case "标签":
                                                                                  echo "<i class='fa-fw fas fa-tags'></i>";
                                                                                  break;
                                                                                case "分类":
                                                                                  echo "<i class='fa-fw fas fa-folder-open'></i>";
                                                                                  break;
                                                                                case "留言板":
                                                                                  echo "<i class='fa-fw fa fa-comment-dots'></i>";
                                                                                  break;
                                                                                default:
                                                                                  echo "<i class='fa-fw fa fa-coffee'></i>";
                                                                              } ?>
                <span><?php $pages->title(); ?></span>
                </a>
            </div>
          <?php endwhile; ?>
        <?php endif; ?>
        <?php $this->options->CustomHeaderLink() ?>
      </div>
    </div>
  </div>
  <!--移动导航栏-->
