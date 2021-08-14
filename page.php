<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('page_header.php'); ?>
<main class="layout" id="content-inner">
<div id="page"> 
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
        
    </article>
    <?php $this->need('comments.php'); ?>
</div><!-- end #main-->
<?php $this->need('sidebar.php'); ?>
	</main>
	</div>
	</div>
	</div>
<?php $this->need('footer.php'); ?>