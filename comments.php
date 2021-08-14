<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="rightside">
	<div id="rightside-config-hide" class="">
		<button id="font-plus" type="button" title="放大字体">
			<i class="fas fa-plus">
			</i>
		</button>
		<button id="font-minus" type="button" title="缩小字体">
			<i class="fas fa-minus">
			</i>
		</button>
		<button id="translateLink" type="button" title="简繁转换">
			簡
		</button>
		<button id="darkmode" type="button" title="浅色和深色模式转换">
			<i class="fas fa-adjust">
			</i>
		</button>
		<button id="hide-aside-btn" type="button" title="单栏和双栏切换">
			<i class="fas fa-arrows-alt-h">
			</i>
		</button>
	</div>
	<div id="rightside-config-show">
		<button id="rightside_config" type="button" title="设置">
			<i class="fas fa-cog fa-spin">
			</i>
		</button>
		<button class="close" id="mobile-toc-button" type="button" title="目录">
			<i class="fas fa-list-ul">
			</i>
		</button>
		<a id="to_comment" href="#post-comment" title="直达评论">
			<i class="fas fa-comments">
			</i>
		</a>
		<button id="go-up" type="button" title="回到顶部">
			<i class="fas fa-arrow-up">
			</i>
		</button>
	</div>
</div>
<div id="post-comment">
    <?php $this->comments()->to($comments); ?>
     	<h3 id="response"><div class="comment-head"><div class="comment-headline"><i class="fas fa-comments fa-fw"></i><span> 评论</span></div></div></h3>
    <?php if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <div class="cancel-comment-reply">
        <?php $comments->cancelReply(); ?>
        </div>
    	      <div class="change" id="commentType">
            </div>
    	<form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
    	      <div class="commments-area">
            <div class="commments-info">
            <?php if($this->user->hasLogin()): ?>
            <div style="border-bottom: 1px dashed #dedede;">
    		<?php _e('登录身份:  '); ?>
    		<a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. 
    		<a href="<?php $this->options->logoutUrl(); ?>" title="退出"><i class="fas fa-sign-out-alt"></i></a>
    		 </div>     
            <?php else: ?>
                <label for="author" class="required"></label>
    			<input placeholder="昵称" type="text" name="author" id="author" class="text" value="<?php $this->remember('author'); ?>" required />

                <label for="mail"<?php if ($this->options->commentsRequireMail): ?> class="required"<?php endif; ?>></label>
    			<input placeholder="邮箱" type="email" name="mail" id="mail" class="text" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />

                <label for="url"<?php if ($this->options->commentsRequireURL): ?> class="required"<?php endif; ?>></label>
    			<input type="url" name="url" id="url" class="text" placeholder="<?php _e('http://'); ?>" 
    			value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
            <?php endif; ?>
            </div>
                <label for="textarea" class="required"></label>
                <textarea placeholder="你可以畅所欲言" rows="8" cols="50" name="text" id="textarea" class="textarea" required ><?php $this->remember('text'); ?></textarea>
                  </div>
    		    <p style=" text-align: right;">
                <button class="submit" type="submit" ><?php _e('评论'); ?></button>
            </p>
    	</form>
    </div>
    <?php else: ?>
    <h3><?php _e('评论已关闭'); ?></h3>
    <?php endif; ?>
   <?php if ($comments->have()): ?>
	<h3><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h3>
    <?php  $comments->listComments(); ?>
    <?php $comments->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
    <?php endif; ?>
</div>