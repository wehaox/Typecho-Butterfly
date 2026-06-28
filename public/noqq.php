<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php if ($this->options->NoQQ === 'on' && (strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') || strpos($_SERVER['HTTP_USER_AGENT'], 'qqtheme') !== false)) : ?>
<?php
    $siteurl = Typecho_Common::url($_SERVER['REQUEST_URI'], Helper::options()->siteUrl);
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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('index.css?v1.2.0'); ?>">
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css?v1.2.9'); ?>">
    <?php if (!empty($this->options->beautifyBlock) && in_array('ShowBeautifyChange',$this->options->beautifyBlock)): ?> 
    <link rel="stylesheet" href="<?php $this->options->themeUrl('css/custom.css?v1.2.0'); ?>">
    <?php endif; ?>    
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
    <div class="error-img">
                <img src="https://i.loli.net/2021/10/11/rVR97X3zmoEj2WA.png" data-lazy-src="<?php echo get_ArticleThumbnail($this);?>" alt="到浏览器访问" class="entered">
    </div>
    <div class="error-info">
      <b class="title">{{ title }}</b>
      <p style="word-break: break-word">{{ url }} </p>
       <el-button style="width: 65%;margin: 0 auto;" type="primary" @click="copy">或点我复制网址</el-button>
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
