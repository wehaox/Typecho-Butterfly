<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<footer id="footer" role="contentinfo">
    <div id="footer-wrap"><div class="copyright">   
    <div class="copyright">©<?php echo date('Y'); ?> By <?php $this->author(); ?></div>
    <div class="framework-info">
        <?php _e('由 <a target="_blank" href="http://www.typecho.org">Typecho</a> 强力驱动'); ?>
        <span class="footer-separator">|</span>
        <span><?php _e('主题 <a target="_blank" href="https://blog.wehaox.com/archives/blogtheme.html">Butterfly</a>'); ?></span>
       </div>
     <div class="footer_custom_text"><?php $this->options->Customfooter() ?></div>
    </div>
</footer><!-- end #footer -->
</div>
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
 <!-- 鼠标点击特效 -->
<?php if ($this->options->CursorEffects !== 'off' &&$this->options->CursorEffects == 'heart') : ?>
<script id="click-heart" src="https://cdn.jsdelivr.net/npm/butterfly-extsrc@1/dist/click-heart.min.js" async="async" mobile="false"></script>
<?php elseif ($this->options->CursorEffects !== 'off' &&$this->options->CursorEffects == 'fireworks') : ?>
<canvas class="fireworks"></canvas>
<script id="fireworks" src="https://cdn.jsdelivr.net/npm/butterfly-extsrc@1.1.0/dist/fireworks.min.js" async="async" mobile="false"></script>
<?php endif; ?>
<?php if ($this->options->ShowLive2D !== 'off' && !isMobile()) : ?>
    <script src="https://cdn.jsdelivr.net/gh/stevenjoezhang/live2d-widget@latest/autoload.js"></script>
<?php endif; ?>
<script><?php $this->options->CustomScript() ?></script>
 <?php $this->options->CustomBodyEnd() ?>
</body>
</html>
