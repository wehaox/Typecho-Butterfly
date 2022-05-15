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
                            <?php switch ($pages->title){
                                case "友链":
                                    echo"<i class='fa-fw fas fa-link'></i>";
                                    break;  
                                case "关于":
                                    echo"<li class='fa-fw fas fa-user'></li>";
                                    break;
                                case "留言":
                                   echo"<i class='fa-fw fas fa-comment-dots'></i>";
                                    break;
                                case "归档":
                                   echo"<i class='fa-fw fas fa-archive'></i>";
                                    break;
                                case "标签":
                                    echo"<i class='fa-fw fas fa-tags'></i>";
                                    break;
                                case "分类":
                                   echo"<i class='fa-fw fas fa-folder-open'></i>";
                                    break;
                                case "留言板":
                                   echo"<i class='fa-fw fa fa-comment-dots'></i>";
                                    break;                                   
                                default:
                                    echo"<i class='fa-fw fa fa-coffee'></i>";    
                            }?>
                            <span><?php $pages->title(); ?></span>
                            </a>
                       </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
                    <?php $this->options->CustomHeaderLink() ?>
                </div>
            </div>
    </nav>