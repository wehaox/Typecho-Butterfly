<?php
/**
 * 相册目录
 *
 * @package custom
 */
?><?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('page_header.php'); ?>
<main class="layout" id="content-inner">
<div id="page" >
    <article class="post-content" id="article-container">
       <?php
		//把文章里的id解析到数组，遍历数组输出相册目录
		//1.删除文章里面的html标签
		$cleanString = strip_tags($this->content);
		// 2.获取文字中的id，并构建数组，使用"-"分割
		$arr = explode("-", $cleanString);

		// 获取数组长度，并且忽略空数据
		$arrlight = count(array_filter($arr, function($value) {
			return $value !== '' && $value !== null;
		}));
		// 5.开始遍历数组
		$html="";
		for($i=0;$i<$arrlight;$i++){
			$this->widget('Widget_Archive@lunbo'.$i, 'pageSize=1&type=post', 'cid='.$arr[$i])->to($ji);
			if($ji->fields->thumb){$img=$ji->fields->thumb;}else{$img="";}//判断是否有头图
			if($ji->fields->subtitle){$subtitle=$ji->fields->subtitle;}else{$subtitle="";}//判断是否有副标题
			$html=$html.'
<figure class="gallery-group">
	<a href="'.$ji->permalink.'">
		<img class="gallery-group-img no-lightbox entered loaded" src="'.$img.'" data-lazy-src="'.$img.'" alt="Group Image Gallery" data-ll-status="loaded">
	</a>
	<figcaption>
		<div class="gallery-group-name">'.$ji->title.'</div>
		<p>'.$subtitle.'</p>
		
	</figcaption>
</figure>
			';
		}
		echo $html;
       ?>
    </article>
    <?php if($this->fields->ShowReward === 'show' || $this->options->ShowGlobalReward === 'show') : ?>
   <div class="post-reward">
  <div class="reward-button button--animated">
    <i class="fas fa-qrcode"></i>打赏</div>
  <div class="reward-main">
    <ul class="reward-all">
<?php if ($this->options->RewardInfo) : ?>
        <?php
        if (strpos($this->options->RewardInfo, '||') !== false) {
            $list = "";
            $txt = $this->options->RewardInfo;
            $string_arr = explode("\r\n", $txt);
            $long = count($string_arr);
            for ($i = 0; $i < $long; $i++) {
                $list = $list . 
                '<li class="reward-item">
                <a href="' . explode("||", $string_arr[$i])[1] . '" rel="external nofollow noreferrer" target="_blank">
                <img class="post-qr-code-img entered loaded" data-lazy-src="' . explode("||", $string_arr[$i])[1] . '" alt="' . explode("||", $string_arr[$i])[0] . '" src="'.GetLazyLoad().'">
                 <div class="post-qr-code-desc">' . explode("||", $string_arr[$i])[0] . '</div>
                </a>
                </li>';
            }
            echo $list;
        }
        ?>
    <?php endif; ?>  
    </ul>
  </div>
</div>
<?php endif; ?>
    <?php $this->need('comments.php'); ?>
</div>
<?php $this->need('post_sidebar.php'); ?>
<link rel="stylesheet" href="<?php $this->options->themeUrl('css/GrayMac.css'); ?>">
<script type="text/javascript" src="<?php $this->options->themeUrl('js/prism.js?v1.0'); ?>"></script>
<script type="text/javascript" src="<?php $this->options->themeUrl('js/clipboard.min.js'); ?>"></script>
<script>
$(document).ready(function(){if($(".toc").html().length == "14") {$("#card-toc,#mobile-toc-button").remove()}});
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
<?php if($this->fields->ShowToc === 'off') : ?>
<style>#card-toc,#mobile-toc-button{display: none!important;}</style>
<?php endif?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>