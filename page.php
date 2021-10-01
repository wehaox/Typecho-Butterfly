<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('page_header.php'); ?>
<style>#card-toc,#mobile-toc-button{display: none!important;}</style>
<script src="<?php $this->options->themeUrl('js/comjs.js'); ?>"></script>
<main class="layout" id="content-inner">
<div id="page"> 
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
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
        echo $content
       ?>
    </article>
    <?php $this->need('comments.php'); ?>
</div><!-- end #main-->
<?php $this->need('sidebar.php'); ?>
	</main>
	</div>
	</div>
	</div>
<?php $this->need('footer.php'); ?>