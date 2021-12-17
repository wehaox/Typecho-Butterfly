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
<?php if ($this->options->NewTabLink == 'on'): ?>
<script>
 $(document).ready(function(){var a=document.getElementsByTagName("a");for(let i=0;i<a.length;i++){let domain=document.domain;let url=a[i].href;if(typeof(url)!="undefined"&&url.length!=0&&url.match(domain)==null){a[i].setAttribute("target","_BLANK")}}})
</script>
<?php endif; ?>
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
<div id="rightside">
	<div id="rightside-config-hide">
		<button id="font-plus" type="button" title="放大字体">
			<i class="fas fa-plus">
			</i>
		</button>
		<button id="font-minus" type="button" title="缩小字体">
			<i class="fas fa-minus">
			</i>
		</button>
		<button id="translateLink" type="button" title="简繁转换">
			簡
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
		<button id="go-up" type="button" title="回到顶部">
			<i class="fas fa-arrow-up">
			</i>
		</button>
	</div>
</div>
<!--搜索  -->
<div id="local-search">
    <div class="search-dialog">
      <div class="search-dialog__title" id="local-search-title">本地搜索</div>
      <div id="local-input-panel">
        <div id="local-search-input">
          <div class="local-search-box">
              <form  id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                    <label for="s" class="sr-only"><?php _e('搜索关键字'); ?></label>
                    <input type="text" id="s" name="s" class="text" placeholder="<?php _e('输入关键字搜索'); ?>" />
                    <!--<button type="submit" class="submit"><?php _e('搜索'); ?></button>-->
                </form>
              </div>
        </div>
      </div>
      <hr>
      <div id="local-search-results"></div>
      <span class="search-close-button"><i class="fas fa-times"></i></span>
    </div>
    <div id="search-mask"></div>
  </div>
<!--搜索  -->
<script type="text/javascript">
    $(document).ready(function () {
        $( ".fancybox").fancybox();
    });
</script>
</body>
</html>