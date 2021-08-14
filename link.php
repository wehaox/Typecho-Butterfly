<?php
/**
 * 友情链接
 *
 * @package custom
 *
 */$this->need('page_header.php');
?>
<main class="layout" id="content-inner">
    <div id="page"> 
    <div class="flink" id="article-container">
        <div class="flink-list">
            <div class="flink" id="article-container">
                <div class="flink-list">
       <?php if ($this->options->Friends) : ?>
        <?php
        if (strpos($this->options->Friends, '||') !== false) {
            $errorimg="'https://cdn.jsdelivr.net/npm/hexo-butterfly@1.0.0/themes/butterfly/source/img/friend_404.gif'";
            $list = "";
            $txt = $this->options->Friends;
            $string_arr = explode("\r\n", $txt);
            $long = count($string_arr);
            for ($i = 0; $i < $long; $i++) {
                $list = $list . 
                ' <div class="flink-list-item"">
                <a target="_blank" title="' . explode("||", $string_arr[$i])[0] . '" 
                href="' . explode("||", $string_arr[$i])[1] . '">
                <img onerror="this.onerror=null;this.src=' .$errorimg. '" src="'.GetLazyLoad().'" data-lazy-src="' . explode("||", $string_arr[$i])[2] . '" />
                <span class="flink-item-name"> '. explode("||", $string_arr[$i])[0] . ' </span>
                <span class="flink-item-desc" title="' . explode("||", $string_arr[$i])[3] . '">' . explode("||", $string_arr[$i])[3] . '</span>
                </a>
                </div>';
            }
            echo $list;
        }
        ?>
    <?php endif; ?>  
    </div>
    </div>
        </div>
    </div>
<div class="flink" id="article-container">
    <div class="flink-list"><?php $this->content(); ?></div>
</div>
<hr>
<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('sidebar.php'); ?>
</div>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/prism.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/clipboard.min.js'); ?>"></script>
<?php if (!empty($this->options->beautifyBlock) && in_array('showLineNumber',
    $this->options->beautifyBlock)): ?> 
<script type="text/javascript">
	(function(){
		var pres = document.querySelectorAll('pre');
		var lineNumberClassName = 'line-numbers';
		pres.forEach(function (item, index) {
			item.className = item.className == '' ? lineNumberClassName : item.className + ' ' + lineNumberClassName;
		});
	})();
</script>
<?php endif; ?>
<?php $this->need('footer.php'); ?>