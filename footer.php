<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if(!$this->page404()): ?>
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
<?php endif ?>
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
    <?php if($this->options->StaticFile == 'CDN' && $this->options->CDNURL == ''): ?>
    <script id="click-heart" src="https://<?php $this->options->jsdelivrLink() ?>/npm/butterfly-extsrc@1/dist/click-heart.min.js" async="async" mobile="false"></script>
    <?php elseif($this->options->StaticFile == 'CDN' && $this->options->CDNURL !== ''): ?>
     <script id="click-heart" src="<?php $this->options->CDNURL() ?>/static/js/click-heart.min.js" async="async" mobile="false"></script>
    <?php else : ?> 
        <script id="click-heart" src="<?php $this->options->themeUrl('/static/js/click-heart.min.js'); ?>" async="async" mobile="false"></script>
    <?php endif; ?>
<?php elseif ($this->options->CursorEffects !== 'off' &&$this->options->CursorEffects == 'fireworks') : ?>
<canvas class="fireworks"></canvas>

    <?php if($this->options->StaticFile == 'CDN' && $this->options->CDNURL == ''): ?>
        <script id="fireworks" src="https://<?php $this->options->jsdelivrLink() ?>/npm/butterfly-extsrc@1.1.0/dist/fireworks.min.js" async="async" mobile="false"></script>
    <?php elseif($this->options->StaticFile == 'CDN' && $this->options->CDNURL !== ''): ?>
        <script id="fireworks" src="<?php $this->options->CDNURL() ?>/static/js/fireworks.min.js" async="async" mobile="false"></script>
    <?php else : ?> 
        <script id="fireworks" src="<?php $this->options->themeUrl('/static/js/fireworks.min.js'); ?>" async="async" mobile="false"></script>
    <?php endif; ?>
<?php endif; ?>
<?php if ($this->options->ShowLive2D !== 'off' && !isMobile()) : ?>
    <?php if($this->options->StaticFile == 'CDN' && $this->options->CDNURL == ''): ?>
        <script src="https://<?php $this->options->jsdelivrLink() ?>/gh/stevenjoezhang/live2d-widget@latest/autoload.js"></script>
    <?php elseif($this->options->StaticFile == 'CDN' && $this->options->CDNURL !== ''): ?>    
        <script src="<?php $this->options->CDNURL() ?>/static/js/autoload.js"></script>
   <?php else : ?> 
        <script src="<?php $this->options->themeUrl('/static/js/autoload.js'); ?>"></script>
       <?php endif; ?>    
<?php endif; ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/custom.main.js'); ?>"></script>
<script><?php $this->options->CustomScript() ?></script>
 <?php $this->options->CustomBodyEnd() ?>
<!--搜索  -->
<div id="local-search">
  <div class="search-dialog">
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
<div class="js-pjax">
<?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch',$this->options->beautifyBlock)): ?>
<script>
(function() {
const searchButton = document.getElementById('search-button');
const input = document.getElementById('dSearchIn');
searchButton.addEventListener('click', function() {
  input.style.width = '150px';
  input.focus();
});
input.addEventListener('blur', function() {
  input.style.width = '35px';
});
})()
</script>
<?php endif ?>
<?php if ($this->options->hcaptchaSecretKey !== "" && $this->options->hcaptchaAPIKey !== ""): ?> 
<script src="https://hcaptcha.com/1/api.js" async defer></script>
<?php endif ?> 
<?php if ($this->is('post') || $this->is('page')): ?>
<script src="<?php $this->options->themeUrl('js/comjs.js?v1.4.3'); ?>"></script>
<?php endif ?>
<?php if (!empty($this->options->beautifyBlock) && in_array('showButterflyClock',$this->options->beautifyBlock)): ?>
<script data-pjax>function butterfly_clock_anzhiyu_injector_config() {
var a = document.getElementsByClassName("sticky_layout")[0];
a && a.insertAdjacentHTML("afterbegin", '<div class="card-widget card-clock"><div class="card-glass"><div class="card-background"><div class="card-content"><div id="hexo_electric_clock"><img class="entered loading" id="card-clock-loading" src= "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-lazy-src="https://cdn.cbd.int/hexo-butterfly-clock-anzhiyu/lib/loading.gif" style="height: 120px; width: 100%;" data-ll-status="loading"/></div></div></div></div></div>')}
for (var elist = "null".split(","), cpage = location.pathname, epage = "all", 
qweather_key = "<?php $this->options->qweather_key() ?>", 
gaud_map_key = "<?php $this->options->gaud_map_key() ?>", 
baidu_ak_key = "undefined", flag = 0,
clock_rectangle = "112.6534116,27.96920845", clock_default_rectangle_enable = "false", i = 0; i < elist.length; i++) cpage.includes(elist[i]) && flag++;
      "all" === epage && 0 == flag ? butterfly_clock_anzhiyu_injector_config() : epage === cpage && butterfly_clock_anzhiyu_injector_config()</script>
<script src="https://widget.qweather.net/simple/static/js/he-simple-common.js?v=2.0"></script>
<script data-pjax src="https://cdn.cbd.int/hexo-butterfly-clock-anzhiyu/lib/clock.min.js"></script>
<link rel="stylesheet" href="https://cdn.cbd.int/hexo-butterfly-clock-anzhiyu/lib/clock.min.css">
<?php endif ?>
<?php $this->header('commentReply=1&description=0&keywords=0&generator=0&template=0&pingback=0&xmlrpc=0&wlw=0&rss2=0&rss1=0&antiSpam=0&atom'); ?>
<?php if ($this->options->NewTabLink == 'on'): ?>
<script>$(document).ready(function(){var a=document.getElementsByTagName("a");for(let i=0;i<a.length;i++){let domain=document.domain;let url=a[i].href;if(typeof(url)!="undefined"&&url.length!=0&&url.match(domain)==null&&url!="javascript:void(0);"){a[i].setAttribute("target","_BLANK")}}});</script>
<?php endif; ?>        
<?php if($this->is('index')): ?>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/wehao.js?v1.7.6'); ?>"></script>
<style>#page-header:not(.not-top-img):before {background-color: rgba(0,0,0,0);}</style>
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
"function"==typeof Typed?subtitleType():getScript("/usr/themes/butterfly/js/typed.min.js")
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
"function"==typeof Typed?subtitleType():getScript("/usr/themes/butterfly/js/typed.min.js")
.then(subtitleType)
</script>
    <?php endif ?>
    <?php endif?>
<!--打字end-->
<!--判断主页end-->
<?php endif?>
<?php if(!empty($this->options->slide_cids) && $this->is('index')):?>
<script data-pjax>
function butterfly_swiper_injector_config() {
var parent_div_git = document.getElementById('recent-posts');
var item_html = `<div class="recent-post-item" style="height: auto;width: 100%"><div class="blog-slider swiper-container-fade swiper-container-horizontal" id="swiper_container"><div class="blog-slider__wrp swiper-wrapper" style="transition-duration: 0ms;">
<?php 
$slide_cids=$this->options->slide_cids;
$cid = explode(',', strtr($slide_cids, ' ', ','));
$num=count($cid);
$html="";
for($i=0;$i<$num;$i++){
$this->widget('Widget_Archive@post'.$i, 'pageSize=1&type=post', 'cid='.$cid[$i])->to($ji);
$html=$html.'<div class="blog-slider__item swiper-slide" style="width: 750px; opacity: 1; transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;"><a class="blog-slider__img" href="'.$ji->permalink.'" alt=""><img width="48" height="48" src="'.get_ArticleThumbnail($ji).'" alt="" /></a><div class="blog-slider__content"><span class="blog-slider__code">'.date('Y-m-d', $ji->modified).'</span><a class="blog-slider__title" href="'.$ji->permalink.'" alt="'.$ji->title.'">'.$ji->title.'</a><div class="blog-slider__text">'.$ji->title.'</div><a class="blog-slider__button" href="'.$ji->permalink.'" alt="">详情</a></div></div>';}echo $html;
?>
</div><div class="blog-slider__pagination swiper-pagination-clickable swiper-pagination-bullets"></div></div></div>`;
parent_div_git.innerHTML=item_html+parent_div_git.innerHTML // 无报错，但不影响使用(支持pjax跳转)
// parent_div_git.insertAdjacentHTML("afterbegin", item_html) // 有报错，但不影响使用(支持pjax跳转)
}
if (document.getElementById('recent-posts') && (location.pathname === 'all' || 'all' === 'all')) {
butterfly_swiper_injector_config()}
</script>
<script  src="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiper.min.js"></script>
<script data-pjax src="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiper_init.js"></script>
<link rel="stylesheet" href="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiperstyle.css">
<link rel="stylesheet" href="https://npm.elemecdn.com/hexo-butterfly-swiper/lib/swiper.min.css">
<?php endif?>
</div>
<!--js-pjax end-->
<div id="rightside" style="">
	<div id="rightside-config-hide" class="">
	    <button id="font-plus" type="button" title="放大字体"><i class="fas fa-plus"></i></button>	    
	    <button id="font-minus" type="button" title="缩小字体"><i class="fas fa-minus"></i></button>	    
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
		<?php if($this->is('post') && $this->allow('comment') || $this->is('page') && $this->allow('comment')): ?>
		<a id="to_comment" href="#comments" title="直达评论">
			<i class="fas fa-comments">
			</i>
		</a>
		<?php endif ?>
		<button id="go-up" type="button" title="回到頂部" class="show-percent">
			<span class="scroll-percent"></span>
			<i class="fas fa-arrow-up">
			</i>
		</button>
	</div>
</div>
<!--pjax-->
<?php if($this->options->EnablePjax === 'on') : ?>
<?php if($this->options->StaticFile == 'CDN' && $this->options->CDNURL == ''): ?>
<link rel="stylesheet" href="https://<?php $this->options->jsdelivrLink() ?>/gh/rstacruz/nprogress@master/nprogress.css">
<script src="https://<?php $this->options->jsdelivrLink() ?>/gh/rstacruz/nprogress@master/nprogress.js"></script>
<script src="https://<?php $this->options->jsdelivrLink() ?>/npm/pjax/pjax.min.js"></script>
<?php elseif($this->options->StaticFile == 'CDN' && $this->options->CDNURL !== ''): ?>
<link rel="stylesheet" href="https://lib.baomitu.com/nprogress/0.2.0/nprogress.css">
<script src="https://lib.baomitu.com/nprogress/0.2.0/nprogress.js"></script>
<script src="<?php $this->options->CDNURL() ?>/static/js/pjax.min.js"></script>
<?php else: ?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('static/css/nprogress.css'); ?>">
<script src="<?php $this->options->themeUrl('static/js/nprogress.js'); ?>"></script>
<script src="<?php $this->options->themeUrl('static/js/pjax.min.js'); ?>"></script>
<?php endif; ?>
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
    <?php if ($this->options->hcaptchaSecretKey !== "" && $this->options->hcaptchaAPIKey !== ""): ?> 
    hcaptcha.render('h-captcha', {
        sitekey: '<?php $this->options->hcaptchaSecretKey() ?>'
    });
    <?php endif ?> 
    <?php $this->options->PjaxCallBack() ?>
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
    coverShow()
})),
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