<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div class="aside-content" id="aside-content" role="complementary">
<?php if (!empty($this->options->PostSidebarBlock) && in_array('ShowAuthorInfo', $this->options->PostSidebarBlock)): ?>  
   <div class="card-widget card-info">
	<div class="card-info-avatar is-center">
	     <div class="avatar-img">
	         <img data-lazy-src="<?php $this->options->logoUrl() ?>" onerror="this.onerror=null;this.src='/usr/themes/butterfly/img/friend_404.gif'" src="<?php echo GetLazyLoad() ?>" alt="avatar">
	      </div>
		<div class="author-info__name">
			<?php $this->author(); ?>
		</div>
		<div class="author-info__description">
			<?php $this->options->author_description() ?>
		</div>
	</div>
	<div class="card-info-data">
		<div class="card-info-data site-data is-center">
			<a href="<?php $this->options->archivelink() ?>">
				<div class="headline">文章</div>
				<div class="length-num">
				<?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->publishedPostsNum() ?>
				</div>
			</a>
			<a href="<?php $this->options->tagslink() ?>">
				<div class="headline">标签</div>
				<div class="length-num"><?php echo tagsNum(); ?></div>
			</a>
			<a href="<?php $this->options->categorylink() ?>">
				<div class="headline">
					分类
				</div>
				<div class="length-num">
					<?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->categoriesNum() ?>
				</div>
			</a>
		</div>
	</div>
	<a class="button--animated" id="card-info-btn" target="_blank" rel="noopener" href="<?php $this->options->author_site() ?>">
		<i class="fas fa-link">
		</i>
		<span>
		<?php $this->options->author_site_description() ?>
		</span>
	</a>
	<?php if($this->options->author_bottom != null) : ?>
	<div class="card-info-social-icons is-center"><?php $this->options->author_bottom() ?></div>
    <?php elseif(!$this->options->author_bottom) : ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if (!empty($this->options->PostSidebarBlock) && in_array('ShowAnnounce', $this->options->PostSidebarBlock)): ?> 
    <div class="card-widget card-announcement"><div class="item-headline">
        <i class="fas fa-bullhorn card-announcement-animation"></i><span>公告</span></div>
    <div class="announcement_content"><?php $this->options->announcement() ?></div></div>
<?php endif; ?>
<?php if (!empty($this->options->AD)): ?>
    <div class="card-widget">
        <div class="item-headline"><i class="fa-solid fa-rectangle-ad"></i><span>广告</span></div>
    <div><?php $this->options->AD() ?></div>
    </div>
<?php endif; ?>
<div class="sticky_layout">
    <div class="card-widget" id="card-toc">
  <div class="item-headline">
    <i class="fas fa-stream"></i>
    <span>目录</span>
    <span class="toc-percentage"></span>
    </div>
  <div class="toc-content">
    <ol class="toc">
         <?php getCatalog(); ?>
    </ol>
  </div>
</div>
<!--微博热搜-->
<?php if (!empty($this->options->PostSidebarBlock) && in_array('ShowWeiboHot', $this->options->PostSidebarBlock)): ?>
<div class="card-widget card-weibo wow animate__zoomIn" data-wow-duration="2s" data-wow-delay="200ms" data-wow-offset="30" data-wow-iteration="1" style="visibility: visible; animation-duration: 2s; animation-delay: 200ms; animation-iteration-count: 1; animation-name: zoomIn;">
  <div class="card-content">
    <div class="item-headline">
      <i class="fab fa-weibo"></i>
      <span>微博热搜</span></div>
    <div id="weibo-container" style="width:100%;height:150px;font-size:95%">
      <style>.weibo-new{background:#ff3852}.weibo-hot{background:#ff9406}.weibo-jyzy{background:#ffc000}.weibo-recommend{background:#00b7ee}.weibo-adrecommend{background:#febd22}.weibo-friend{background:#8fc21e}.weibo-boom{background:#bd0000}.weibo-topic{background:#ff6f49}.weibo-topic-ad{background:#4dadff}.weibo-boil{background:#f86400}#weibo-container{overflow-y:auto;-ms-overflow-style:none;scrollbar-width:none}#weibo-container::-webkit-scrollbar{display:none}.weibo-list-item{display:flex;flex-direction:row;justify-content:space-between;flex-wrap:nowrap}.weibo-title{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-right:auto}.weibo-num{float:right}.weibo-hotness{display:inline-block;padding:0 6px;transform:scale(.8) translateX(-3px);color:#fff;border-radius:8px}</style>
      <div class="weibo-list">
          <?php echo weibohot() ?> 
      </div>
    </div>
  </div>
</div>
<?php endif ?>
<!--微博热搜end-->
<?php if (!empty($this->options->PostSidebarBlock) && in_array('ShowRecentPosts', $this->options->PostSidebarBlock)): ?>
    <div class="card-widget card-recent-post">
       <div class="item-headline">
            <i class="fas fa-history"></i><span><?php _e('最新文章'); ?></span>
        </div>
      <div class="aside-list">
            <?php $this->widget('Widget_Contents_Post_Recent')->to($contents); ?>
            <?php while($contents->next()): ?>
             <div class="aside-list-item"> 
               <a class="thumbnail" href="<?php $contents->permalink() ?>" title="<?php $contents->title() ?>" >
               <img onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" data-lazy-src="<?php GetRandomThumbnail($contents); ?> " src="<?php echo GetLazyLoad() ?>" 
               alt="<?php $contents->title() ?>">
               </a>
               <div class="content">
                  <a class="title" href="<?php $contents->permalink() ?>"><?php $contents->title() ?></a>
                  
                  <time datetime="<?php $this->date(); ?>" title="发表于 <?php $this->date(); ?>">
                      <?php $contents->date() ?></time>
               </div>
              </div>
             <?php endwhile; ?>
       </div>
    </div>
    <?php endif; ?>
<?php if (!empty($this->options->PostSidebarBlock) && in_array('ShowWebinfo', $this->options->PostSidebarBlock)): ?>
  <div class="card-widget card-webinfo">
     <div class="item-headline">
   <i class="fas fa-chart-line"></i>
    <span>网站资讯</span></div>
     <div class="webinfo">
   <div class="webinfo-item">
      <div class="item-name">文章数目 :</div>
      <div class="item-count">
          	<?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->publishedPostsNum() ?>
           </div></div>
    <div class="webinfo-item">
      <div class="item-name">已运行时间 :</div>
      <div class="item-count" id="runtimeshows" data-publishdate="">
          <?php echo RunTime()?>          
     </div></div>
    <div class="webinfo-item">
      <div class="item-name">本站总字数 :</div>
      <div class="item-count">
         <?php allOfCharacters(); ?>
          </div></div>
<?php if ($this->options->ShowOnlinePeople == 'on'): ?>                 
    <div class="webinfo-item">
      <div class="item-name">当前在线人数 :</div>
      <div class="item-count" >
      <?php onlinePeople();?>
      </div></div>
<?php endif; ?>      
    <div class="webinfo-item">
      <div class="item-name">本站总访问量 :</div>
      <div class="item-count" >
      <?php theAllViews();?>
      </div></div>
    <div class="webinfo-item">
      <div class="item-name">最后更新时间 :</div>
     <div class="item-count" >
         <?php get_last_update(); ?>
      </div></div>
  </div>
  </div>
 <?php endif; ?>
    <?php if (!empty($this->options->PostSidebarBlock) && in_array('ShowOther', $this->options->PostSidebarBlock)): ?>
	<div class="card-widget">
	    <div class="item-headline">
            <i class="fas fa-user"></i><span><?php _e('用户'); ?></span></div>
        <div class="widget-list">
            <?php if($this->user->hasLogin()): ?>
				<div  class="last"><a href="<?php $this->options->adminUrl(); ?>"><?php _e('进入后台'); ?> (<?php $this->user->screenName(); ?>)</a></div >
                <div class="last"><a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?></a></div >
            <?php else: ?>
                <div  class="last"><a href="<?php $this->options->adminUrl('login.php'); ?>"><?php _e('登录'); ?></a></div >
            <?php endif; ?>
            <div class="last"><a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 RSS'); ?></a></div >
            <div class="last"><a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 RSS'); ?></a></div >
        </div >
	</div>
    <?php endif; ?>
</div>
</div><!-- end #sidebar -->