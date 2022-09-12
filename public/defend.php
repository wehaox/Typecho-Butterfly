<?php
if (isset($_POST['pwd'])){
	if (trim($_POST['pwd']) == $this->options->ThemePassword){
		setcookie("ThemePassword",trim($_POST['pwd']),time()+3600*24*3);
		exit ('{"status": "200","info":"密码正确,芝麻开门！"}'); 
	}
}
if(isset($_COOKIE["ThemePassword"]) && $_COOKIE["ThemePassword"] !==$this->options->ThemePassword && $this->options->Defend === 'on' 
    || empty($_COOKIE["ThemePassword"]) && $this->options->Defend === 'on' ){
?>

<html data-theme="light">
    <head>
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
     <link rel="stylesheet" href="<?php $this->options->themeUrl('index.css?v1.2.0'); ?>">
     <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css?v1.2.9'); ?>">
    <?php if (!empty($this->options->beautifyBlock) && in_array('ShowBeautifyChange',$this->options->beautifyBlock)): ?> 
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/custom.css?v1.2.0'); ?>">
    <?php endif; ?>     
    </head>
<body _c_t_common="1">
    <link rel="stylesheet" href="//unpkg.com/element-plus/dist/index.css" />
    <script src="https://cdn.jsdelivr.net/npm/vue@3.2.23/dist/vue.global.prod.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="//unpkg.com/element-plus"></script>
    <div id="web_bg"></div>

<div class="error404" id="body-wrap">
<div id="error-wrap">
  <div class="error-content">
    <div class="error-img">
        <img src="https://i.loli.net/2021/10/09/oPZA9nBlTDevy3S.png" data-lazy-src="<?php echo get_ArticleThumbnail($this);?>" alt="403" class="entered">
    </div>
    <div class="error-info">
      <h1 class="error_title">403</h1>
      <div class="error_subtitle">站点维护中... 请稍后访问</div>
      <?php if( !empty($this->options->ThemePassword)): ?>
          <form onsubmit="return false">
          <input style="text-align: center" type="password" class="text" placeholder="或在此输入密码访问" v-model="pwd" autocomplete="off">
          <input type="submit" class="submit" value="提交" @click="send">
          </form>
      <?php endif ?>
    </div>
  </div>
</div>



<script>
const passwordApp = {
       data() {
           return {
               pwd: ''
          }
       },
   methods:{
       send(){
           let that = this
           let pwd = this.pwd;
           if(pwd !==""){          
           var params = new URLSearchParams() 
           params.append("pwd",pwd)
           axios.post('/', params).then(res => {  
               if(res.data.status == "200"){
                     this.$notify({
                         title: 'Success',
                         message: res.data.info,
                         type: 'success',
                         });
                     window.location = "<?php echo $_SERVER["REQUEST_URI"];?>"    
               }else{
                     this.$notify({
                         title: '打咩',
                         message: '你似乎不知道密码哦',
                         type: 'error',
                         });
               }
           }).catch(err => console.log(err));
           }else{
               this.$notify({
                   title: '警告',
                   message: '密码不能为空哦',
                   type: 'warning',});
           }
       }
   }
}
const app = Vue.createApp(passwordApp);
app.use(ElementPlus);
app.mount("#error-wrap");
</script>


<div>
</div>
</div>
</body>
</html>
<?php
exit();		    
}?>