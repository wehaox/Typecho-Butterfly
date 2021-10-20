<?php
$Str_Msg_PSWERR="或输入密码访问";
if ( isset($_POST['theme_passwd']) ){
	if (trim($_POST['theme_passwd']) == $this->options->ThemePassword){
		setcookie("theme_passwd",trim($_POST['theme_passwd']),time()+3600*24*3);
		echo '<meta http-equiv="refresh" content="0;url='.$_SERVER["REQUEST_URI"].'">';
		$success = "密码正确，请等待跳转";
	}else{
	    $Str_Msg_PSWERR="密码错误，请重新输入";
	}
}
if($_COOKIE["theme_passwd"]!==$this->options->ThemePassword && $this->options->Defend === 'on'){
?>
<html data-theme="light">
<?php  $this->need('header_com.php'); ?>
<body _c_t_common="1">
    <div id="web_bg"></div>
<div class="error404" id="body-wrap">
<div id="error-wrap">
  <div class="error-content">
    <div class="error-img" style="background-image: url(https://i.loli.net/2021/10/09/oPZA9nBlTDevy3S.png)"></div>
    <div class="error-info">
      <h1 class="error_title">403</h1>
      <div class="error_subtitle">站点维护中... 请稍后访问</div>
      <?php if( !empty($this->options->ThemePassword)): ?>
      <form action="<?php echo $_SERVER["REQUEST_URI"];?>" method="post" >
      <p>
          <input style="text-align: center" type="password" class="text" placeholder="<?php echo $Str_Msg_PSWERR;?>" name="theme_passwd">
          <input type="submit" class="submit" value="提交">
      </p>
      </form>
      <p style="color:green;font-size:14px"><?php echo $success;?></p>
      <?php endif ?>
    </div>
  </div>
</div>
<div>
</div>
</div>
</body>
</html>
<?php
exit();		    
}?>