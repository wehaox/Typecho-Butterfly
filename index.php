<?php
/**
 * <span><script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>主题最新版本：<span id="latest">获取中...</span><script>$(document).ready(function() {$.get("https://typecho.wehao.ml", function(data) { $("#latest").text(data.ver);});});</script></span>
 * 这是 Typecho 版本的 butterfly 主题
 * 主题为移植至Typecho，你可以使用自己原先博客的index.css文件，拥有原先相同效果
 * <a href="https://www.wehaox.com">个人网站</a>
 * <a href="https://blog.wehaox.com/archives/typecho-butterfly.html">主题详细使用说明</a>
 * <a href="https://blog.wehaox.com/archives/blogtheme.html#cl-4">主题更新日志</a>
 * @package Typecho-Butterfly
 * @author b站:wehao-
 * @version 1.0.0
 * @link https://space.bilibili.com/34174433
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/** 文章置顶 */
$sticky = $this->options->sticky_cids;
if($sticky && $this->is('index') || $this->is('front')){
    $sticky_cids = explode(',', strtr($sticky, ' ', ','));//分割文本 
    $sticky_html = 
"<span class='article-meta'>
<i class='fas fa-thumbtack article-meta__icon sticky'></i>
<span class='sticky'>置顶</span>
<span class='article-meta__separator'>|</span>
</span>";
    $db = Typecho_Db::get();
    $select1 = $this->select()->where('type = ?', 'post');
    $select2 = $this->select()->where('type = ? && status = ? && created < ?', 'post','publish',time());
    //清空原有文章的列队
    $this->row = [];
    $this->stack = [];
    $this->length = 0;
    $order = '';
    foreach($sticky_cids as $i => $cid) {
        if($i == 0) $select1->where('cid = ?', $cid);
        else $select1->orWhere('cid = ?', $cid);
        $order .= " when $cid then $i";
        $select2->where('table.contents.cid != ?', $cid); //避免重复
    }
    if ($order) $select1->order(null,"(case cid$order end)"); //置顶文章的顺序 按 $sticky 中 文章ID顺序
    if ($this->_currentPage == 1) foreach($db->fetchAll($select1) as $sticky_post){ //首页第一页才显示
        $sticky_post['sticky'] = $sticky_html;
        $this->push($sticky_post); //压入列队
    }
$uid = $this->user->uid; //登录时，显示用户各自的私密文章
    if($uid) $select2->orWhere('authorId = ? && status = ?',$uid,'private');
    $sticky_posts = $db->fetchAll($select2->order('table.contents.created', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
    foreach($sticky_posts as $sticky_post) $this->push($sticky_post); //压入列队
    $this->setTotal($this->getTotal()-count($sticky_cids)); //置顶文章不计算在所有文章内
}
?>
<?php  $this->need('header.php'); ?>
     <script type="text/javascript" src="<?php $this->options->themeUrl('js/custom.js'); ?>"></script>
<main class="layout" id="content-inner">
<div class="recent-posts" id="recent-posts">
	<?php while($this->next()): ?>
        <div class="recent-post-item" >
           <wehao class="post_cover" id="post_rl">
               <a  class="article-title" href="<?php $this->permalink() ?>">     
<img class="post_bg" data-lazy-src="<?php echo get_ArticleThumbnail($this);?>" src="<?php echo GetLazyLoad() ?>" onerror="this.onerror=null;this.src='https://tva1.sinaimg.cn/large/007X0Rdyly1gpaaf55n1rj30ic09u0sw.jpg'">
               </a>
            </wehao>
              <div class="recent-post-info">
			    <a  class="article-title" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
			    <div class="article-meta-wrap">
			         <?php $this->sticky();?>
			   <span class="post-meta-date">
			        <i class="far fa-calendar-alt"></i>
				    <?php _e('发表于  '); ?> <?php $this->date(); ?>
				    <time class="post-meta-date-created" datetime="<?php $this->date('c'); ?>">
				    </time>
				     <span class="article-meta__separator">|</span>
				</span>
				<i class="fas fa-history"></i>
				<span class="article-meta-label">更新于</span>
				  <?php echo date('Y-m-d' , $this->modified);?>
				<time class="post-meta-date-updated" datetime="<?php echo date('Y-m-d' , $this->modified);?>" title="更新于 ">
				</time>
				<span class="article-meta">
				    <span class="article-meta__separator">|</span>
				    <i class="fas fa-inbox article-meta__icon"></i>
				  <span class="post-meta-date">
				    <?php _e('分类: '); ?>
				    <?php $this->category(' '); ?>
				</span>
				    	<span class="article-meta__separator">|</span>
			     <span class="post-meta-date" itemprop="author">
				    <?php _e('作者: '); ?>
				<a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author">
				    <?php $this->author(); ?>
				    </a>
				</span>
					<span class="article-meta__separator">|</span>
				<i class="fas fa-comments"></i>
			     <span class="post-meta-date" itemprop="interactionCount">
				    <a itemprop="discussionUrl" href="<?php $this->permalink() ?>#comments">
				        <?php $this->commentsNum('0条评论', '1 条评论', '%d 条评论'); ?>
				    </a>
				</span>
				</span>
			    </div>
			    <div class="content">
			    <?php if($this->fields->excerpt && $this->fields->excerpt!='') {
				    echo $this->fields->excerpt;
				  }else{
					echo $this->excerpt(130);
				  }
			       echo '<br><br><a href="',$this->permalink(),'" title="',$this->title(),'">阅读全文...</a>';
			        ?>
                   </div>
            </div>
        </div>
	<?php endwhile; ?>
 <?php $this->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', array('wrapTag' => 'nav', 'wrapClass' => 'page-nav', 'itemTag' => '', 'prevClass' => 'extend prev', 'nextClass' => 'extend next', 'currentClass' => 'page-number current' )); ?>
</div>
<?php $this->need('sidebar.php'); ?>
</main>
<div id="rightside">
	<div id="rightside-config-hide" class="">
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
		<button id="go-up" type="button" title="回到顶部">
			<i class="fas fa-arrow-up">
			</i>
		</button>
	</div>
</div>
<script>
console.log(`
===================================================================
                                                                             
    #####  #    # ##### ##### ###### #####  ###### #      #   #    
    #    # #    #   #     #   #      #    # #      #       # #     
    #####  #    #   #     #   #####  #    # #####  #        #     
    #    # #    #   #     #   #      #####  #      #        #      
    #    # #    #   #     #   #      #   #  #      #        #    
    #####   ####    #     #   ###### #    # #      ######   #  
    
                           1.0.0
===================================================================
`);
</script>
<?php $this->need('footer.php'); ?>