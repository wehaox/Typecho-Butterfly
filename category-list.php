<?php
/**
 * 分类
 *
 * @package custom
 *
 */$this->need('page_header.php');
?>
<main class="layout" id="content-inner">
    <div id="page"> 
    <div class="category-lists">
  <div class="category-title is-center">分类 -
    <span class="category-amount"><?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->categoriesNum() ?></span></div>
  <div>
     <ul class="category-list" id="aside-cat-list"> 
        <?php $this->widget('Widget_Metas_Category_List')->parse('
         <li class="category-list-item">
         <a href="{permalink}" class="category-list-link" title="{description}"> {name} </a>
          <span class="category-list-count"> {count} </span>
          </li> '); ?>
    </ul>
  </div>
</div>
    <?php $this->need('comments.php'); ?>
    </div>
     <?php $this->need('sidebar.php'); ?>
    </main>
<?php $this->need('footer.php'); ?>    
