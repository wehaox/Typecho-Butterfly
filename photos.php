<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php
/**
    * 照片墙
    *
    * @package custom
    */
$GLOBALS['BUTTERFLY_DISABLE_TOC'] = true;
$this->need('includes/page_header.php');
?>
<main class="layout" id="content-inner">
<div id="page">
    <article class="post-content" id="article-container">
       <?php
        $hasCommented = false;
        $rememberMail = $this->remember('mail', true);
        if (!empty($rememberMail)) {
            $db = Typecho_Db::get();
            $sql = $db->select('coid')->from('table.comments')
             ->where('cid = ?', $this->cid)
             ->where('mail = ?', $rememberMail)
             ->limit(1);
            $result = $db->fetchRow($sql);
            $hasCommented = !empty($result);
        }
         $content = replaceHideContent($this->content, $this->user->hasLogin() || $hasCommented);
        echo $content;
       ?>
    </article>
    <?php $this->need('includes/comments.php'); ?>
</div>
<?php $this->need('includes/sidebar.php'); ?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>
