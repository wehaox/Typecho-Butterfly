<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('post_header.php'); ?>
<main class="layout" id="content-inner">
<div id="post" >
<?php if (is_array($this->options->beautifyBlock) && !in_array('PostShowTopimg',$this->options->beautifyBlock)): ?>  
    <div id="post-info">
  <h1 class="post-title"><?php $this->title() ?>
  <?php if($this->user->hasLogin()):?>
  <a style="float: none;"  class="post-edit-link" href="<?php $this->options->adminUrl(); ?>write-post.php?cid=<?php echo $this->cid;?>" title="編輯" target="_blank"><i class="fas fa-pencil-alt"></i></a><?php endif;?></h1>
  <div id="post-meta">
    <div class="meta-firstline">
      <span class="post-meta-date">
        <i class="far fa-calendar-alt fa-fw post-meta-icon"></i>
        <span class="post-meta-label">发表于</span>
        <!--<time class="post-meta-date-created" title="发表于<?php $this->date(); ?>"></time>-->
        <?php $this->date(); ?>
        <span class="post-meta-separator">|</span>
        <i class="fas fa-history fa-fw post-meta-icon"></i>
        <span class="post-meta-label">更新于</span>
        <!--<time class="post-meta-date-updated" title="更新于<?php echo date('Y-m-d' , $this->modified);?>"></time>-->
        <?php echo date('Y-m-d' , $this->modified);?>
        </span>
      <span class="post-meta-categories">
        <span class="post-meta-separator">|</span>
        <i class="fas fa-inbox fa-fw post-meta-icon"></i>
        <a class="post-meta-categories"><?php printTag($this); ?></a>
    </div>
    <div class="meta-secondline">
      <span class="post-meta-separator">|</span>
      <span class="post-meta-wordcount">
        <i class="far fa-file-word fa-fw post-meta-icon"></i>
        <span class="post-meta-label">字数总计:</span>
        <span class="word-count"><?php echo art_count($this->cid); ?></span>
        <span class="post-meta-separator">|</span>
        <i class="far fa-clock fa-fw post-meta-icon"></i>
        <span class="post-meta-label">阅读时长:</span>
        <span><?php echo art_time($this->cid); ?>分钟</span>
        <span class="post-meta-separator">|</span>
        <span class="post-meta-pv-cv"><i class="far fa-eye fa-fw post-meta-icon"></i>
        <span class="post-meta-label">阅读量:</span>
        <span id="busuanzi_value_page_pv"><?php get_post_view($this) ?></span></span>
    </div>
  </div>
</div>
<?php endif; ?>
<?php if($this->fields->showTimeWarning !== 'off'&&(time()-($this->modified))/86400 >= $this->options->outoftime) : ?>
<div class="post-outdate-notice">
<div style="width: 94%;">这篇文章距离最后更新已过<?php echo floor((time()-($this->modified))/86400);?>
天,如果文章内容或图片资源失效，请留言反馈，我会及时处理，谢谢！
</div><a id="close-outdate"><i class="fas fa-times"></i></a></div>  
<script>$("#close-outdate").click(function(){$(".post-outdate-notice").fadeOut(900);;})</script>
<?php endif; ?>
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
<div class="post-copyright">
    <div class="post-copyright__author">
    <span class="post-copyright-meta"> <?php _e('作者: '); ?> </span>
    <span class="post-copyright-info">
        <a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a></span>
        </div>
        <div class="post-copyright__type">
            <span class="post-copyright-meta">文章链接: </span>
            <span class="post-copyright-info"><a href="<?php $this -> permalink() ?>">
                <?php $this -> permalink() ?></a></span>
                </div>
       <div class="post-copyright__notice">
           <span class="post-copyright-meta">版权声明: </span>
           <span class="post-copyright-info">
            <?php if($this->fields->CopyRight == 'off') :?>
           <b style="color:red">本文严禁转载</b>，引用或转载文章前请先联系博主！            
           <?php else: ?>
            本博客所有文章除特别声明外，均采用 
            <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">CC BY-NC-SA 4.0</a> 
            许可协议。转载请注明来自<a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a> ！
           <?php endif?>
           </span>
      </div>
</div>
  <div class="tag_share">
      <div class="post-meta__tag-list">
        <?php printTag($this); ?>
      </div>
         <div class="post_share">
	<div class="social-share share-component"
		data-image="https://tva4.sinaimg.cn/large/007X0Rdygy1ghm2u8yvhdj30sg0g0gp1.jpg"
		data-sites="facebook,twitter,wechat,weibo,qq">
	<link rel="stylesheet" href="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/social-share.js/1.0.16/css/share.min.css" media="all"
		onload="this.media='all'">
	<script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/social-share.js/1.0.16/js/social-share.min.js" defer=""></script>
</div>
   </div>
   </div>
<?php if($this->fields->ShowReward === 'show' || $this->options->ShowGlobalReward === 'show') : ?>
   <div class="post-reward">
  <div class="reward-button button--animated">
    <i class="fas fa-qrcode"></i>打赏</div>
  <div class="reward-main">
    <ul class="reward-all">
<?php if ($this->options->RewardInfo) : ?>
        <?php
        if (strpos($this->options->RewardInfo, '||') !== false) {
            $list = "";
            $txt = $this->options->RewardInfo;
            $string_arr = explode("\r\n", $txt);
            $long = count($string_arr);
            for ($i = 0; $i < $long; $i++) {
                $list = $list . 
                '<li class="reward-item">
                <a href="' . explode("||", $string_arr[$i])[1] . '" rel="external nofollow noreferrer" target="_blank">
                <img class="post-qr-code-img entered loaded" data-lazy-src="' . explode("||", $string_arr[$i])[1] . '" alt="' . explode("||", $string_arr[$i])[0] . '" src="'.GetLazyLoad().'">
                 <div class="post-qr-code-desc">' . explode("||", $string_arr[$i])[0] . '</div>
                </a>
                </li>';
            }
            echo $list;
        }
        ?>
    <?php endif; ?>  
    </ul>
  </div>
</div>
<?php endif; ?>
<?php if($this->options->googleadsense != ""): ?>
  <div class="ads-wrap">
  <ins class="adsbygoogle"
       style="display:block;height: 180px;"
       data-ad-layout="rectangle,horizonta"
       data-ad-format="fluid"
       data-ad-client="<?php $this->options->googleadsense(); ?>"></ins></div>
  <script>
       (adsbygoogle = window.adsbygoogle || []).push({});
  </script>
  
<?php endif; ?>
<nav class="pagination-post" id="pagination">
<?php $prevId = thePrevCid($this);$nextId=theNextCid($this);?>
<?php if(!empty($nextId)&&!empty($prevId)) : ?>
<?php $this->widget('Widget_Archive@recommend'.$prevId, 'pageSize=1&type=post', 'cid='.$prevId)->to($prev);?>
    <?php $this->widget('Widget_Archive@recommend'.$prevId, 'pageSize=1&type=post', 'cid='.$prevId)->to($prev);?>
<div class="prev-post pull-left">
      <a href="<?php $prev->permalink();?>" title="<?php $prev->title();?>">
       <img class="cover" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" data-lazy-src="<?php echo get_ArticleThumbnail($prev);?>" src="<?php echo GetLazyLoad() ?>" alt="<?php $prev->title();?>">
        <div class="pagination-info">
        <div class="label">上一篇</div>
        <div class="next_info"><?php $prev->title();?></div>
        </div>
      </a>
      </div>
<?php elseif(!empty($nextId)&&empty($prevId)) : ?>   
 <?php $this->widget('Widget_Archive@recommend'.$nextId, 'pageSize=1&type=post', 'cid='.$nextId)->to($next);?>
<div class="prev-post pull-full">
    <a href="<?php $next->permalink();?>" title="<?php $next->title();?>">
      <img class="cover" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" class="next-cover" data-lazy-src="<?php echo get_ArticleThumbnail($next);?>" src="<?php echo GetLazyLoad() ?>" alt="<?php $next->title();?>">
             <div class="pagination-info">
        <div class="label">下一篇</div>
        <div class="next_info"><?php $next->title();?></div></div>
    </a>
    </div>      
<?php endif; ?>
<?php if(!empty($nextId)&&!empty($prevId)) : ?>
<?php $this->widget('Widget_Archive@recommend'.$prevId, 'pageSize=1&type=post', 'cid='.$prevId)->to($prev);?>
  <?php $this->widget('Widget_Archive@recommend'.$nextId, 'pageSize=1&type=post', 'cid='.$nextId)->to($next);?>
  <div class="next-post pull-right">
    <a href="<?php $next->permalink();?>" title="<?php $next->title();?>">
      <img class="cover" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" class="next-cover" data-lazy-src="<?php echo get_ArticleThumbnail($next);?>" src="<?php echo GetLazyLoad() ?>" alt="<?php $next->title();?>">
             <div class="pagination-info">
        <div class="label">下一篇</div>
        <div class="next_info"><?php $next->title();?></div></div>
    </a>
    </div>
<?php elseif(empty($nextId)&&!empty($prevId)) : ?>   
    <?php $this->widget('Widget_Archive@recommend'.$prevId, 'pageSize=1&type=post', 'cid='.$prevId)->to($prev);?>
<div class="prev-post pull-full">
      <a href="<?php $prev->permalink();?>">
       <img class="cover" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'" class="prev-cover" data-lazy-src="<?php echo get_ArticleThumbnail($prev);?>" src="<?php echo GetLazyLoad() ?>" alt="<?php $prev->title();?>">
        <div class="pagination-info">
        <div class="label">上一篇</div>
        <div class="next_info"><?php $prev->title();?></div>
        </div>
      </a>
      </div>
<?php endif; ?>
</nav>
<?php if($this->options->ShowRelatedPosts == 'on'): ?>
<div class="relatedPosts">
<div class="headline">
<i class="fas fa-thumbs-up fa-fw"></i>
<span>相关推荐</span>
</div>
<div class="relatedPosts-list">
<?php $this->related($this->options->RelatedPostsNum)->to($relatedPosts); ?>
<?php while ($relatedPosts->next()): ?><div><a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>"><img class="cover" data-lazy-src="<?php echo get_ArticleThumbnail($relatedPosts);?>" src="<?php echo GetLazyLoad() ?>" alt="cover"><div class="content is-center"><div class="date"><i class="far fa-calendar-alt fa-fw"></i> <?php $relatedPosts->date('Y-m-d'); ?></div><div class="title"><?php $relatedPosts->title(); ?></div></div></a></div><?php endwhile; ?>
</div>
</div>
<script>
$(document).ready(function(){
    const related = document.querySelector(".relatedPosts-list").innerHTML
    if(related == '\n'){
        $(".relatedPosts").remove()
    }
})
</script>
<?php endif?>
<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('post_sidebar.php'); ?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/GrayMac.css'); ?>">
<script type="text/javascript" src="<?php $this->options->themeUrl('js/prism.js?v1.5.3'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/clipboard.min.js'); ?>"></script>
<script>
$(document).ready(function(){if($(".toc").html().length == "14"){$("#card-toc,#mobile-toc-button").remove()}});
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