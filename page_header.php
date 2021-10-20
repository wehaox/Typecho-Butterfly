<?php $this->need('public/noqq.php'); ?>
<?php if(!$this->user->hasLogin()):?>
<?php $this->need('public/defend.php'); ?>
<?php endif;?>
<?php  $this->need('header_com.php'); ?>
<body style="zoom: 1;">
<div id="web_bg"></div>
<div class="page" id="body-wrap">
    <header class="not-home-page" id="page-header" style="background-image: url(<?php GetRandomThumbnailPost($this); ?>)">
         <div id="page-site-info"><h1 id="site-title"><?php $this->archiveTitle(array(
            'category'  =>  _t(' %s '),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ''); ?></h1></div>
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
                        <?php $pages->title(); ?>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </nav>
</header>