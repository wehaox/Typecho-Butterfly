<?php if ($this->options->NoQQ === 'on' && (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') || strpos($_SERVER['HTTP_USER_AGENT'], 'qqtheme') !== false)) : ?>
<?php
    $siteurl = ($_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<script>
    let browser = navigator.userAgent.toLowerCase();
    if (browser.indexOf('qq') == -1 && browser.indexOf('qqtheme') == -1) {
        window.location.href = "<?php echo $siteurl; ?>">";
    }
</script>
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//unpkg.com/element-plus/dist/index.css" />
    <script src="https://unpkg.com/vue@next"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/element-plus"></script>
    <script src="https://cdn.jsdelivr.net/gh/Inndy/vue-clipboard2@master/dist/vue-clipboard.min.js"></script>
    <title>请到浏览器访问      👉</title>
    <?php  $this->need('header_com.php'); ?>
</head>
<body>
    <style>
        .title{
            font-size: 18px;
        }
    </style>
    <div id="web_bg"></div> 
<div class="error404" id="body-wrap">
<div id="error-wrap">
  <div class="error-content">
    <div class="error-img" style="background-image: url(https://i.loli.net/2021/10/11/rVR97X3zmoEj2WA.png)"></div>
    <div class="error-info">
      <b class="title">{{ title }}</b>
      <p style="word-break: break-word">{{ url }} </p>
       <el-button type="primary" @click="copy">或点我复制网址</el-button>
    </div>
  </div>
</div>
<div>
</div>
</div>
<script>
const NoQQ = {
       data() {
           return {
               url: "<?php echo $siteurl; ?>",
               title: "本站不支持手机QQ访问，请在浏览器中打开",
            } 
       },
       methods:{
           copy(){
               var that = this;
               this.$copyText(this.url).then(function (e) {
                 that.$notify({
                   title: 'Success',
                   dangerouslyUseHTMLString: true,
                   message: "已复制<br>" + that.url,
                   type: 'success',
                 });
        }, function (e) {
           that.$notify({
                   title: 'error',
                   dangerouslyUseHTMLString: true,
                   message: "复制失败",
                   type: 'error',
            });
        })
           }
    }
}
const app = Vue.createApp(NoQQ);
app.use(ElementPlus);
app.use(VueClipboard);
app.mount("#error-wrap");
</script>
</body>
</html>
<?php exit; ?>
<?php endif; ?>