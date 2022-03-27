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
    <span class="category-amount"><?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?><?php $stat->categoriesNum() ?></span>
</div>
<ul class="category-list" id="aside-cat-list">
<?php $this->widget('Widget_Metas_Category_List')->to($categorys); ?>
<?php while($categorys->next()): ?>
<?php if ($categorys->levels === 0): ?>
<?php $children = $categorys->getAllChildren($categorys->mid); ?>
<?php if (empty($children)) { ?>
<li class="category-list-item">
<a class="category-list-link" href="<?php $categorys->permalink(); ?>" title="<?php $categorys->name(); ?>"><?php $categorys->name(); ?>
</a>
<span class="category-list-count"><?php $categorys->count(); ?></span>
</li>
<?php } else { ?>
<li class="category-list-item">
<a class="category-list-link"  href="#"><?php $categorys->name(); ?></a>
<span class="category-list-count"><?php $categorys->count(); ?></span>
<ul class="category-list-child">
<?php foreach ($children as $mid) 
{ ?>
<?php 
$child = $categorys->getCategory($mid); echo($this->is('category', $mid));?>
<li class="category-list-item">
<a href="<?php echo $child['permalink'] ?>" title="<?php echo $child['name']; ?>"><?php echo $child['name']; ?>
</a><span class="category-list-count"><?php echo $child['count']; ?></span>
</li>
<?php 
} ?>
</ul>
</li>
<?php } ?>
<?php endif; ?>
<?php endwhile; ?>
</ul>
</div>
<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('sidebar.php'); ?>
</main>
<?php $this->need('footer.php'); ?>