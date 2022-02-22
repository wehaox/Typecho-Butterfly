<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<html data-theme="light">
<?php  $this->need('header_com.php'); ?>  
<body _c_t_common="1">
<div id="web_bg"></div>
<div class="error404" id="body-wrap">
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
                        <a class="site-page" href="<?php $this->options->siteUrl(); ?>">
                            <li class="fa-fw fas fa-home"></li><?php _e('首页'); ?></a>
                    </div>
                    <?php if($this->options->EnableAutoHeaderLink === 'on') : ?>
                        <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                        <?php while($pages->next()): ?>
                        <div class="menus_item">
                            <a class="site-page" href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>">
                            <?php if($this->is($pages->title == "友链")){
                                echo"<i class='fa-fw fas fa-link'></i>";
                            }
                            elseif($this->is($pages->title == "关于")){
                                 echo"<li class='fa-fw fas fa-user'></li>";
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
                            <span><?php $pages->title(); ?></span>
                            </a>
                       </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    <?php $this->options->CustomHeaderLink() ?>
                </div>
            </div>
    </nav>
</header>
    <div id="error-wrap"><div class="error-content"><div class="error-img"><img src="https://i.loli.net/2020/05/19/aKOcLiyPl2JQdFD.png" alt="Page not found" class="entered"></div><div class="error-info"><h1 class="error_title">404</h1><div class="error_subtitle">頁面沒有找到</div></div></div></div></div>
    <!--搜索  -->
<div id="local-search">
  <div class="search-dialog" style="">
    <nav class="search-nav">
      <span class="search-dialog-title">本地搜索</span>
      <span id="loading-status"></span>
      <button class="search-close-button">
        <i class="fas fa-times"></i>
      </button>
    </nav>
    <div class="search-wrap" style="display: block;">
      <div id="local-search-input">
        <form class="local-search-box" method="post" action="<?php $this->options->siteUrl(); ?>" role="search" id="search">
          <label for="s" class="sr-only"><?php _e('搜索关键字'); ?></label>
          <input type="text"  name="s"  placeholder="回车查询" required="required"></div>
      </form>
      <hr>
      <div id="local-search-results"></div>
    </div>
  </div>
  <div id="search-mask" style=""></div>
</div>
</div>
<!--搜索  -->
</body>
</html>