<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php 
/**  
    * 友链
    *  
    * @package custom  
    */  
$this->need('includes/page_header.php'); 
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
      $errorimg = Helper::options()->themeUrl . '/img/friend_404.gif';
      $shuffle = Helper::options()->linksshuffle;
      $link_limit = Helper::options()->linksIndexNum;
      $Links = Links_Plugin::output("
      <div class='flink-list-item'>
      <a target='_blank' href='{url}'>
      <div class='flink-item-icon'>
      <img onerror=\"this.onerror=null;this.src='{$errorimg}'\" src='{GetLazyLoad()}' data-lazy-src='{image}' alt='{name}' title='{name}' class='entered'/></div>
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
                   $errorimg = Helper::options()->themeUrl . '/img/friend_404.gif';
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
                        <img onerror="this.onerror=null;this.src=\'' . $errorimg . '\'" src="'.GetLazyLoad().'" data-lazy-src="' . explode("||", $string_arr[$i])[2] . '" alt=" '. explode("||", $string_arr[$i])[0] . '" class="entered"/>
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

<?php $this->need('includes/comments.php'); ?>
</div>
<?php $this->need('includes/sidebar.php'); ?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>
