<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer id="footer">
    <div id="footer-wrap"><div class="copyright">   
    <div class="copyright">©<?php echo date('Y'); ?> By <?php $this->author(); ?></div>
    <div class="framework-info">
        <span>由</span>
        <a target="_blank" href="http://www.typecho.org">Typecho</a><span> 强力驱动</span>
        <span class="footer-separator">|</span>
        <span>主题</span>
        <a id="btheme" target="_blank" href="https://github.com/wehaox/Typecho-Butterfly">Butterfly</a>
    </div>
        <div class="footer_custom_text"><?php $this->options->Customfooter() ?></div>
    </div>

</div>
</footer>
<?php $this->footer(); ?>
<style type="text/css" data-typed-js-css="true">
        .typed-cursor{
          opacity: 1;
        }
        .typed-cursor.typed-cursor--blink{
          animation: typedjsBlink 0.7s infinite;
          -webkit-animation: typedjsBlink 0.7s infinite;
                  animation: typedjsBlink 0.7s infinite;
        }
        @keyframes typedjsBlink{
          50% { opacity: 0.0; }
        }
        @-webkit-keyframes typedjsBlink{
          0% { opacity: 1; }
          50% { opacity: 0.0; }
          100% { opacity: 1; }
        }
 </style>
<?php if ($this->options->showFramework == 'off'): ?>
<style>.framework-info{display:none}</style>
<?php endif; ?>
<?php if ($this->options->CursorEffects !== 'off' &&$this->options->CursorEffects == 'heart') : ?>
<script id="click-heart" src="https://cdn.jsdelivr.net/npm/butterfly-extsrc@1/dist/click-heart.min.js" async="async" mobile="false"></script>
<?php elseif ($this->options->CursorEffects !== 'off' &&$this->options->CursorEffects == 'fireworks') : ?>
<canvas class="fireworks"></canvas>
<script id="fireworks" src="https://cdn.jsdelivr.net/npm/butterfly-extsrc@1.1.0/dist/fireworks.min.js" async="async" mobile="false"></script>
<?php endif; ?>
<?php if ($this->options->ShowLive2D !== 'off' && !isMobile()) : ?>
    <script src="https://cdn.jsdelivr.net/gh/stevenjoezhang/live2d-widget@latest/autoload.js"></script>
<?php endif; ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/custom.main.js'); ?>"></script>
<script><?php $this->options->CustomScript() ?></script>
 <?php $this->options->CustomBodyEnd() ?>
<!--搜索  -->
<div id="local-search">
  <div class="search-dialog" style="">
    <nav class="search-nav">
      <span class="search-dialog-title">本地搜索</span>
      <span id="loading-status"></span>
      <button class="search-close-button">
        <i class="fas fa-times"></i>
      </button>
    </nav>
    <div class="search-wrap" style="display: block;">
      <div id="local-search-input">
        <form class="local-search-box" method="post" action="<?php $this->options->siteUrl(); ?>" role="search" id="search">
          <label for="s" class="sr-only"><?php _e('搜索关键字'); ?></label>
          <input type="text"  name="s"  placeholder="回车查询" required="required"></div>
      </form>
      <hr>
      <div id="local-search-results"></div>
    </div>
  </div>
  <div id="search-mask" style=""></div>
</div>
</div>
<!--搜索end  -->
<script type="text/javascript">
    $(document).ready(function () {
        $( ".fancybox").fancybox();
        $.fancybox.defaults.hash = false;
    });
</script>
<div class="js-pjax">
<?php $this->header('commentReply=1&description=0&keywords=0&generator=0&template=0&pingback=0&xmlrpc=0&wlw=0&rss2=0&rss1=0&antiSpam=0&atom'); ?>  
<script>
            (function() {
                var a = document.addEventListener ? {
                    add: "addEventListener",
                    focus: "focus",
                    load: "DOMContentLoaded"
                } : {
                    add: "attachEvent",
                    focus: "onfocus",
                    load: "onload"
                };
                var c, d, e, f, b = document.getElementById("<?PHP $this->respondId() ?>");
                null != b && (c = b.getElementsByTagName("form"), c.length > 0 && (d = c[0], e = d.getElementsByTagName("textarea")[0], f = !1, null != e && "text" == e.name && e[a.add](a.focus, function() {
                    if (!f) {
                        var a = document.createElement("input");
                        a.type = "hidden", a.name = "_", d.appendChild(a), f = !0, a.value = <?PHP echo Typecho_Common::shuffleScriptVar($this->security->getToken(preg_replace('/\??&?_pjax=[^&]+/i', '', $this->request->getRequestUrl()))) ?>
                    }
                })))
            });
        </script>
<?php if ($this->options->NewTabLink == 'on'): ?>
<script>$(document).ready(function(){var a=document.getElementsByTagName("a");for(let i=0;i<a.length;i++){let domain=document.domain;let url=a[i].href;if(typeof(url)!="undefined"&&url.length!=0&&url.match(domain)==null&&url!="javascript:void(0);"){a[i].setAttribute("target","_BLANK")}}});</script>
<?php endif; ?>        
<?php if($this->is('index')): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/wehao.js?v1.4.0'); ?>"></script>
<!--打字-->
<?php if (is_array($this->options->beautifyBlock) && in_array('ShowTopimg',$this->options->beautifyBlock)): ?>
   <?php if(!empty($this->options->CustomSubtitle)): ?>
      <script>
 function subtitleType() {
if (true) {
var typed = new Typed("#subtitle", {
strings: "<?php $this->options->CustomSubtitle()?>".split(","),
startDelay: 300,
typeSpeed: 150,
loop: <?php $this->options->SubtitleLoop() ?>,
backSpeed: 50
})
}
}
"function"==typeof Typed?subtitleType():getScript("https://cdn.jsdelivr.net/npm/typed.js/lib/typed.min.js")
.then(subtitleType)
</script>
   <?php else: ?>
      <script>
function subtitleType(){
fetch("https://v1.hitokoto.cn").then(t=>t.json()).then(t=>{
o=0=="".length?new Array:" ".split(",");
o.unshift(t.hitokoto),
new Typed("#subtitle",{
    strings:o,
    startDelay:300,
    typeSpeed:150,
    loop: <?php $this->options->SubtitleLoop() ?>,
    backSpeed:50
      }
  )}
)}
"function"==typeof Typed?subtitleType():getScript("https://cdn.jsdelivr.net/npm/typed.js/lib/typed.min.js")
.then(subtitleType)
</script>
    <?php endif ?>
    <?php endif?>
<!--打字end-->
<!--判断主页end-->
<?php endif?>
</div>
<div id="rightside">
	<div id="rightside-config-hide" class="">
	    <?php if ($this->is('post')): ?>
	    <button id="readmode" type="button" title="阅读模式">
	        <i class="fas fa-book-open"></i>
	    </button>
	    <?php endif ?>
		<button id="translateLink" type="button" title="简繁转换">
			繁
		</button>
		<button id="darkmode" type="button" title="浅色和深色模式转换">
			<i class="fas fa-adjust">
			</i>
		</button>
		<button id="hide-aside-btn" type="button" title="单栏和双栏切换">
			<i class="fas fa-arrows-alt-h">
			</i>
		</button>
	</div>
	<div id="rightside-config-show">
		<button id="rightside_config" type="button" title="设置">
			<i class="fas fa-cog fa-spin">
			</i>
		</button>
		<?php if ($this->is('post')): ?>
		<button class="close" id="mobile-toc-button" type="button" title="目录">
			<i class="fas fa-list-ul">
			</i>
		</button>
		<?php endif ?>
		<?php if(!$this->is('index') && $this->allow('comment')): ?>
		<a id="to_comment" href="#comments" title="直达评论">
			<i class="fas fa-comments">
			</i>
		</a>
		<?php endif ?>
		<button id="go-up" type="button" title="回到顶部">
			<i class="fas fa-arrow-up">
			</i>
		</button>
	</div>
</div>
<!--pjax-->
<?php if($this->options->EnablePjax === 'on') : ?>
<link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pjax/pjax.min.js"></script>
<script>
let pjaxSelectors = ["title", "#body-wrap", "#rightside-config-hide", "#rightside-config-show", ".js-pjax"];
var pjax = new Pjax({
    elements: 'a:not([target="_blank"])',
    selectors: pjaxSelectors,
    cacheBust: !1,
    analytics: !1,
    scrollRestoration: !1});
document.addEventListener("pjax:send", (function() {
if (window.removeEventListener("scroll", window.tocScrollFn), window.removeEventListener("scroll", scrollCollect), "object" == typeof preloader && preloader.initLoading(), window.aplayers)
for (let e = 0; e < window.aplayers.length; e++) window.aplayers[e].options.fixed || window.aplayers[e].destroy();"object" == typeof typed && typed.destroy();
const e = document.body.classList;
e.contains("read-mode") && e.remove("read-mode")
NProgress.start();
})),
document.addEventListener("pjax:complete", (function() {

    NProgress.done();
    window.refreshFn(), 
    document.querySelectorAll("script[data-pjax]").forEach(e => {
        const t = document.createElement("script"),
        o = e.text || e.textContent || e.innerHTML || "";
        Array.from(e.attributes).forEach(e => t.setAttribute(e.name, e.value)), t.appendChild(document.createTextNode(o)), e.parentNode.replaceChild(t, e)}),
    GLOBAL_CONFIG.islazyload && window.lazyLoadInstance.update(), "function" == typeof chatBtnFn && chatBtnFn(), "function" == typeof panguInit && panguInit(), "function" == typeof gtag && gtag("config", "", 
    {page_path: window.location.pathname}),
    "object" == typeof _hmt && _hmt.push(["_trackPageview", window.location.pathname]), 
    "function" == typeof loadMeting && document.getElementsByClassName("aplayer").length && loadMeting(),
    "object" == typeof Prism && Prism.highlightAll(), "object" == typeof preloader && preloader.endLoading()
})
),
document.addEventListener("pjax:error", e => {
    // 404 === e.request.status && pjax.loadUrl("/404");
    if(e.request.status === 404){
        window.location="/404";
    }
    if(e.request.status === 403){
        window.location=e.request.responseURL
    }
})
</script>
<?php endif?>
 <!--pjax end-->
</body>
</html>