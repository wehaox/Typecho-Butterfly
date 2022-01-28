<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if($this->allow('comment') && $this->options->CloseComments == 'off'): ?>
    <hr></hr>
    <h3 id="response"><div class="comment-head"><div class="comment-headline"><i class="fas fa-comments fa-fw"></i><span> 评论</span></div></div></h3>
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
                  <?php if(!$this->user->hasLogin() && $this->options->EnableCommentsLogin === 'on'): ?>
                   <div class="submit" style="float:left;margin-top: 24px;" id="comment_keys"><i class="fas fa-key"></i></div>
                  <?php endif; ?>
    		    <p style=" text-align: right;">
                <button class="submit" type="submit" ><?php _e('评论'); ?></button>
            </p>
    	</form>
<?php if(!$this->user->hasLogin() && $this->options->EnableCommentsLogin === 'on'): ?>
<div id="comment_login" style="display:none">
<form action="<?php $this->options->loginAction()?>" method="post" name="login" rold="form">
<input type="hidden" name="referer" value="<?php echo curPageURL() ?>">
<input type="text" class="text" name="name" autocomplete="username" placeholder="请输入用户名" required/>
<input type="password" class="text" name="password" autocomplete="current-password" placeholder="请输入密码" required/>
<button class="submit" type="submit">登录</button>
</form>
</div>
<?php endif; ?>	
    </div>
    <script>$("#to_comment").click(function() {var hre = $(this).attr("href");$('html, body').animate({scrollTop: $(hre).offset().top}, 300);});
    $("#comment_keys").click(function(){
        $('#comment_login').slideToggle("fast");
    })
    </script>
    <?php elseif(!$this->allow('comment')&&$this->is('post')): ?>
    <hr></hr>
    <h3><?php _e('评论已关闭'); ?></h3>
     <?php else: ?>
    <?php endif; ?>
   <?php if ($comments->have()): ?>
	<h3><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h3>
    <?php  $comments->listComments(); ?>
    <?php $comments->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', array('wrapTag' => 'ol', 'wrapClass' => 'page-navigator', 'itemTag' => '', 'prevClass' => 'prev', 'nextClass' => 'next', 'currentClass' => 'current' )); ?>
    <?php endif; ?>
</div>