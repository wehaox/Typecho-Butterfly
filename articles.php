<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php   
/**  
    * 归档
    *  
    * @package custom  
    */  
$GLOBALS['BUTTERFLY_DISABLE_TOC'] = true;
$this->need('includes/page_header.php');
$pageSize = 20;
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$totalPosts = getRecentPostsLiteCount();
$totalPages = $totalPosts > 0 ? (int)ceil($totalPosts / $pageSize) : 1;
$currentPage = min($currentPage, $totalPages);
$archives = getRecentPostsLiteByPage($pageSize, $currentPage);
$basePageUrl = isset($this->permalink) ? $this->permalink : '';
if ($basePageUrl === '') {
    ob_start();
    $this->permalink();
    $basePageUrl = trim(ob_get_clean());
}

$pageUrlParts = parse_url($basePageUrl);
$pageQuery = [];
if (!empty($pageUrlParts['query'])) {
    parse_str($pageUrlParts['query'], $pageQuery);
}

$paginationBase = '';
if (!empty($pageUrlParts['scheme'])) {
    $paginationBase .= $pageUrlParts['scheme'] . '://';
}
if (!empty($pageUrlParts['host'])) {
    $paginationBase .= $pageUrlParts['host'];
}
if (!empty($pageUrlParts['port'])) {
    $paginationBase .= ':' . $pageUrlParts['port'];
}
$paginationBase .= isset($pageUrlParts['path']) ? $pageUrlParts['path'] : $basePageUrl;
$paginationFragment = isset($pageUrlParts['fragment']) ? '#' . $pageUrlParts['fragment'] : '';

$buildPageUrl = function ($page) use ($paginationBase, $pageQuery, $paginationFragment) {
    $query = $pageQuery;
    if ($page > 1) {
        $query['page'] = $page;
    } else {
        unset($query['page']);
    }

    $queryString = http_build_query($query);
    return $paginationBase . ($queryString ? '?' . $queryString : '') . $paginationFragment;
};
?>
<main class="layout" id="content-inner">
    <div id="archive">
        <section class="page-title">
<?php if($this -> authorId == $this -> user -> uid): ?>            
<?php endif; ?>
        </section>
   <?php $year=0;
    $output = '<div class="article-sort-title">文章总览 - '.$totalPosts.' </div><div class="article-sort">';
    foreach($archives as $archive):
        $year_tmp = date('Y',$archive['created']);
        $erro = "'https://tva1.sinaimg.cn/large/007X0Rdyly1gpaaf55n1rj30ic09u0sw.jpg'";
        if ($year != $year_tmp) {
            $year = $year_tmp;
            $output .= '<div class="article-sort-item year">'. $year .' 年</div>'; //输出年份
        }
        $output .= '<div class="article-sort-item">';
        if(noCover((object)$archive)){
            $output .= '<a class="article-sort-item-img" href="'.$archive['permalink'] .'">
            <img onerror="this.onerror=null;this.src='.$erro.'" src="'.get_ArticleThumbnail($archive).'" alt="'. htmlspecialchars($archive['title'], ENT_QUOTES, 'UTF-8') .'">
             </a>';
        }
        $output .= '
         <div class="article-sort-item-info">
        <div class="article-sort-item-time">
        <i class="far fa-calendar-alt"></i>
        <time class="post-meta-date-created" datetime=" '.date('Y-m-d',$archive['created']).'" title="发表于 '.date('Y-m-d',$archive['created']).'">
        '.date('Y-m-d',$archive['created']).'
        </time>
        </div>
        <a class="article-sort-item-title" href="'.$archive['permalink'] .'" title="'. htmlspecialchars($archive['title'], ENT_QUOTES, 'UTF-8') .'">
        '. htmlspecialchars($archive['title'], ENT_QUOTES, 'UTF-8') .'
        </a>
        </div>
        </div>'; //输出文章日期和标题
    endforeach;
    $output .= '</div>';
    echo $output;
?>
    <?php if ($totalPages > 1): ?>
    <nav id="pagination">
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a class="extend prev" href="<?php echo htmlspecialchars($buildPageUrl($currentPage - 1), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-chevron-left fa-fw"></i></a>
            <?php endif; ?>
            <?php
            $window = 2;
            $startPage = max(1, $currentPage - $window);
            $endPage = min($totalPages, $currentPage + $window);

            if ($startPage > 1) {
                echo '<a class="page-number" href="', htmlspecialchars($buildPageUrl(1), ENT_QUOTES, 'UTF-8'), '">1</a>';
                if ($startPage > 2) {
                    echo '<span class="space">...</span>';
                }
            }

            for ($page = $startPage; $page <= $endPage; $page++) {
                if ($page == $currentPage) {
                    echo '<a class="page-number current" aria-current="page">', $page, '</a>';
                } else {
                    echo '<a class="page-number" href="', htmlspecialchars($buildPageUrl($page), ENT_QUOTES, 'UTF-8'), '">', $page, '</a>';
                }
            }

            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    echo '<span class="space">...</span>';
                }
                echo '<a class="page-number" href="', htmlspecialchars($buildPageUrl($totalPages), ENT_QUOTES, 'UTF-8'), '">', $totalPages, '</a>';
            }
            ?>
            <?php if ($currentPage < $totalPages): ?>
                <a class="extend next" href="<?php echo htmlspecialchars($buildPageUrl($currentPage + 1), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-chevron-right fa-fw"></i></a>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>
    </div>
    <?php $this->need('includes/sidebar.php'); ?>
    <style>.card-recent-post{display:none}#to_comment{display: none!important;}#pagination{text-align:center;margin:1.5rem 0}.layout>div:not(.recent-posts) .pagination{display:inline-flex;flex-wrap:wrap;align-items:center;justify-content:center}.layout>div:not(.recent-posts) .pagination .page-number,.layout>div:not(.recent-posts) .pagination .extend{display:inline-block;margin:0 .2rem;min-width:2.5em;height:2.5em;border-radius:8px;text-align:center;line-height:2.2rem;box-shadow:var(--card-box-shadow)}.layout>div:not(.recent-posts) .pagination .page-number{text-decoration:none}.layout>div:not(.recent-posts) .pagination a.page-number:not(.current){background:var(--btn-comm-bg)!important;color:var(--font-color);cursor:pointer}.layout>div:not(.recent-posts) .pagination a.page-number:not(.current):hover{background:var(--btn-hover-color)!important;color:var(--btn-color)}.layout>div:not(.recent-posts) .pagination .page-number.current{pointer-events:none}.layout>div:not(.recent-posts) .pagination .extend{background:var(--btn-comm-bg);color:#99a9bf;cursor:pointer;text-decoration:none}.layout>div:not(.recent-posts) .pagination .extend:hover{background:var(--btn-hover-color);color:var(--btn-color)}.layout>div:not(.recent-posts) .pagination .space{display:inline-flex;align-items:center;justify-content:center;color:var(--font-color);margin:0 .2rem;min-width:auto;height:2.5em;line-height:2.2rem;padding:0 .35rem}</style>
</main>
<?php $this -> need('footer.php'); ?>
