<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>

<div class="aside-content" id="aside-content" role="complementary">
    
   <div class="card-widget card-info">
       
	<div class="card-info-avatar is-center">
     <img  class="avatar-img" data-lazy-src="<?php $this->options->logoUrl() ?>" onerror="this.onerror=null;this.src='https://cdn.jsdelivr.net/npm/hexo-butterfly@1.0.0/themes/butterfly/source/img/friend_404.gif'" 
           src="<?php echo GetLazyLoad() ?>" alt="avatar">
		<div class="author-info__name">
			<?php $this->author(); ?>
		</div>
		<div class="author-info__description">
			<?php $this->options->author_description() ?>
		</div>
	</div>
	<div class="card-info-data">
		<div class="card-info-data-item is-center">
			<a href="<?php $this->options->archivelink() ?>">
				<div class="headline">
					文章
				</div>
				<div class="length-num">
				<?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->publishedPostsNum() ?>
				</div>
			</a>
		</div>
		<div class="card-info-data-item is-center">
			<a href="<?php $this->options->tagslink() ?>">
				<div class="headline">
					标签
				</div>
				<div class="length-num">
			<?php echo tagsNum(); ?>
				</div>
			</a>
		</div>
		<div class="card-info-data-item is-center">
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
    <div class="card-widget card-announcement"><div class="item-headline">
        <i class="fas fa-bullhorn card-announcement-animation"></i><span>公告</span></div>
    <div class="announcement_content"><?php $this->options->announcement() ?></div></div>
         <div class="sticky_layout">
    <div class="card-widget" id="card-toc">
  <div class="item-headline">
    <i class="fas fa-stream"></i>
    <span>目录</span></div>
    
  <div class="toc-content" progress-percentage="">
    <ol class="toc">
         <?php getCatalog(); ?>
    </ol>
  </div>
</div>
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)): ?>
    <div class="card-widget card-recent-post">
       <div class="item-headline">
            <i class="fas fa-history"></i><span><?php _e('最新文章'); ?></span>
        </div>
      <div class="aside-list">
            <?php $this->widget('Widget_Contents_Post_Recent')->to($contents); ?>
            <?php while($contents->next()): ?>
             <div class="aside-list-item"> 
               <a class="thumbnail" href="<?php $contents->permalink() ?>" title="<?php $contents->title() ?>" >
               <img onerror="this.onerror=null;this.src='https://tva1.sinaimg.cn/large/007X0Rdyly1gpaaf55n1rj30ic09u0sw.jpg'" data-lazy-src="<?php GetRandomThumbnail($contents); ?> " src="<?php echo GetLazyLoad() ?>" 
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
<?php if (!empty($this->options->sidebarBlock) && in_array('ShowWebinfo', $this->options->sidebarBlock)): ?>
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
      <div class="item-count" id="runtimeshow" data-publishdate="">
<script language=javascript>
function show_date_time(){
window.setTimeout("show_date_time()", 10);
BirthDay=new Date("<?php $this->options->buildtime() ?>");//建站日期
today=new Date();
timeold=(today.getTime()-BirthDay.getTime());
sectimeold=timeold/1000
secondsold=Math.floor(sectimeold);
msPerDay=24*60*60*1000
e_daysold=timeold/msPerDay
daysold=Math.floor(e_daysold);
runtimeshow.innerHTML=daysold+"天" ;
}
show_date_time();
</script>
     </div></div>
    <div class="webinfo-item">
      <div class="item-name">本站总字数 :</div>
      <div class="item-count">
         <?php allOfCharacters(); ?>
          </div></div>
    <div class="webinfo-item">
      <div class="item-name">当前在线人数 :</div>
      <div class="item-count" >
      <?php onlinePeople();?>
      </div></div>
    <div class="webinfo-item">
      <div class="item-name">本站总访问量 :</div>
      <div class="item-count" >
      <?php echo theAllViews();?>
      </div></div>
    <div class="webinfo-item">
      <div class="item-name">最后更新时间 :</div>
     <div class="item-count" >
         <?php get_last_update(); ?>
      </div></div>
  </div>
  </div>
 <?php endif; ?>
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)): ?>
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