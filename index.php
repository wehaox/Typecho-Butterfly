<?php
/**
 * <span><script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>主题最新版本：<span id="latest">获取中...</span><script>$(document).ready(function() {$.get("https://typecho.wehao.ml", function(data) { $("#latest").text(data.ver);});});</script></span>
 * 这是 Typecho 版本的 butterfly 主题
 * 主题为移植至Typecho，你可以替换原butterfly主题的index.css文件
 * 当前适配 hexo-butterfly 4.6.0
 * <a href="https://www.wehaox.com">个人网站</a> | <a href="https://blog.wehaox.com/archives/typecho-butterfly.html">主题使用文档</a>
 * @package Typecho-Butterfly
 * @author b站:wehao-
 * @version 1.7.5
 * @link https://space.bilibili.com/34174433
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/** 文章置顶 */
$sticky = $this->options->sticky_cids;
if($sticky && $this->is('index') || $this->is('front')){
    $sticky_cids = explode(',', strtr($sticky, ' ', ','));//分割文本 
    $sticky_html = "<span class='article-meta'><i class='fas fa-thumbtack article-meta__icon sticky'></i><span class='sticky'>置顶 </span><span class='article-meta__separator'>|</span></span>";
    $db = Typecho_Db::get();
    $select1 = $this->select()->where('type = ?', 'post');
    $select2 = $this->select()->where('type = ? AND status = ? AND created < ?', 'post','publish',time());
    $this->row = [];
    $this->stack = [];
    $this->length = 0;
    $order = '';
    foreach($sticky_cids as $i => $cid) {
        if($i == 0) $select1->where('cid = ?', $cid);
        else $select1->orWhere('cid = ?', $cid);
        $order .= " when $cid then $i";
        $select2->where('table.contents.cid != ?', $cid);
    }
    if ($order) $select1->order('',"(case cid$order end)");
    if ($this->_currentPage == 1) foreach($db->fetchAll($select1) as $sticky_post){
        $sticky_post['sticky'] = $sticky_html;
        $this->push($sticky_post);
    }
    $uid = $this->user->uid; //登录时，显示用户各自的私密文章
    if($uid) $select2->orWhere('authorId = ? AND status = ?',$uid,'private');
    $sticky_posts = $db->fetchAll($select2->order('table.contents.created', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
    foreach($sticky_posts as $sticky_post) $this->push($sticky_post); //压入列队
    $this->setTotal($this->getTotal()-count($sticky_cids)); //置顶文章不计算在所有文章内
}
?>
<?php  $this->need('header.php'); ?>
<main class="layout" id="content-inner">
<div class="recent-posts" id="recent-posts">
<?php while($this->next()): ?>
    <div class="recent-post-item">
        <wehao class="post_cover">
             <a  href="<?php $this->permalink() ?>">
                 <img class="post-bg" data-lazy-src="<?php echo get_ArticleThumbnail($this);?>" src="<?php echo GetLazyLoad() ?>" onerror="this.onerror=null;this.src='<?php $this->options->themeUrl('img/404.jpg'); ?>'"></a>
        </wehao>
    <div class="recent-post-info">
        <a  class="article-title" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
        <div class="article-meta-wrap">
        <?php $this->sticky(); ?>
            <span class="post-meta-date">
                <i class="far fa-calendar-alt"></i>
                <span class="article-meta-label">发表于</span>
                <span datetime="<?php $this->date('Y-m-d'); ?>" style="display: inline;" pubdate><?php $this->date('Y-m-d'); ?></span>
            </span>
            <span class="post-meta-date">
                <span class="article-meta-separator">|</span>
                <i class="fas fa-history"></i>
                <span class="article-meta-label">更新于</span>
                <span datetime="<?php echo date('Y-m-d', $this->modified); ?>"  style="display: inline;"><?php echo date('Y-m-d', $this->modified); ?></span>
            </span>
            <span class="article-meta">
                <span class="article-meta-separator">|</span>
                <i class="fas fa-inbox"></i>
                <?php $this->category(' '); ?>
            </span>
            <span class="article-meta">
                <span class="article-meta-separator">|</span>
                <i class="fa-solid fa-pen-nib"></i>
                <?php _e('作者: '); ?><a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a>
            </span>
            <span class="article-meta">
                <span class="article-meta-separator">|</span>
                <i class="fas fa-comments"></i>
                <a class="twikoo-count" href="<?php $this->permalink() ?>#comments"><?php $this->commentsNum('0条评论', '1 条评论', '%d 条评论'); ?></a>
            </span>
        </div>
    <div class="content">
        <?php 
        if($this->fields->excerpt && $this->fields->excerpt!='') {
            echo $this->fields->excerpt;}
        else{
            echo $this->excerpt(130);
        }
            echo '<br><br><a href="',$this->permalink(),'" title="',$this->title(),'">阅读全文...</a>';
        ?>
    </div>
    </div>
</div>
<?php endwhile; ?>
<nav id="pagination">
 <?php $this->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'pagination', 'itemTag' => '', 'prevClass' => 'extend prev', 'nextClass' => 'extend next', 'currentClass' => 'page-number current' )); ?>
</nav>
</div>
<?php $this->need('sidebar.php'); ?>
</main>
<?php $this->need('footer.php'); ?>
<script>
function ver() {console.log(`
===================================================================
                                                                             
    #####  #    # ##### ##### ###### #####  ###### #      #   #    
    #    # #    #   #     #   #      #    # #      #       # #     
    #####  #    #   #     #   #####  #    # #####  #        #     
    #    # #    #   #     #   #      #####  #      #        #      
    #    # #    #   #     #   #      #   #  #      #        #    
    #####   ####    #     #   ###### #    # #      ######   #  
    
                            1.7.5
===================================================================
`);}
</script>