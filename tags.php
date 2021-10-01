<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php   
/**  
    * 标签
    *  
    * @package custom  
    */  
$this->need('page_header.php'); ?>
<style>
.tag-cloud-list a:first-child{
    font-size: 1.8em;
}
.tag-cloud-list a{
    font-size: 1.3em;
}
.tag-cloud-list a:nth-child(2n){
    font-size: 2.1em;
}
</style>
<main class="layout" id="content-inner">
    <div id="page">
        <div class="tag-cloud-title is-center">标签 - <span class="tag-cloud-amount"><?php echo tagsNum(); ?> </span></div>
        <div class="tag-cloud-list is-center">
            
   <?php $this->widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true, 'limit' => 2000))->to($tags); ?>  
        <?php while($tags->next()): ?>  
            <a<?php if (!empty($this->options->beautifyBlock) && in_array('ShowColorTags',
                    $this->options->beautifyBlock)): ?> 
            style="color: rgb(<?php echo(rand(0, 255)); ?>, <?php echo(rand(0,255)); ?>, <?php echo(rand(0, 255)); ?>)"
              <?php endif; ?>
            rel="tag" class="tagslink" href="<?php $tags->permalink(); ?>"  title="<?php $tags->name(); ?>" style='display: inline-block; margin: 0 5px 5px 0;'><?php $tags->name(); ?></a>
            <?php endwhile; ?>
  </div>
         <?php $this->need('comments.php'); ?>
    </div>
     <?php $this->need('sidebar.php'); ?>
</main>
<?php $this -> need('footer.php'); ?>