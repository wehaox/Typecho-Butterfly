<?php  $this->need('header_com.php'); ?>
<script id="config-diff">var GLOBAL_CONFIG_SITE = { 
		    isPost: !0, 
		    isHome: !0, 
		    isHighlightShrink: !0, 
		    isToc: !0, 
		   }
</script>
<script id="config_change">var GLOBAL_CONFIG_SITE = {
    isPost: !0, 
    isHome: !0, 
    isHighlightShrink: !1, 
    isToc: !0, 
    }
</script>
<div id="rightside">
	<div id="rightside-config-hide" class="">
		<button id="readmode" type="button" title="阅读模式">
			<i class="fas fa-book-open">
			</i>
		</button>
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
		<button class="close" id="mobile-toc-button" type="button" title="目录">
			<i class="fas fa-list-ul">
			</i>
		</button>
		<a id="to_comment" href="#post-comment" title="直达评论">
			<i class="fas fa-comments">
			</i>
		</a>
		<button id="go-up" type="button" title="回到顶部">
			<i class="fas fa-arrow-up">
			</i>
		</button>
	</div>
</div>
<body style="zoom: 1;">
    <div id="web_bg"></div>
<div class="page" id="body-wrap">
    <?php if (is_array($this->options->beautifyBlock) && in_array('PostShowTopimg',$this->options->beautifyBlock)): ?>
     <header id="page-header" class="post-bg" style="background-image: url(<?php GetRandomThumbnailPost($this); ?>)">
            <div id="post-info">
          <h1 class="post-title"><?php $this->title() ?></h1>
          <div id="post-meta">
              <div class="meta-firstline">
              <i class="far fa-calendar-alt fa-fw post-meta-icon"></i>
              <?php _e('发表于: '); ?><time datetime="<?php $this->date('c'); ?>">
                  <?php $this->date('c'); ?></time><?php $this->date(); ?>
              <span class="post-meta-separator">|</span>
              <i class="fas fa-history fa-fw post-meta-icon"></i>
              <span class="post-meta-label">更新于</span>
              <time class="post-meta-date-updated" datetime="" title="更新于 <?php echo date('Y-m-d' , $this->modified);?>"><?php echo date('Y-m-d' , $this->modified);?>
              </time><?php echo date('Y-m-d' , $this->modified);?>
              <span class="post-meta-separator">|</span>
              <i class="fas fa-inbox fa-fw post-meta-icon"></i>
              <?php $this->category(' '); ?>  
              </div>
              <div class="meta-secondline">
                <span class="post-meta-separator">|</span>
                  <i class="far fa-file-word fa-fw post-meta-icon"></i>
              <?php _e('字数统计: ');?>
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
        <nav id="nav" class="show" >
         <span id="blog_name">
            <a id="site-name" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a>
       </span>
        <div id="menus">
              <div id="search-button">
                  <a class="site-page social-icon search">
                  <i class="fas fa-search fa-fw"></i>
                  <span> 搜索</span></a>
                  </div>
                        <div id="toggle-menu"><a class="site-page"><i class="fas fa-bars fa-fw"></i></a></div>
                <div class="menus_items">
                     <div class="menus_item">
                    <a class="site-page"
                    <?php if($this->is('index')): ?> 
                    <?php endif; ?> 
                    href="<?php $this->options->siteUrl(); ?>">
                <li class="fa-fw fas fa-home"></li><?php _e('首页'); ?></a>
                      </div>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while($pages->next()): ?>
                    <div class="menus_item">
                    <a<?php if($this->is('page', $pages->slug)): ?> class="site-page" <?php endif; ?> class="site-page" href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>">
                        <?php if($this->is($pages->title == "友链")){
                            echo"<i class='fa-fw fas fa-link'></i>";
                        }
                        elseif($this->is($pages->title == "关于")){
                             echo"<li class='fa-fw fas fa-heart'></li>";
                        }
                        elseif($this->is($pages->title=="留言")){
                             echo"<i class='fa-fw fas fa-comment-dots'></i>";
                        }
                        elseif($this->is($pages->title=="归档")){
                             echo"<i class='fa-fw fas fa-archive'></i>";
                        }
                        elseif($this->is($pages->title=="标签")){
                             echo"<i class='fa-fw fas fa-tags'></i>";
                        }
                        elseif($this->is($pages->title=="分类")){
                             echo"<i class='fa-fw fas fa-folder-open'></i>";
                        }elseif($this->is($pages->title=="留言板")){
                             echo"<i class='fa-fw fa fa-comment-dots'></i>";
                        }else{
                            echo"<i class='fa-fw fa fa-coffee'></i>";
                        }                        
                        ?>  
                        <?php $pages->title(); ?>
                        </a>
                    <?php endwhile; ?>
                </div>  
            </div>
        </div>
    </nav>
</header>
<?php else: ?>
<header class="not-top-img" id="page-header">
      <nav id="nav" class="show" >
        <span id="blog_name">
            <a id="site-name" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a>
        </span>
        <div id="menus">
                <div id="search-button">
                  <a class="site-page social-icon search">
                  <i class="fas fa-search fa-fw"></i>
                  <span> 搜索</span></a>
                </div>
            <div id="toggle-menu"><a class="site-page"><i class="fas fa-bars fa-fw"></i></a></div>
            <div class="menus_items">
                     <div class="menus_item">
                    <a class="site-page"
                    <?php if($this->is('index')): ?> 
                    <?php endif; ?> 
                    href="<?php $this->options->siteUrl(); ?>">
                <li class="fa-fw fas fa-home"></li><?php _e('首页'); ?></a>
                      </div>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while($pages->next()): ?>
                    <div class="menus_item">
                    <a<?php if($this->is('page', $pages->slug)): ?>  <?php endif; ?> 
                    class="site-page" href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>">
                        <?php if($this->is($pages->title == "友链")){
                            echo"<i class='fa-fw fas fa-link'></i>";
                        }
                        elseif($this->is($pages->title == "关于")){
                             echo"<li class='fa-fw fas fa-heart'></li>";
                        }
                        elseif($this->is($pages->title=="留言")){
                             echo"<i class='fa-fw fas fa-comment-dots'></i>";
                        }
                        elseif($this->is($pages->title=="归档")){
                             echo"<i class='fa-fw fas fa-archive'></i>";
                        }
                        elseif($this->is($pages->title=="标签")){
                             echo"<i class='fa-fw fas fa-tags'></i>";
                        }
                        elseif($this->is($pages->title=="分类")){
                             echo"<i class='fa-fw fas fa-folder-open'></i>";
                        }else{
                            echo"<i class='fa-fw fa fa-coffee'></i>";
                        }                        
                        ?>  
                        <?php $pages->title(); ?>
                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
<?php endif; ?>