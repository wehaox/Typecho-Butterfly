<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php 
/**  
    * 友链
    *  
    * @package custom  
    */  
$this->need('page_header.php'); 
?>
<main class="layout" id="content-inner">
<div id="page">
<div id="article-container">
    <div class="flink">
<?php $this->content(); ?>
    <div class="flink-list">
<?php
if($this->options->friendset==1){
if(array_key_exists("Links", Typecho_Plugin::export()['activated'])){
      $errorimg="'/usr/themes/butterfly/img/friend_404.gif'";
      $shuffle = Helper::options()->linksshuffle;
      $link_limit = Helper::options()->linksIndexNum;
      $Links = Links_Plugin::output("
      <div class='flink-list-item'>
      <a target='_blank' href='{url}'>
      <div class='flink-item-icon'>
      <img onerror=\"this.onerror=null;this.src='/usr/themes/butterfly/img/friend_404.gif'\" src='{GetLazyLoad()}' data-lazy-src='{image}' alt='' class='entered'/></div>
      <div class='flink-item-name'>{name}</div>
      <div class='flink-item-desc' title='{description}'>{description}</div>
      </a></div>");
    for($i = 0; $i < count($Links); $i++){
      echo $Links[$i];
    }
}
}else{
if ($this->options->Friends){
                if (strpos($this->options->Friends, '||') !== false) {
                   $errorimg="'/usr/themes/butterfly/img/friend_404.gif'";
                   $list = "";
                   $txt = $this->options->Friends;
                   $string_arr = explode("\r\n", $txt);
                   $long = count($string_arr);
                   for ($i = 0; $i < $long; $i++) {
                       $list = $list . 
                       ' <div class="flink-list-item"">
                       <a target="_blank" title="' . explode("||", $string_arr[$i])[0] . '" 
                       href="' . explode("||", $string_arr[$i])[1] . '">
                       <div class="flink-item-icon">
                       <img onerror="this.onerror=null;this.src=' .$errorimg. '" src="'.GetLazyLoad().'" data-lazy-src="' . explode("||", $string_arr[$i])[2] . '" alt=" '. explode("||", $string_arr[$i])[0] . '" class="entered"/>
                       </div>
                       <div class="flink-item-name"> '. explode("||", $string_arr[$i])[0] . ' </div>
                       <div class="flink-item-desc" title="' . explode("||", $string_arr[$i])[3] . '">' . explode("||", $string_arr[$i])[3] . '</div>
                       </a>
                       </div>';
         }
                 echo $list;
    }
  }
}
?>

            </div>
    </div>
</div>

<?php $this->need('comments.php'); ?>
</div>
<?php $this->need('post_sidebar.php'); ?>
<script src="<?php $this->options->themeUrl('js/comjs.js'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/prism.js?v1.0'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/clipboard.min.js'); ?>"></script>
<script>
$(document).ready(function(){var tocState = $(".toc").html();if(tocState.length == "1") {
$("#card-toc,#mobile-toc-button").remove();}});
</script>
<?php if (!empty($this->options->beautifyBlock) && in_array('showLineNumber',
    $this->options->beautifyBlock)): ?> 
<script type="text/javascript">
	(function(){
		var pres = document.querySelectorAll('pre');
		var lineNumberClassName = 'line-numbers';
		pres.forEach(function (item, index) {
			item.className = item.className == '' ? lineNumberClassName : item.className + ' ' + lineNumberClassName;
		});
	})();
</script>
<?php endif; ?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>
