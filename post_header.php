<?php  $this->need('header_com.php'); ?>
<body style="zoom: 1;">
    <div id="web_bg"></div>
<div class="page" id="body-wrap">
    <?php if (is_array($this->options->beautifyBlock) && in_array('PostShowTopimg',$this->options->beautifyBlock)): ?>
     <header id="page-header" class="post-bg" style="background-image: url(<?php GetRandomThumbnailPost($this); ?>)">
            <div id="post-info">
          <h1 class="post-title"><?php $this->title() ?>
          <?php if($this->user->hasLogin()):?>
          <a class="post-edit-link" href="<?php $this->options->adminUrl(); ?>write-post.php?cid=<?php echo $this->cid;?>" title="編輯" target="_blank"><i class="fas fa-pencil-alt"></i></a><?php endif;?>
          </h1>
          <div id="post-meta">
              <div class="meta-firstline">
              <i class="far fa-calendar-alt fa-fw post-meta-icon"></i>
              <span class="post-meta-label">发表于</span><?php echo $this->date('Y-m-d');?>
              <span class="post-meta-separator">|</span>
              <i class="fas fa-history fa-fw post-meta-icon"></i>
              <span class="post-meta-label">更新于</span>
              <?php echo date('Y-m-d' , $this->modified);?>
              <span class="post-meta-separator">|</span>
              <i class="fas fa-inbox fa-fw post-meta-icon"></i>
              <?php $this->category(' '); ?>  
              </div>
              <div class="meta-secondline">
                <span class="post-meta-separator">|</span>
                  <i class="far fa-file-word fa-fw post-meta-icon"></i>
              <span class="post-meta-label">字数总计:</span>
              <span class="word-count"><?php art_count($this->cid); ?></span>
              <span class="post-meta-separator">|</span>
              <i class="far fa-clock fa-fw post-meta-icon"></i>
              <span class="post-meta-label">阅读时长:</span>
              <span><?php echo art_time($this->cid); ?>分钟</span>
              <span class="post-meta-separator">|</span>
              <span class="post-meta-pv-cv"><i class="far fa-eye fa-fw post-meta-icon"></i><span class="post-meta-label">阅读量:</span><span id="busuanzi_value_page_pv"><?php get_post_view($this) ?></span></span>
               </div>
          </div>
        </div>
<?php else: ?>
<header class="not-top-img" id="page-header">
<?php endif; ?>   
<?php  $this->need('public/nav.php'); ?>
</header>