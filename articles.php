<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php   
/**  
    * 归档
    *  
    * @package custom  
    */  
$this->need('page_header.php');
?>
<main class="layout" id="content-inner">
    <div id="archive">
        <section class="page-title">
<?php if($this -> authorId == $this -> user -> uid): ?>            
<?php endif; ?>
        </section>
   <?php $this->widget('Widget_Contents_Post_Recent', 'pageSize=1000')->to($archives); Typecho_Widget::widget('Widget_Stat')->to($stat);
    $year=0; $mon=0; $i=0; $j=0;
    $output = '<div class="article-sort-title">文章总览 - '.$stat->publishedPostsNum.' </div><div class="article-sort"> ';
    while($archives->next()):
        $year_tmp = date('Y',$archives->created);
        $mon_tmp = date('m',$archives->created);
        $y=$year; $m=$mon;
        $erro = "'https://tva1.sinaimg.cn/large/007X0Rdyly1gpaaf55n1rj30ic09u0sw.jpg'";
        if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
        if ($year != $year_tmp && $year > 0) $output .= '</ul>';
        if ($year != $year_tmp) {
            $year = $year_tmp;
            $output .= '<div class="article-sort-item year">'. $year .' 年</div>'; //输出年份
        }
        $output .= 
        '<div class="article-sort-item">'.
       '<a class="article-sort-item-img" href="'.$archives->permalink .'">
       <img onerror="this.onerror=null;this.src='.$erro.'" src="'.get_ArticleThumbnail($archives).'" alt="'. $archives->title .'">
        </a>
         <div class="article-sort-item-info">
        <div class="article-sort-item-time">
        <i class="far fa-calendar-alt"></i>
        <time class="post-meta-date-created" datetime=" '.date('Y-m-d',$archives->created).'" title="发表于 '.date('Y-m-d',$archives->created).'">
        '.date('Y-m-d',$archives->created).'
        </time>
        </div>
        <a class="article-sort-item-title" href="'.$archives->permalink .'" title="'. $archives->title .'">
        '. $archives->title .'
        </a>
        </div>
        </div>'; //输出文章日期和标题
    endwhile;
    echo $output;
?>
    </div>
     </div>
    <?php $this->need('sidebar.php'); ?>
    <style>.card-recent-post{display:none}#to_comment{display: none!important;}</style>
</main>
<?php $this -> need('footer.php'); ?>