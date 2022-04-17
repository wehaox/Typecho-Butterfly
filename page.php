<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('page_header.php'); ?>
<main class="layout" id="content-inner">
<div id="page" >
    <?php if (is_array($this->options->beautifyBlock) && !in_array('PageShowTopimg',$this->options->beautifyBlock)): ?>
    <h1 class="page-title"><?php $this->archiveTitle(array(
            'category'  =>  _t(' %s '),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ''); ?></h1>
    <?php endif ;?>
    <article class="post-content" id="article-container">
       <?php
        $db = Typecho_Db::get();
        $sql = $db->select()->from('table.comments')
         ->where('cid = ?', $this->cid)
         ->where('mail = ?', $this->remember('mail', true))
         ->limit(1);
         $result = $db->fetchAll($sql);
         if ($this->user->hasLogin() || $result) {
         $content = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '<div class="reply-content">$1</div>', $this->content);
         } else {
         $content = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '<p class="need-reply">此处内容 <a href="#comments">回复</a> 可见</p>', $this->content);
         }
        echo $content;
       ?>
    </article>
    <?php $this->need('comments.php'); ?>
</div>
<?php $this->need('post_sidebar.php'); ?>
<script src="<?php $this->options->themeUrl('js/comjs.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/prism.js?v1.0'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/clipboard.min.js'); ?>"></script>
<script>
$(document).ready(function(){var tocState = $(".toc").html();if(tocState.length == "1") {
$("#card-toc,#mobile-toc-button").remove();}});
</script>
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
<?php if($this->fields->ShowToc === 'off') : ?>
<style>#card-toc,#mobile-toc-button{display: none!important;}</style>
<?php endif?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>