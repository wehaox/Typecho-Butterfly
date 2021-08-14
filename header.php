<?php  $this->need('header_com.php'); ?>
    <div id="web_bg"></div>
<script>function subtitleType(){
fetch("https://api.btstu.cn/yan/api.php?charset=utf-8&encode=json").then(t=>t.json()).then(t=>{
var e="出自 "+t.from,o=0=="".length?new Array:" ".split(",");
o.unshift(t.text),
new Typed("#subtitle",
{strings:o,
startDelay:300,
typeSpeed:150,
loop:!0,
backSpeed:50
})
    
}
)}
"function"==typeof Typed?subtitleType():getScript("https://cdn.jsdelivr.net/npm/typed.js/lib/typed.min.js").then(subtitleType
)</script>
<div class="page" id="body-wrap">
<?php if (is_array($this->options->beautifyBlock) && in_array('ShowTopimg',$this->options->beautifyBlock)): ?> 
<header class="full_page" id="page-header"  style="background-image: url(<?php $this->options->headerimg() ?>)">
        <div id="site-info">
            <h1 id="site-title"><?php $this->options->description() ?></h1>
            <div id="site-subtitle">
                <span id="subtitle"></span>
            </div>
        </div>
        <div id="scroll-down"><i class="fas fa-angle-down scroll-down-effects"></i></div>
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
<?php endif; ?>