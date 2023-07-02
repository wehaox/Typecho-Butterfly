<nav id="nav" class="show" >
         <span id="blog-info">
            <a href="<?php $this->options->siteUrl(); ?>">
                <?php if($this->options->SiteLogo !== '') : ?>
                <img src="<?php $this->options->SiteLogo() ?>" width="95px" />
                <?php else :?>
                <span class="site-name"><?php $this->options->title() ?></span>
                <?php endif ?>
            </a>
       </span>
            <div id="menus">
                  <div id="search-button">
                      <a class="site-page social-icon search">
                      <i class="fas fa-search fa-fw"></i>
                      <?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch',$this->options->beautifyBlock)): ?>
                      <form method="post" action="<?php $this->options->siteUrl(); ?>" role="search" id="dSearch">
                        <input type="text" placeholder="搜索" id="dSearchIn" name="s" required="required">
                      </form>
                      <?php else: ?>
                        <span> 搜索</span>
                      <?php endif ?>
                      </a> 
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

<?php if (is_array($this->options->beautifyBlock) && in_array('showNoAlertSearch',$this->options->beautifyBlock)): ?>
<style>
#dSearch{
    display: inline-block;
}
#dSearch>input {
    border: none;
    opacity: 1;
    outline: none;
    width: 35px;
    text-indent: 2px;
    transition: all .5s;
    background: transparent;
}
#page-header.nav-fixed #nav ::placeholder,
#page-header.nav-fixed #nav input {
    color: var(--font-color);
}

#nav ::placeholder,
#nav input {
    color: var(--light-grey);
}
#page-header.not-top-img #nav ::placeholder,
#page-header.not-top-img #nav input {
    color: var(--font-color);
    text-shadow: none;
}

#page-header.nav-fixed #nav a:hover {
    color: unset;
}

</style>
<script>
var input = document.getElementById('dSearchIn');
input.addEventListener('focus', function() {
  input.style.width = '150px';
});
input.addEventListener('blur', function() {
  input.style.width = '35px';
});
</script>
<?php endif ?>