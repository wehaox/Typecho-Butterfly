<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('includes/page_header.php'); ?>
<?php $pageShowReward = getThemeFieldValue($this->cid, 'ShowReward', 'off'); ?>
<main class="layout" id="content-inner">
<div id="page" >
    <article class="post-content" id="article-container">
       <?php
        $hasCommented = false;
        $rememberMail = $this->remember('mail', true);
        if (!empty($rememberMail)) {
            $db = Typecho_Db::get();
            $sql = $db->select('coid')->from('table.comments')
             ->where('cid = ?', $this->cid)
             ->where('mail = ?', $rememberMail)
             ->limit(1);
            $result = $db->fetchRow($sql);
            $hasCommented = !empty($result);
        }
         $content = replaceHideContent($this->content, $this->user->hasLogin() || $hasCommented);
        echo $content;
       ?>
    </article>
    <?php if($pageShowReward === 'show' || $this->options->ShowGlobalReward === 'show') : ?>
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
    <?php $this->need('includes/comments.php'); ?>
</div>
<?php $this->need('includes/sidebar.php'); ?>
</main>
<!-- end #main-->
<?php $this->need('footer.php'); ?>
