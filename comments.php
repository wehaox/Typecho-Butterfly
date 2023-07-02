<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if($this->allow('comment') && $this->options->CloseComments == 'off'): ?>
    <hr></hr>
    <h3 id="response"><div class="comment-head"><div class="comment-headline"><i class="fas fa-comments fa-fw"></i><span> 评论</span></div></div></h3>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <div class="cancel-comment-reply">
        <?php $comments->cancelReply("<svg class='vicon cancel-reply-btn' viewBox='0 0 1024 1024' version='1.1' xmlns='http://www.w3.org/2000/svg' p-id='4220' width='22' height='22'><path d='M796.454 985H227.545c-50.183 0-97.481-19.662-133.183-55.363-35.7-35.701-55.362-83-55.362-133.183V227.545c0-50.183 19.662-97.481 55.363-133.183 35.701-35.7 83-55.362 133.182-55.362h568.909c50.183 0 97.481 19.662 133.183 55.363 35.701 35.702 55.363 83 55.363 133.183v568.909c0 50.183-19.662 97.481-55.363 133.183S846.637 985 796.454 985zM227.545 91C152.254 91 91 152.254 91 227.545v568.909C91 871.746 152.254 933 227.545 933h568.909C871.746 933 933 871.746 933 796.454V227.545C933 152.254 871.746 91 796.454 91H227.545z' p-id='4221'></path><path d='M568.569 512l170.267-170.267c15.556-15.556 15.556-41.012 0-56.569s-41.012-15.556-56.569 0L512 455.431 341.733 285.165c-15.556-15.556-41.012-15.556-56.569 0s-15.556 41.012 0 56.569L455.431 512 285.165 682.267c-15.556 15.556-15.556 41.012 0 56.569 15.556 15.556 41.012 15.556 56.569 0L512 568.569l170.267 170.267c15.556 15.556 41.012 15.556 56.569 0 15.556-15.556 15.556-41.012 0-56.569L568.569 512z' p-id='4222'></path></svg>"); ?>
        </div>
    	      <div class="change" id="commentType">
            </div>
    	<form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
    	      <div class="commments-area">
            <div class="commments-info">
            <?php if($this->user->hasLogin()): ?>
            <div style="border-bottom: 1px dashed #dedede;">
    		<?php _e('登录身份:  '); ?>
    		<a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?><?php if($this->user->group == 'administrator'): ?> 博主 <?php elseif($this->user->group == 'editor'): ?> 编辑 <?php elseif($this->user->group == 'contributor'): ?> 贡献者 <?php elseif($this->user->group == 'subscriber'): ?> 关注者 <?php elseif($this->user->group == 'visitor'): ?> 访问者 <?php endif ?></a></a>.
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
                <div title="OwO" class="OwO"></div>
                  </div>
                  <?php if(!$this->user->hasLogin() && $this->options->EnableCommentsLogin === 'on'): ?>
                   <div class="commentsFormArea" style="float:left" id="comment_keys">
                       <b class="submit"><i class="fas fa-key"></i></b>
                   </div>
                  <?php endif; ?>
    		    <div class="commentsFormArea" style="text-align: right;">
                   <button class="submit" type="submit" ><?php _e('评论'); ?></button>
                </div>
                <?php if($this->options->siteKey !== "" && $this->options->siteKey !== ""){RecapOutPut($this->user->hasLogin()) ;?><script>$(document).ready(function(){if($("#comment_keys").length == 0){$(".g-recaptcha").css({"position":"relative","top":"-40px"})}})</script> <?php }?>
                <?php if($this->options->hcaptchaSecretKey !== "" && $this->options->hcaptchaAPIKey !== ""){
                RecapOutPut($this->user->hasLogin());?>
                <script>$(document).ready(function(){if($("#comment_keys").length == 0){$(".h-captcha").css({"position":"relative","top":"-40px"})}})</script>
                <?php }?>                  
                </form>
<?php if(!$this->user->hasLogin() && $this->options->EnableCommentsLogin === 'on'): ?>
<div id="comment_login" style="display:none">
<form onsubmit="return false" style="margin-top: 10px;">
<input type="text" class="text" name="name" autocomplete="username" placeholder="请输入用户名" required/>
<input type="password" class="text" name="password" autocomplete="current-password" placeholder="请输入密码" required/>
<button class="submit" type="submit" id="web-login">登录</button>
</form>
<script>
$("#web-login").click(function(){
    $.post("<?php $this->options->loginAction()?>",
    {"name":$("input[name=name]").val(),"password":$("input[name=password]").val()},
    function(data){
         if(data.search("GLOBAL_CONFIG")!= -1){
            Dreamer.warning("登录失败，请检查账号密码",2000);
         }else{
             Dreamer.success("登录成功，等待跳转...",function () {
                 location.reload();       
             });
         }
    })
});
</script>
</div>
<?php endif; ?>	
    </div>
    <script>
    $("#to_comment").click(function() {var hre = $(this).attr("href");$('html, body').animate({scrollTop: $(hre).offset().top}, 300);});
    $("#comment_keys").click(function(){
        $('#comment_login').slideToggle("fast");
    });
    </script>
    <script>
    var OwO_demo = new OwO({
        logo: '<i class="iconfont icon-face"></i>',
        container: document.getElementsByClassName('OwO')[0],
        target: document.getElementsByClassName('textarea')[0],
        api: '<?php $this->options->themeUrl('OwO.json'); ?>',
        position: 'down',
        width: '100%',
        maxHeight: '350px'});
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