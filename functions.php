<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
function themeConfig($form) {
    ?>
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/wehaox/CDN@0.2/css/themedash.css">-->
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('css/themedash.css'); ?>">
    <div class='set_toc' >
    <div class='mtoc'>
    <a href='#themeBackup'>主题备份与还原</a>
    <a href='#cids'>文章置顶及公共部分</a>
    <a href='#friends'>友情链接设置</a>
    <a href='#reward'>打赏功能</a>
    <a href='#aside'>侧边栏显示设置</a>
    <a href='#beautifyBlock'>美化选项</a>
    <a href='#ShowLive2D'>Live2D设置</a>
    <a href='#CustomSet'>自定义内容</a>
    </div></div>
    <form class="protected" action="?butterflybf" method="post" id="themeBackup">
        <input type="submit" name="type" class="btn btn-s" value="备份主题数据" />&nbsp;&nbsp;<input type="submit" name="type" class="btn btn-s" value="还原主题数据" />&nbsp;&nbsp;<input type="submit" name="type" class="btn btn-s" value="删除备份数据" /></form>
    <script src='https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js'></script>
    <script src="<?php Helper::options()->themeUrl('js/themecustom.js?v1.1.9'); ?>"></script>
    <script src='https://cdn.jsdelivr.net/gh/wehaox/CDN@main/postdomai.js'></script>
    <?php
    $sticky_cids = new Typecho_Widget_Helper_Form_Element_Text('sticky_cids', NULL, NULL,'置顶文章的 cid', '<div style="font-family:arial; background:#E8EFD1; padding:8px">按照排序输入, 请以半角逗号或空格分隔 cid</div>');
    $sticky_cids->setAttribute('id', 'cids');
    $form->addInput($sticky_cids);
    
    $NewTabLink = new Typecho_Widget_Helper_Form_Element_Select('NewTabLink',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否开启新标签打开外部链接',
        '介绍：非站内链接在新标签打开'
    );
    $form->addInput($NewTabLink->multiMode());
    
    $showFramework = new Typecho_Widget_Helper_Form_Element_Select('showFramework',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否显示底部博客框架和主题',
        '介绍：如果你是小白自行修改主题名会导致侵权提示，你可以在这里关闭同时希望你可以<b>尊重本主题</b>'
    );
    $form->addInput($showFramework->multiMode());
    
    $Defend = new Typecho_Widget_Helper_Form_Element_Select(
        'Defend',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启网站维护或密码访问',
        '介绍：开启后，网站所有页面将会显示维护或者输入密码访问，登录用户不受限制'
    );
    $form->addInput($Defend->multiMode());
    
    $ThemePassword = new Typecho_Widget_Helper_Form_Element_Text('ThemePassword', NULL,NULL, _t('全站密码访问(非必填)'), _t('输入访问网站的密码，<b>需要在上方开启网站维护或密码访问</b>'));
    $form->addInput($ThemePassword);
    
     $NoQQ = new Typecho_Widget_Helper_Form_Element_Select(
        'NoQQ',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启网站禁止手机QQ访问',
        '介绍：烦人的QQ'
    );
    $form->addInput($NoQQ->multiMode());
    
    $Sitefavicon = new Typecho_Widget_Helper_Form_Element_Text('Sitefavicon', NULL, NULL, _t('网站图标'), _t('网站图标，使用png格式，大小建议不超过64x64'));
    $form->addInput($Sitefavicon);
    
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL,_t('#null'), _t('作者头像'), _t('在这里填入图片地址，它会显示在右侧栏的作者头像'));
    $form->addInput($logoUrl);
    
    $author_description = new Typecho_Widget_Helper_Form_Element_Text('author_description', NULL, _t('作者描述'), _t('作者描述'), _t('在这里填入站点描述，它会显示在右侧栏的作者信息'));
    $form->addInput($author_description);
    
    $author_site_description = new Typecho_Widget_Helper_Form_Element_Text('author_site_description', NULL,_t('个人网站'), _t('作者链接描述'), _t('作者链接描述'));
    $form->addInput($author_site_description);
    
    $author_site = new Typecho_Widget_Helper_Form_Element_Text('author_site', NULL, _t('#null'), _t('作者链接'), _t('在这里填入作者链接，它会显示在右侧栏的作者信息的个人网站上'));
    $form->addInput($author_site);
    
    $author_bottom = new Typecho_Widget_Helper_Form_Element_Textarea('author_bottom', NULL, _t(''), _t('侧栏作者信息最底部内容（非必填）'), _t('这里填入html代码,不会勿填'));
    $form->addInput($author_bottom);
    
    $announcement = new Typecho_Widget_Helper_Form_Element_Textarea('announcement', NULL, _t('这里是公告<br>'), _t('公告'), _t('在这里填入公告，它会显示在右侧栏的公告上,采用html写法'));
    $form->addInput($announcement);
    
    $headerimg = new Typecho_Widget_Helper_Form_Element_Text('headerimg', NULL,_t('https://tva1.sinaimg.cn/large/007X0Rdyly1ghm1qiihrdj31hc0u07jk.jpg'), _t('主页顶图(banner image)'), _t('填入主页头图链接'));
    $form->addInput($headerimg);
    
    $buildtime = new Typecho_Widget_Helper_Form_Element_Text('buildtime', NULL,_t('2021/04/05'), _t('建站时间'), _t('按照输入框内格式填写'));
    $form->addInput($buildtime);
    
    $outoftime = new Typecho_Widget_Helper_Form_Element_Text('outoftime', NULL,_t('15'), _t('文章过时提醒'), _t('设置文章过时提醒最大天数，默认15天，可在文章管理单独设置是否显示过期提醒'));
    $form->addInput($outoftime);
    
    $archivelink = new Typecho_Widget_Helper_Form_Element_Text('archivelink', NULL,_t('#null'), _t('侧栏文章(归档)链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($archivelink);
    
    $tagslink = new Typecho_Widget_Helper_Form_Element_Text('tagslink', NULL,_t('#null'), _t('侧栏标签链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($tagslink);
    
    $categorylink = new Typecho_Widget_Helper_Form_Element_Text('categorylink', NULL,_t('#null'), _t('侧栏分类链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($categorylink);
    
    
    $CloseComments = new Typecho_Widget_Helper_Form_Element_Select('CloseComments',
    array(
        'off' => '关闭（默认）',
        "on" => '开启'
        ),
        'off',
        '全站关闭评论',
        '介绍：开启后所有文章不能评论'
    );
    $form->addInput($CloseComments->multiMode());    
    
    $EnableCommentsLogin = new Typecho_Widget_Helper_Form_Element_Select('EnableCommentsLogin',
    array(
        'off' => '关闭（默认）',
        "on" => '开启'
        ),
        'off',
        '开启用户评论区登录',
        '介绍：开启后在评论区会显示登录按钮'
    );
    $form->addInput($EnableCommentsLogin->multiMode());
    
    
    
    $EnablePjax = new Typecho_Widget_Helper_Form_Element_Select('EnablePjax',
    array(
        'off' => '关闭（默认）',
        "on" => '开启'
        ),
        'off',
        '开启Pjax(实验性功能,如发生页面错误关闭此选项)',
        '介绍：页面无刷新加载,有效提高页面加载速度<br>
         此功能目前为实验性功能，请查看<a href="https://blog.wehaox.com/archives/typecho-butterfly.html#cl-13">使用文档</a>'
    );
    $form->addInput($EnablePjax->multiMode());
    
    /* 友链设置 */
    $Friends = new Typecho_Widget_Helper_Form_Element_Textarea('Friends',NULL,NULL,
        '友情链接（非必填）',
        '介绍：用于填写友情链接 <br />
         注意：需在独立页面创建友链，该项才会生效 <br />
         格式：博客名称 || 博客地址 || 博客头像 || 博客简介 <br />
         其他：一行一个，一行代表一个友链'
    );
    $Friends->setAttribute('id', 'friends');
    $form->addInput($Friends);
    
    $LazyLoad = new Typecho_Widget_Helper_Form_Element_Text('LazyLoad',
        NULL,
        NULL,
        '全局懒加载图（非必填）',
        '介绍：用于修改懒加载图片 格式：base64 或者 图片url'
    );
    $form->addInput($LazyLoad);
    

    $ShowGlobalReward = new Typecho_Widget_Helper_Form_Element_Select('ShowGlobalReward',
        array(
            'off' => '关闭（默认）',
            'show' => '开启打赏',
        ),
        'off',
        '是否开启全局文章打赏',
        '介绍：开启此功能所有文章将显示打赏'
    );
    $ShowGlobalReward->setAttribute('id', 'reward');
    $form->addInput($ShowGlobalReward->multiMode());
    
    /* 打赏设置 */
    $RewardInfo = new Typecho_Widget_Helper_Form_Element_Textarea('RewardInfo',NULL,_t('微信 || https://cdn.jsdelivr.net/gh/wehaox/CDN@main/reward/wechat.jpg
支付宝 || https://cdn.jsdelivr.net/gh/wehaox/CDN@main/reward/alipay.jpg'),
        '打赏信息（非必填）',
        '注意：需在开启打赏功能，该项才会显示 <br />
         格式：打赏名称 || 图片地址 <br />一行一个'
    );
    $form->addInput($RewardInfo);    


    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('sidebarBlock', 
    array(
    'ShowAnnounce' => _t('显示公告'),
    'ShowRecentPosts' => _t('显示最新文章'),
    'ShowRecentComments' => _t('显示最近回复'),
    'ShowCategory' => _t('显示分类'),
    'ShowTag' => _t('显示标签'),
    'ShowArchive' => _t('显示归档'),
    'ShowWebinfo' => _t('显示网站咨询'),
    'ShowOther' => _t('显示其它杂项'),
    'ShowMobileSide' => _t('手机端显示侧栏')
    ),
    array('ShowAnnounce','ShowRecentPosts', 'ShowRecentComments', 'ShowCategory','ShowTag', 'ShowArchive', 'ShowWebinfo', 'ShowOther','ShowMobileSide'), _t('侧边栏显示'));
    $sidebarBlock->setAttribute('id', 'aside');
    $form->addInput($sidebarBlock->multiMode());
    // 在线人数显示
    $ShowOnlinePeople = new Typecho_Widget_Helper_Form_Element_Select('ShowOnlinePeople',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否显示在线人数',
        '介绍：侧栏网站咨询模块在线人数统计,防止某些虚拟主机无法开启导致500错误'
    );
    $form->addInput($ShowOnlinePeople->multiMode());
    // 文章侧边栏设置
    $PostSidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('PostSidebarBlock', 
    array(
    // 'ShowAuthorInfo' => _t('显示作者信息'),
    // 'ShowAnnounce' => _t('显示公告'),
    'ShowRecentPosts' => _t('显示最新文章'),
    'ShowWebinfo' => _t('显示网站咨询'),
    'ShowOther' => _t('显示其它杂项')),
    array('ShowRecentPosts', 'ShowWebinfo', 'ShowOther'), _t('文章侧边栏显示'),_t('说明:单独设置文章内侧栏'));
    $form->addInput($PostSidebarBlock->multiMode());

// 美化选项
    $beautifyBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('beautifyBlock', 
    array(
    'ShowBeautifyChange' => _t('是否开启美化(基于butterfly小康的魔改)'),
    'ShowColorTags' => _t('是否开启彩色标签云'),
    'ShowTopimg' => _t('是否显示主页顶图'),
    'PostShowTopimg' => _t('是否显示文章示顶图'),
    'PageShowTopimg' => _t('是否显示独立页面顶图'),
    'showLineNumber' => _t('是否显示代码块行号'),
    'showSnackbar' => _t('是否显示主题以及简繁切换弹窗'),
    'showLazyloadBlur' => _t('是否开启懒加载模糊效果'),
    ),
    array('ShowTopimg','PostShowTopimg','PageShowTopimg','showLineNumber','showLazyloadBlur'), _t('美化选项'));
    $beautifyBlock->setAttribute('id', 'beautifyBlock');
    $form->addInput($beautifyBlock->multiMode());
    
    $ShowLive2D = new Typecho_Widget_Helper_Form_Element_Select('ShowLive2D',
    array(
        'off' => '关闭（默认）',
        "on" => '开启（GitHub默认）'
        ),
        'off',
        '开启Live2D人物模型（仅通过GitHub默认样式且不会在手机显示）',
        '介绍：开启后会在右下角显示一个小人，该功能采用远程调用不会消耗性能'
    );
    $ShowLive2D->setAttribute('id', 'ShowLive2D');
    $form->addInput($ShowLive2D->multiMode());
    
    // 弹窗提示
    $SnackbarPosition = new Typecho_Widget_Helper_Form_Element_Select('SnackbarPosition',
        array(
            'top-left' => '左上(默认)',
            'top-center' => '上中',
            'top-right' => '右上',
            'bottom-left' => '左下',
            'bottom-center' => '下中',
            'bottom-right' => '右下',
        ),
        'top-left',
        '主题以及简繁切换弹窗位置',
        '选择其中一个,需要开启是否显示主题以及简繁切换弹窗 '
    );
    $form->addInput($SnackbarPosition->multiMode());
    
    
    $CursorEffects = new Typecho_Widget_Helper_Form_Element_Select('CursorEffects',
        array(
            'off' => '关闭（默认）',
            'heart' => '鼠标点击效果:爱心',
            'fireworks' => '烟火效果',
        ),
        'off',
        '选择鼠标点击特效',
        '介绍：用于切换鼠标点击特效 '
    );
    $form->addInput($CursorEffects->multiMode());
     // 自定义subtitle
    $CustomSubtitle = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomSubtitle',
        NULL,
        NULL,
        '自定义主页副标题/subtitle（非必填）',
        '介绍：不填则使用默认的一言api。'
    );
    $CustomSubtitle->setAttribute('id', 'CustomSet');
    $form->addInput($CustomSubtitle);
    
    $SubtitleLoop = new Typecho_Widget_Helper_Form_Element_Select('SubtitleLoop',
    array(
        'true' => '开启循环打字（默认）',
        "false" => '关闭循环打字'
        ),
        'true',
        '副标题循环打字',
        '介绍：开启后主页副标题循环打字'
    );
    $form->addInput($SubtitleLoop->multiMode());
    
    
    $EnableAutoHeaderLink = new Typecho_Widget_Helper_Form_Element_Select('EnableAutoHeaderLink',
    array(
        'on' => '开启（默认）',
        "off" => '关闭'
        ),
        'on',
        '自动生成导航栏独立页面链接',
        '介绍：如果你要自定义导航栏链接部分,你可以选择关闭此项'
    );
    $form->addInput($EnableAutoHeaderLink->multiMode());    
    
     // 自定义导航栏链接
    $CustomHeaderLink = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomHeaderLink',
        NULL,
        NULL,
        '自定义导航栏链接',
        '介绍：目前使用html写法'
    );
    $form->addInput($CustomHeaderLink);        
    
    // 自定义认证用户
    $CustomAuthenticated = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomAuthenticated',
        NULL,
        NULL,
        '自定义认证用户',
        '介绍：评论区认证用户专属头衔<br>
         格式：邮箱||认证头衔 如:<br> xxx@xxx.com||xxx认证<br>
        (一行一个)'
    );
    $form->addInput($CustomAuthenticated);
    
    //自定义颜色    
    $EnableCustomColor = new Typecho_Widget_Helper_Form_Element_Select('EnableCustomColor',
    array(
        "false" => '关闭（默认）',
        'true' => '开启'
        ),
        'false',
        '开启主题自定义颜色(实验性功能)',
        '介绍：需要开启此选项下面的自定义颜色才能生效'
    );
    $form->addInput($EnableCustomColor->multiMode());    
 
    $CustomColorMain = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorMain',
        NULL,
        NULL,
        '自定主题主要颜色',
        '介绍：使用hex格式如#fff'
    );
    $form->addInput($CustomColorMain);
    
    $CustomColorButtonBG = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorButtonBG',
        NULL,
        NULL,
        '自定按钮颜色',
        '介绍：'
    );
    $form->addInput($CustomColorButtonBG);        
    
    $CustomColorButtonHover = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorButtonHover',
        NULL,
        NULL,
        '自定按钮悬停色',
        '介绍：'
    );
    $form->addInput($CustomColorButtonHover);
    
    $CustomColorSelection = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorSelection',
        NULL,
        NULL,
        '自定文本选择色',
        '介绍：'
    );
    $form->addInput($CustomColorSelection);    
    //自定义颜色end

     // 自定义css和js
    $CustomCSS = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomCSS',
        NULL,
        NULL,
        '自定义CSS样式（非必填）',
        '介绍：请填写自定义CSS内容，填写时无需填写style标签。'
    );
    $form->addInput($CustomCSS);
    
    $CustomScript = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomScript',
        NULL,
        NULL,
        '自定义JS代码（非必填，请看下方介绍）',
        '介绍：请填写自定义JS内容，填写时无需填写script标签。<br />
         非专业人士请勿填写！'
    );
    $form->addInput($CustomScript);
    
    $CustomHead = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomHead',
        NULL,
        NULL,
        '自定义head标签内位置内容',
        '介绍：填写如cdn的&lt;link&gt;标签、百度统计代码等等'
    );
    $form->addInput($CustomHead);

    $CustomBodyEnd = new Typecho_Widget_Helper_Form_Element_Textarea(
        'CustomBodyEnd',
        NULL,
        NULL,
        '自定义body标签末尾位置内容',
        '介绍：填写如cdn的&lt;script&gt;&lt;/script&gt;标签等等'
    );
    $form->addInput($CustomBodyEnd);


    
    $Customfooter = new Typecho_Widget_Helper_Form_Element_Textarea(
        'Customfooter',
        NULL,
        NULL,
        '自定义Footer内容',
        '介绍：网页底部的信息，如备案号等等(可使用html)'
    );
    $form->addInput($Customfooter);
    
$db = Typecho_Db::get();
$sjdq=$db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:butterfly'));
$ysj = $sjdq['value'];
if(isset($_POST['type']))
{ 
if($_POST["type"]=="备份主题数据"){
if($db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:butterflybf'))){
$update = $db->update('table.options')->rows(array('value'=>$ysj))->where('name = ?', 'theme:butterflybf');
$updateRows= $db->query($update);
echo '<div class="tongzhi">备份已更新，请等待自动刷新！如果等不到请点击';
?>    
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);</script>
<?php
}else{
if($ysj){
     $insert = $db->insert('table.options')->rows(array('name' => 'theme:butterflybf','user' => '0','value' => $ysj));
     $insertId = $db->query($insert);
echo '<div class="tongzhi">备份完成，请等待自动刷新！如果等不到请点击';
?>    
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);</script>
<?php
}
}
        }
if($_POST["type"]=="还原主题数据"){
if($db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:butterflybf'))){
$sjdub=$db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:butterflybf'));
$bsj = $sjdub['value'];
$update = $db->update('table.options')->rows(array('value'=>$bsj))->where('name = ?', 'theme:butterfly');
$updateRows= $db->query($update);
echo '<div class="tongzhi">检测到主题备份数据，恢复完成，请等待自动刷新！如果等不到请点击';
?>    
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2000);</script>
<?php
}else{
echo '<div class="tongzhi">没有主题备份数据，恢复不了哦！</div>';
}
}
if($_POST["type"]=="删除备份数据"){
if($db->fetchRow($db->select()->from ('table.options')->where ('name = ?', 'theme:butterflybf'))){
$delete = $db->delete('table.options')->where ('name = ?', 'theme:butterflybf');
$deletedRows = $db->query($delete);
echo '<div class="tongzhi">删除成功，请等待自动刷新，如果等不到请点击';
?>    
<a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
<script language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);</script>
<?php
}else{
echo '<div class="tongzhi">不用删了！备份不存在！！！</div>';
}
}
    }
// 结束
}

function themeFields($layout)
{
    $thumb = new Typecho_Widget_Helper_Form_Element_Text(
        'thumb',
        NULL,
        NULL,
        '自定义文章缩略图',
        '填写时：将会显示填写的文章缩略图 <br>不填写时采用默认图片'
    );
    $layout->addItem($thumb);

    $desc = new Typecho_Widget_Helper_Form_Element_Text(
        'desc',
        NULL,
        NULL,
        'SEO描述',
        '用于填写文章或独立页面的SEO描述，如果不填写则没有'
    );
    $layout->addItem($desc);

    $keywords = new Typecho_Widget_Helper_Form_Element_Text(
        'keywords',
        NULL,
        NULL,
        'SEO关键词',
        '用于填写文章或独立页面的SEO关键词，如果不填写则没有'
    );
    $layout->addItem($keywords);
    
    
    $showTimeWarning = new Typecho_Widget_Helper_Form_Element_Select(
        'showTimeWarning',
        array(
            'on' => '开启（默认）',
            'off' => '关闭'
        ),
        'on',
        '是否开启当前页面的文章过期警告',
        '用于单独设置当前文章的过期警告 <br /> <b>仅在文章内作用,独立页面无需改动</b> <br />'
    );
    $layout->addItem($showTimeWarning);
    
    $ShowReward = new Typecho_Widget_Helper_Form_Element_Select('ShowReward',
        array(
            'off' => '关闭（默认）',
            'show' => '开启打赏',
        ),
        'off',
        '是否开启文章打赏',
        '介绍：开启此功能需要在主题设置中添加二维码图片'
    );
    $layout->addItem($ShowReward);
    $ShowToc = new Typecho_Widget_Helper_Form_Element_Select('ShowToc',
        array(
            'show' => '开启（默认）',
            'off' => '关闭',
        ),
        'show',
        '是否显示文章目录',
        '介绍：或许有的文章不需要目录功能,默认是开启的,一般不需要设置'
    );
    $layout->addItem($ShowToc);
    
    
    
}
// 新文章缩略图
function get_ArticleThumbnail($widget){
  // 当文章无图片时的随机缩略图
  $rand = mt_rand(1, 26); // 随机 1-9 张缩略图
  // 缩略图加速
  $rand_url;
  if(!empty(Helper::options()->articleImgSpeed)){
    $rand_url = Helper::options()->articleImgSpeed;
  }else {
    $rand_url = $widget->widget('Widget_Options')->themeUrl . '/images/articles/';
  }
//   $random =  $rand_url . $rand . '.jpg'; // 随机缩略图路径
  $random =  'https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg';
  $attach = $widget->attachments(1)->attachment;
  $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';

  //如果有自定义缩略图
  if($widget->fields->thumb) {
    return $widget->fields->thumb;
  }else if (preg_match_all($pattern, $widget->content, $thumbUrl) && strlen($thumbUrl[1][0]) > 7) {
      return $thumbUrl[1][0];
  } else if ($attach->isImage) {
      return $attach->url;
  } else {
      return $random;
  }
};




// 主页文章缩略图
function GetRandomThumbnail($widget)
{
    $random = 'https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg';
    if (Helper::options()->Jmos) {
        $moszu = explode("\r\n", Helper::options()->Jmos);
        $random = $moszu[array_rand($moszu, 1)] . "?jrandom=" . mt_rand(0, 1000000);
    }
    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
    $patternMD = '/\!\[.*?\]\((http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
    $patternMDfoot = '/\[.*?\]:\s*(http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
    $t = preg_match_all($pattern, $widget->content, $thumbUrl);
    $img = $random;
    if ($widget->fields->thumb) {
        $img = $widget->fields->thumb;
    } elseif ($t) {
        $img = $thumbUrl[1][0];
    } elseif (preg_match_all($patternMD, $widget->content, $thumbUrl)) {
        $img = $thumbUrl[1][0];
    } elseif (preg_match_all($patternMDfoot, $widget->content, $thumbUrl)) {
        $img = $thumbUrl[1][0];
    }
    echo $img;
}
// 文章封面缩略图
function GetRandomThumbnailPost($widget)
{
    if (Helper::options()->Jmos) {
        $moszu = explode("\r\n", Helper::options()->Jmos);
        $random = $moszu[array_rand($moszu, 1)] . "?jrandom=" . mt_rand(0, 1000000);
    }
    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
    $patternMD = '/\!\[.*?\]\((http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
    $patternMDfoot = '/\[.*?\]:\s*(http(s)?:\/\/.*?(jpg|jpeg|gif|png|webp))/i';
    $t = preg_match_all($pattern, $widget->content, $thumbUrl);
    $img = $random;
    if ($widget->fields->thumb) {
        $img = $widget->fields->thumb;
    }
    echo $img;
}

// 文章字数统计
function  art_count ($cid){
$db=Typecho_Db::get ();
$rs=$db->fetchRow ($db->select ('table.contents.text')->from ('table.contents')->where ('table.contents.cid=?',$cid)->order ('table.contents.cid',Typecho_Db::SORT_ASC)->limit (1));
echo mb_strlen($rs['text'], 'UTF-8');
}
// 文章字数统计2
function charactersNum($archive) {
    return mb_strlen($archive->text,'UTF-8');
}

// 全站字数统计
function allOfCharacters() {
        $showPrivate = intval($pluginOpts->showPrivate);
        $chars = 0;
        $db = Typecho_Db::get();
        if($showPrivate == 0){
            $select = $db ->select('text')->from('table.contents')->where('table.contents.status = ?','publish');
        }else {
            $select = $db ->select('text')->from('table.contents');
        }
        $rows = $db->fetchAll($select);
        foreach ($rows as $row){
        $chars += mb_strlen($row['text'], 'UTF-8');
        }
        $unit = '';
        if($chars >= 10000) {
            $chars /= 10000;
            $unit = 'W';
        }else if($chars >= 1000) {
            $chars /= 1000;
            $unit = 'K';
        }
        $out = sprintf('%.2lf %s',$chars, $unit);
        echo $out;
}

function thumb($cid) {
if (empty($imgurl)) {
$rand_num = 10; //随机图片数量，根据图片目录中图片实际数量设置
if ($rand_num == 0) {
$imgurl = "img/0.jpg";
//如果$rand_num = 0,则显示默认图片，须命名为"0.jpg"，注意是绝对地址
    }else{
$imgurl = "img/".rand(1,$rand_num).".jpg";
//随机图片，须按"1.jpg","2.jpg","3.jpg"...的顺序命名，注意是绝对地址
}
}
 $db = Typecho_Db::get();
 $rs = $db->fetchRow($db->select('table.contents.text')
    ->from('table.contents')
    ->where('table.contents.type = ?', 'attachment')
    ->where('table.contents.parent= ?', $cid)
    ->order('table.contents.cid', Typecho_Db::SORT_ASC)
    ->limit(1));
$img = unserialize($rs['text']);
if (empty($img)){
    echo $imgurl;
}
else{
    echo '你的博客地址'.$img['path'];
}
}


// 评论时间
function timesince($older_date,$comment_date = false) {
$chunks = array(
array(86400 , '天'),
array(3600 , '小时'),
array(60 , '分'),
array(1 , '秒'),
);
$newer_date = time();
$since = abs($newer_date - $older_date);
if($since < 2592000){
for ($i = 0, $j = count($chunks); $i < $j; $i++){
$seconds = $chunks[$i][0];
$name = $chunks[$i][1];
if (($count = floor($since / $seconds)) != 0) break;
}
$output = $count.$name.'前';
}else{
$output = !$comment_date ? (date('Y-m-j G:i', $older_date)) : (date('Y-m-j', $older_date));
}
return $output;
}



// 文章内获取第一张图做封面
function getPostImg($archive) {
    $img = array();
    //  匹配 img 的 src 的正则表达式
    preg_match_all("/<img.*?src=\"(.*?)\".*?\/?>/i", $archive->content, $img);
    //  判断是否匹配到图片
    if (count($img) > 0 && count($img[0]) > 0) {
        //  返回图片
        return $img[1][0];
    } else {
        //  如果没有匹配到就返回 none
        return 'none';
    }
}

function createCatalog($obj) {    //为文章标题添加锚点
    global $catalog;
    global $catalog_count;
    $catalog = array();
    $catalog_count = 0;
    $obj = preg_replace_callback('/<h([1-6])(.*?)>(.*?)<\/h\1>/i', function($obj) {
        global $catalog;
        global $catalog_count;
        $catalog_count ++;
        $catalog[] = array('text' => trim(strip_tags($obj[3])), 'depth' => $obj[1], 'count' => $catalog_count);
        return '<h'.$obj[1].$obj[2].' id="cl-'.$catalog_count.'"><a class="markdownIt-Anchor" href="#cl-'.$catalog_count.'"></a>'.$obj[3].'</h'.$obj[1].'>';
    }, $obj);
    return $obj;
}


// 目录树
function getCatalog() {    //输出文章目录容器
    global $catalog;
    $index = '';
    if ($catalog) {
        $prev_depth = '';
        $to_depth = 0;
        foreach($catalog as $catalog_item) {
            $catalog_depth = $catalog_item['depth'];
            if ($prev_depth) {
                if ($catalog_depth == $prev_depth) {
                    $index .= '</li >'."\n";
                } elseif ($catalog_depth > $prev_depth) {
                    $to_depth++;
                    $index .= '<ol class="toc-child">'."\n";
                } else {
                    $to_depth2 = ($to_depth > ($prev_depth - $catalog_depth)) ? ($prev_depth - $catalog_depth) : $to_depth;
                    if ($to_depth2) {
                        for ($i=0; $i<$to_depth2; $i++) {
                            $index .= '</li>'."\n".'</ol>'."\n";
                            $to_depth--;
                        }
                    }
                    $index .= '</li>';
                }
            }
            $index .= '<li class="toc-item">
            <a class="toc-link" href="#cl-'.$catalog_item['count'].'">
            <span class="toc-number"></span>
            <span class="toc-text">'.$catalog_item['text'].'</span>
            </a>';
            $prev_depth = $catalog_item['depth'];
        }
        for ($i=0; $i<=$to_depth; $i++) {
            $index .= '</li>'."\n";
        }
    // $index = '<div >'."\n".'<div >'."\n"."\n".$index.'</div>'."\n".'</div>'."\n";
    }
    echo $index;
}

/* 获取懒加载图片 */
function GetLazyLoad()
{
    if (Helper::options()->LazyLoad) {
        return Helper::options()->LazyLoad;
    } else {
       return "data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=";
    }
}

/* 格式化标签 */
function ParseCode($text)
{
    $text = Short_Tabs($text);
    $text = Note_Fsm($text);
    $text = Note_Ico($text);
    $text = Hide_Lnline($text);
    $text = Hide_Block($text);
    $text = Hide_Toggle($text);
    $text = Button($text);
    $text = Cheak_Box($text);
    $text = inline_Tag($text);
    $text = Bf_Radio($text);
    $text = Bf_Mark($text);
    $text = PostImage($text);
    return $text;
}
// 标签外挂-Tabs
function Short_Tabs($text)
{
    $text = preg_replace_callback('/<p>\[tabs\](.*?)\[\/tabs\]<\/p>/ism', function ($text) {
        return '[tabs]' . $text[1] . '[/tabs]';
    }, $text);
    $text = preg_replace_callback('/\[tabs\](.*?)\[\/tabs\]/ism', function ($text) {
        return preg_replace('~<br.*?>~', '', $text[0]);
    }, $text);
    $text = preg_replace_callback('/\[tabs\](.*?)\[\/tabs\]/ism', function ($text) {
        $tabname = '';
        preg_match_all('/label=\"(.*?)\"\]/i', $text[1], $tabnamearr);
        for ($i = 0; $i < count($tabnamearr[1]); $i++) {
            if ($i === 0) {
                $tabname .= '<li class="tab active"><button type="button" data-href="' . $i . '">' . $tabnamearr[1][$i] . '</button></li>';
            } else {
                $tabname .= '<li class="tab"  data-href="' . $i . '"><button type="button" data-href="' . $i . '">' . $tabnamearr[1][$i] . '</button></li>';
            }
        }
        $tabcon = '';
        preg_match_all('/"\](.*?)\[\//i', $text[1], $tabconarr);
        for ($i = 0; $i < count($tabconarr[1]); $i++) {
            if ($i === 0) {
                $tabcon .= '<div class="tab-item-content active" id="' . $i . '">' . $tabconarr[1][$i] . ' <button type="button" class="tab-to-top" aria-label="scroll to top"><i class="fas fa-arrow-up"></i></button></div>';
            } else {
                $tabcon .= '<div class="tab-item-content" id="' . $i . '">' . $tabconarr[1][$i] . '<button type="button" class="tab-to-top" aria-label="scroll to top"><i class="fas fa-arrow-up"></i></button></div>';
            }
        }
        return '
        <div class="tabs" id="tags"><ul class="nav-tabs">' . $tabname . '</ul><div class="tab-contents">' . $tabcon . '</div></div>';
    }, $text);
    return $text;
}
// 标签外挂-btn
function Button($text)
{
    $text = preg_replace_callback('/\[btn href=\"(.*?)\" type=\"(.*?)\".*?\ ico=\"(.*?)\"](.*?)\[\/btn\]/ism', function ($text) {
        return '<a href="' . $text[1] . '" class="btn-beautify button--animated ' . $text[2] . '">
        <i class=" ' . $text[3] . '"></i><span>' . $text[4] . '</span></a>';
    }, $text);
    return $text;
}

// 标签外挂-note
function Note_Fsm($text)
{
    $text = preg_replace_callback('/\[note type=\"(.*?)\".*?\](.*?)\[\/note\]/ism', function ($text) {
        return '<div class="note ' . $text[1] . '"> <p>' . $text[2] . '</p></div>';
    }, $text);
    return $text;
}
// 标签外挂-note_ico
function Note_Ico($text)
{
    $text = preg_replace_callback('/\[note-ico type=\"(.*?)\".*?\ ico=\"(.*?)\"](.*?)\[\/note-ico\]/ism', function ($text) {
        return '<div class="note ' . $text[1] . '"><i class="' . $text[2] . '"></i><p>' . $text[3] . '</p></div>';
    }, $text);
    return $text;
}
// hide-inline
function Hide_Lnline($text)
{
    $text = preg_replace_callback('/\[hide-inline name=\"(.*?)\".*?\](.*?)\[\/hide-inline\]/ism', function ($text) {
        return '<span class="hide-inline"><button type="button" class="hide-button button--animated">' . $text[1] . '</button><span class="hide-content">' . $text[2] . '</span></span>';
    }, $text);
    return $text;
}
// hide-block
function Hide_Block($text)
{
    $text = preg_replace_callback('/\[hide-block name=\"(.*?)\".*?\](.*?)\[\/hide-block\]/ism', function ($text) {
        return '<div class="hide-block"><button type="button" class="hide-button button--animated">' . $text[1] . '</button><div class="hide-content">' . $text[2] . '</div></div>';
    }, $text);
    return $text;
}

// hide-toggle
function Hide_Toggle($text)
{
    $text = preg_replace_callback('/\[hide-toggle name=\"(.*?)\".*?\](.*?)\[\/hide-toggle\]/ism', function ($text) {
        return '<div class="hide-toggle"><div class="hide-button toggle-title"><i class="fas fa-caret-right fa-fw"></i>
               <span>' . $text[1] . ' </span></div><div class="hide-content">' . $text[2] . '</div></div>';
    }, $text);
    return $text;
}
// 复选框
function Cheak_Box($text)
{
    $text = preg_replace_callback('/\[cb type=\"(.*?)\".*?\ checked=\"(.*?)"\](.*?)\[\/cb\]/ism', function ($text) {
        return '<div class="checkbox '. $text[1] .' checked"><input type="checkbox" '. $text[2] .'>'. $text[3] .'</div>';
    }, $text);
    return $text;
}
// 行内标签
function inline_Tag($text)
{
    $text = preg_replace_callback('/\[in-tag color=\"(.*?)\"](.*?)\[\/in-tag\]/ism', function ($text) {
        return '<span class="inline-tag '. $text[1] .'">'. $text[2] .'</span>';
    }, $text);
    return $text;
}
// 单选框-radio
function Bf_Radio($text)
{
    $text = preg_replace_callback('/\[radio color=\"(.*?)\".*?\ checked=\"(.*?)"\](.*?)\[\/radio\]/ism', function ($text) {
       return '<div class="checkbox '. $text[1] .' checked"><input type="radio" '. $text[2] .'>'. $text[3] .'</div>';
    }, $text);
    return $text;
}
function Bf_Mark($text)
{
    $text = preg_replace_callback('/\[label color=\"(.*?)\".*?\](.*?)\[\/label\]/ism', function ($text) {
       return '<mark class="hl-label '. $text[1] .'">'. $text[2] .'</mark>';
    }, $text);
    return $text;
}


// 重写文章图片加载
function PostImage($text)
{
    $text = preg_replace_callback('/<img src=\"(.*?)\".*?alt\=\"(.*?)\".*?>/ism', function ($text) {
        return '<a href="'.$text[1].'" data-fancybox="gallery" /><img class="LazyLoad" alt="'.$text[2].'" data-lazy-src="'.$text[1].'" src="'.GetLazyLoad().'" /></a>';
    }, $text);
    return $text;
}
function themeInit($archive) {
    if ($archive->is('single')) {
        $archive->content = createCatalog($archive->content);
        $archive->content = ParseCode($archive->content);
    }
}

/**
 * 判断时间区间
 * 
 * 使用方法  if(timeZone($this->date->timeStamp)) echo 'ok';
 */
function timeZone($from){
$now = new Typecho_Date(Typecho_Date::gmtTime());
return $now->timeStamp - $from < 24*60*60 ? true : false;
}


/**
* 获取标签数目
* 
* 语法: <?php echo tagsNum(); ?>
* 
* @access protected
* @return integer
*/
function tagsNum($display = true)
{
    $db = Typecho_Db::get();
        $total_tags = $db->fetchObject($db->select(array('COUNT(mid)' => 'num'))
              ->from('table.metas')
              ->where('table.metas.type = ?', 'tag'))->num;
        if($display) {
            echo $total_tags;
        } else {
            return $total_tags;
    }
}

//获取Gravatar头像 QQ邮箱取用qq头像
function getGravatar($email, $s = 96, $d = 'mp', $r = 'g', $img = false, $atts = array())
{
preg_match_all('/((\d)*)@qq.com/', $email, $vai);
if (empty($vai['1']['0'])) {
    $url = 'https://gravatar.loli.net/avatar/';
    $url .= md5(strtolower(trim($email)));
    $url .= "?s=$s&d=$d&r=$r";
    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
}else{
    $url = 'https://q1.qlogo.cn/headimg_dl?dst_uin='.$vai['1']['0'].'&spec=100';
}
return  $url;
} 
 
 
// 获取浏览器信息
function getBrowser($agent)
{
    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
        $outputer = '<i class="fab fa-internet-explorer"></i>&nbsp;&nbsp;Internet Explore';
    } else if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
      $str1 = explode('Firefox/', $regs[0]);
$FireFox_vern = explode('.', $str1[1]);
        $outputer = '<i class="fab fa-firefox-browser"></i>&nbsp;&nbsp;FireFox';
    } else if (preg_match('/Maxthon([\d]*)\/([^\s]+)/i', $agent, $regs)) {
      $str1 = explode('Maxthon/', $agent);
$Maxthon_vern = explode('.', $str1[1]);
        $outputer = '<i class="iconfont icon-maxthon"></i>&nbsp遨游';
    } else if (preg_match('#360([a-zA-Z0-9.]+)#i', $agent, $regs)) {
$outputer = '<i class="iconfont icon-chrome"></i>&nbsp;360极速浏览器';
    } else if (preg_match('/Edg([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('Edge/', $regs[0]);
$Edge_vern = explode('.', $str1[1]);
        $outputer = '<i class="fab fa-edge"></i>&nbsp;&nbsp;MicroSoft Edge';
    } else if (preg_match('/UC/i', $agent)) {
              $str1 = explode('rowser/',  $agent);
$UCBrowser_vern = explode('.', $str1[1]);
        $outputer = '<i class="iconfont icon-UCliulanqi"></i>&nbsp;UC浏览器';
    }  else if (preg_match('/QQ/i', $agent, $regs)||preg_match('/QQBrowser\/([^\s]+)/i', $agent, $regs)) {
                  $str1 = explode('rowser/',  $agent);
$QQ_vern = explode('.', $str1[1]);
        $outputer = '<i class="iconfont icon-QQliulanqi"></i>&nbsp;QQ浏览器';
    } else if (preg_match('/UBrowser/i', $agent, $regs)) {
              $str1 = explode('rowser/',  $agent);
$UCBrowser_vern = explode('.', $str1[1]);
        $outputer = '<i class="iconfont icon-UCliulanqi"></i>&nbsp;UC浏览器';
    }  else if (preg_match('/Opera[\s|\/]([^\s]+)|OPR/i', $agent, $regs)) {
        $outputer = '<i class="fab fa-opera"></i>&nbsp;&nbsp;Opera';
    } else if (preg_match('/YaBrowser/i', $agent, $regs)) {
         $str1 = explode('Version/',  $agent);
$yandex_brower = explode('.', $str1[1]);
        $outputer = '<i class="fab fa-yandex-international"></i>&nbsp;&nbsp;Yandex';
    }else if (preg_match('/Quark/i', $agent, $regs)) {
         $str1 = explode('Version/',  $agent);
$quark_brower = explode('.', $str1[1]);
        $outputer = '<i class="iconfont icon-quark"></i>&nbsp;Quark';
    }else if (preg_match('/XiaoMi/i', $agent, $regs)) {
$outputer = '<i class="iconfont icon-XiaoMi"></i>&nbsp;小米浏览器';}
    else if (preg_match('/Chrome([\d]*)\/([^\s]+)/i', $agent, $regs)) {
$str1 = explode('Chrome/', $agent);
$chrome_vern = explode('.', $str1[1]);
        $outputer = '<i class="fab fa-chrome""></i>&nbsp;&nbsp;Google Chrome';
    } else if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
         $str1 = explode('Version/',  $agent);
$safari_vern = explode('.', $str1[1]);
        $outputer = '<i class="fab fa-safari"></i>&nbsp;&nbsp;Safari';
    } 
    else{
        $outputer = '<i class="fab fa-chrome"></i>&nbsp;&nbsp;Google Chrome';
    }
    echo $outputer;
}
// 获取操作系统信息
function getOs($agent)
{
    $os = false;
    if (preg_match('/win/i', $agent)) {
        if (preg_match('/nt 6.0/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="iconfont icon-windows"></i>&nbsp;Windows Vista&nbsp;/&nbsp;';
        } else if (preg_match('/nt 6.1/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="iconfont icon-windows"></i>&nbsp;Windows 7&nbsp;/&nbsp;';
        } else if (preg_match('/nt 6.2/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 8&nbsp;/&nbsp;';
        } else if(preg_match('/nt 6.3/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 8.1&nbsp;/&nbsp;';
        } else if(preg_match('/nt 5.1/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="iconfont icon-windows"></i>&nbsp;Windows XP&nbsp;/&nbsp;';
        } else if (preg_match('/nt 10.0/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 10&nbsp;/&nbsp;';
        }else if (preg_match('/nt 11.0/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 11&nbsp;/&nbsp;';
        }else{
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows X64&nbsp;/&nbsp;';
        }
    } 
    else if (preg_match('/android/i', $agent)) {
    if (preg_match('/android 9/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Pie&nbsp;/&nbsp;';
        }else if (preg_match('/android 4/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android ICS&nbsp;/&nbsp;';
        }
        else if (preg_match('/android 5/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Lollipop&nbsp;/&nbsp;';
        }
        else if (preg_match('/android 6/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android M&nbsp;/&nbsp;';
        }
        else if (preg_match('/android 7/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Nougat&nbsp;/&nbsp;';
        }else if (preg_match('/android 8/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Oreo&nbsp;/&nbsp;';
        }else if (preg_match('/android 10/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Q&nbsp;/&nbsp;';
        }
        else if (preg_match('/android 11/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android 11&nbsp;/&nbsp;';
        }
    else{
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android&nbsp;/&nbsp;';
    }
    }
    else if (preg_match('/ubuntu/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-ubuntu"></i>&nbsp;&nbsp;Ubuntu&nbsp;/&nbsp;';
    }else if (preg_match('/Arch/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="iconfont icon-Arch-Linux"></i>&nbsp;Arch Linux&nbsp;/&nbsp;';
    }else if (preg_match('/manjaro/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="iconfont icon-manjaro"></i>&nbsp;&nbsp;Manjaro&nbsp;/&nbsp;';
    }else if (preg_match('/debian/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="iconfont icon-debianos"></i>&nbsp;Debian&nbsp;/&nbsp;';
    }else if (preg_match('/linux/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-linux"></i>&nbsp;&nbsp;Linux&nbsp;/&nbsp;';
    }else if (preg_match('/iPad/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-apple"></i>&nbsp;&nbsp;iOS(iPad)&nbsp;/&nbsp;';
    }else if (preg_match('/iPhone/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-apple"></i>&nbsp;&nbsp;iOS(iPhone)&nbsp;/&nbsp;';
    }else if (preg_match('/mac/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-apple"></i>&nbsp;&nbsp;MacOS&nbsp;/&nbsp;';
    }else if (preg_match('/fusion/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android&nbsp;/&nbsp;';
    } else {
        $os = '&nbsp;&nbsp;<i class="fab fa-linux"></i>&nbsp;&nbsp;Linux&nbsp;/&nbsp;';
    }
    echo $os;
}

function commentRank($widget, $email = NULL)      
{      
    if (empty($email)) return;      
    $txt = Helper::options()->CustomAuthenticated;
    $string_arr = explode("\r\n", $txt);
    $long = count($string_arr);
    for ($i = 0; $i < $long; $i++) {
        $mailList[] =  explode("||", $string_arr[$i])[0];
        $authList[] =  explode("||", $string_arr[$i])[1];
    }
    $all = array_combine($mailList,$authList);
    
    if ($widget->authorId == $widget->ownerId) {
        echo '<span class="vtag vmaster">博主</span>';      
    } 
    else if (in_array($email, $mailList)) {
        echo '<span class="vtag vauth">'.$all[$email].'</span>';
      
    }
    else{
        echo '<span class="vtag vvisitor">访客</span>';
    }
}

//获取评论的锚点链接
function get_comment_at($coid)
{
    $db   = Typecho_Db::get();
    $prow = $db->fetchRow($db->select('parent,status')->from('table.comments')
        ->where('coid = ?', $coid));//当前评论
    $mail = "";
    $parent = @$prow['parent'];
    if ($parent != "0") {//子评论
        $arow = $db->fetchRow($db->select('author,status,mail')->from('table.comments')
            ->where('coid = ?', $parent));//查询该条评论的父评论的信息
        @$author = @$arow['author'];//作者名称
        $mail = @$arow['mail'];
        if(@$author && $arow['status'] == "approved"){//父评论作者存在且父评论已经审核通过
            if (@$prow['status'] == "waiting"){
                echo '<p class="commentReview">（评论审核中）)</p>';
            }
            echo '<a onclick="b(this);return false;" href="#comment-' . $parent . '">@' . $author . '</a>';
        }else{//父评论作者不存在或者父评论没有审核通过
            if (@$prow['status'] == "waiting"){
                echo '<p class="commentReview">（评论审核中）)</p>';
            }else{
                echo '';
            }
        }

    } else {//母评论，无需输出锚点链接
        if (@$prow['status'] == "waiting"){
            echo '<p class="commentReview">（评论审核中）)</p>';
        }else{
            echo '';
        }
    }
}
/**
 * 重写评论显示函数
 */
function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
?>
<li id="li-<?php $comments->theId(); ?>" class="comment-body<?php 
if ($comments->levels > 0) {
    echo ' comment-child';
    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
} else {
    echo ' comment-parent';
}
$comments->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">
    <div id="<?php $comments->theId(); ?>">
        <div class="comment-author">
            <?php $email=$comments->mail; $imgUrl = getGravatar($email);echo '<img class="vimg" data-lazy-src="'.$imgUrl.'" width="45px" height="45px" style="border-radius: 50%;" src="'.GetLazyLoad().'">'; ?>
            <cite class="vnick"><?php $comments->author(); ?></cite>
            <?php commentRank($comments, $comments->mail); ?>
            
        </div>
        <div class="vhead">
            <b><?php $parentMail = get_comment_at($comments->coid)?><?php echo $parentMail;?></b>
            <a class="vtime" href="<?php $comments->permalink(); ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
            <?php if(Helper::options()->CloseComments == 'off'): ?>
            <span class="comment-reply"><?php $comments->reply(); ?></span>
            <?php endif ?>
        </div>
        <div class="comment-content"><?php $comments->content(); ?></div>
        <span class="comment-ua"><?php getOs($comments->agent); ?><?php getBrowser($comments->agent); ?></span>
    </div>
<?php if ($comments->children) { ?>
    <div class="comment-children">
        <?php $comments->threadedComments($options); ?>
    </div>
<?php } ?>
</li>
<?php } 

// 主页封面
function img_postthemb($thiz,$default_img){
        $content = $thiz->content;
        $ret = preg_match("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $content, $thumbUrl);
        if($ret === 1 && count($thumbUrl) == 2){
                return $thumbUrl[1];
        }else{
                return $default_img="https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg";
         }         
} 
   
//  输出标签  
function printTag($that) { ?>
        <?php if (count($that->tags) > 0): ?>
            <?php foreach( $that->tags as $tags): ?>
            <a href="<?php print($tags['permalink']) ?>" class="post-meta__tags"><span><?php print($tags['name']) ?></span></a>
            <?php endforeach;?>
        <?php else: ?>
            <a class="post-meta__tags"><span>无标签</span></a>
        <?php endif;?>
<?php }


//当前人数
function onlinePeople(){
   $online_log = "usr/themes/butterfly/online.dat"; //保存人数的文件到根目录,
   $timeout = 30;//30秒内没动作者,认为掉线
   $entries = file($online_log);
   $temp = array();
   for ($i=0;$i<count($entries);$i++){
       $entry = explode(",",trim($entries[$i]));
       if(($entry[0] != getenv('REMOTE_ADDR')) && ($entry[1] > time())) {
           array_push($temp,$entry[0].",".$entry[1]."\n"); //取出其他浏览者的信息,并去掉超时者,保存进$temp
           }
   }
   array_push($temp,getenv('REMOTE_ADDR').",".(time() + ($timeout))."\n"); //更新浏览者的时间
   $slzxrs = count($temp); //计算在线人数
   $entries = implode("",$temp);
    //写入文件
    $fp = fopen($online_log,"w");
    flock($fp,LOCK_EX); //flock() 不能在NFS以及其他的一些网络文件系统中正常工作
    fputs($fp,$entries);
    flock($fp,LOCK_UN);
    fclose($fp);
    echo $slzxrs;
}

/*
* 无插件阅读数
*/
function get_post_view($archive)
{
    $db = Typecho_Db::get();
    $cid = $archive->cid;
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `'.$db->getPrefix().'contents` ADD `views` INT(10) DEFAULT 0;');
    }
    $exist = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid))['views'];
    if ($archive->is('single')) {
        $cookie = Typecho_Cookie::get('contents_views');
        $cookie = $cookie ? explode(',', $cookie) : array();
        if (!in_array($cid, $cookie)) {
            $db->query($db->update('table.contents')
                ->rows(array('views' => (int)$exist+1))
                ->where('cid = ?', $cid));
            $exist = (int)$exist+1;
            array_push($cookie, $cid);
            $cookie = implode(',', $cookie);
            Typecho_Cookie::set('contents_views', $cookie);
        }
    }
    echo $exist == 0 ? '0':' '.$exist;
}
//总访问量
function theAllViews(){
    $db = Typecho_Db::get();
        if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `'.$db->getPrefix().'contents` ADD `views` INT(10) DEFAULT 0;');
    }
    $row = $db->fetchAll($db->select('SUM(views)')->from('table.contents'));
    echo $row[0]["SUM(`views`)"];
}
//  回复可见       
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('myyodux','one');
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('myyodux','one');
class myyodux {
    public static function one($con,$obj,$text)
    {
      $text = empty($text)?$con:$text;
      if(!$obj->is('single')){
      $text = preg_replace("/\[hide\](.*?)\[\/hide\]/sm",'',$text);
    //   $text = preg_replace("/\n\s*){3,}/sm",' ',$text);
      }
      return $text;
    }
}
function ParseAvatar($mail, $re = 0, $id = 0){
    $a = Typecho_Widget::widget('Widget_Options')->JGravatars;
    $b = 'https://gravatar.helingqi.com/wavatar/';
    $c = strtolower($mail);
    $d = md5($c);
    $f = str_replace('@qq.com', '', $c);
    if (strstr($c, "qq.com") && is_numeric($f) && strlen($f) < 11 && strlen($f) > 4) {
        $g = '//thirdqq.qlogo.cn/g?b=qq&nk=' . $f . '&s=100';
        if ($id > 0) {
            $g = Helper::options()->rootUrl . '?id=' . $id . '" data-type="qqtx';
        }
    } else {
        $g = $b . $d . '?d=mm';
    }
    if ($re == 1) {
        return $g;
    } else {
        echo $g;
    }
}
 
/**
* 显示上一篇
*
* 如果没有下一篇,返回null
*/
function thePrevCid($widget, $default = NULL) {
  $db = Typecho_Db::get();
  $sql = $db->select()->from('table.contents')
  ->where('table.contents.created < ?', $widget->created)
  ->where('table.contents.status = ?', 'publish')
  ->where('table.contents.type = ?', $widget->type)
  ->where('table.contents.password IS NULL')
  ->order('table.contents.created', Typecho_Db::SORT_DESC)
  ->limit(1);
  $content = $db->fetchRow($sql);

  if ($content) {
    return $content["cid"];
  } else {
    return $default;
  }
};

/**
* 获取下一篇文章mid
*
* 如果没有下一篇,返回null
*/
function theNextCid($widget, $default = NULL) {
  $db = Typecho_Db::get();
  $sql = $db->select()->from('table.contents')
  ->where('table.contents.created > ?', $widget->created)
  ->where('table.contents.status = ?', 'publish')
  ->where('table.contents.type = ?', $widget->type)
  ->where('table.contents.password IS NULL')
  ->order('table.contents.created', Typecho_Db::SORT_ASC)
  ->limit(1);
  $content = $db->fetchRow($sql);

  if ($content) {
    return $content["cid"];
  } else {
    return $default;
  }
};

//调用博主最近文章更新时间
function get_last_update(){
    $num = '1';
    $type = 'post';
    $status = 'publish';
    $now = time();
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    $create = $db->fetchRow($db->select('created')->from('table.contents')->where('table.contents.type=? and status=?',$type,$status)->order('created',Typecho_Db::SORT_DESC)->limit($num));
    $update = $db->fetchRow($db->select('modified')->from('table.contents')->where('table.contents.type=? and status=?',$type,$status)->order('modified',Typecho_Db::SORT_DESC)->limit($num));
    if($create>=$update){
      echo Typecho_I18n::dateWord($create['created'], $now);
    }else{
        $lastday = floor(date($now-$update['modified'])/86400);
        if($lastday>365){$lastyear = floor(date($now-$update['modified'])/30758400);echo $lastyear . " 年前" ;
        }  elseif($lastday > 30){$lastmom = floor(date($now-$update['modified'])/2592000);echo $lastmom ." 个月前" ;
        }elseif($lastday < 1){$lasthour = floor(date($now-$update['modified'])/3600);
            if($lasthour<1){$lastmin = floor(date($now-$update['modified'])/60);
                if($lastmin < 1){$lastsecd = floor(date($now-$update['modified'])); echo $lastsecd ." 秒前" ;}
                else{echo $lastmin ." 分钟前" ;}
            }else{echo $lasthour ." 小时前" ;}
        }else{echo $lastday." 天前";}}
}
//文章阅读时间统计
function art_time ($cid){
    $db=Typecho_Db::get ();
    $rs=$db->fetchRow ($db->select ('table.contents.text')->from ('table.contents')->where ('table.contents.cid=?',$cid)->order ('table.contents.cid',Typecho_Db::SORT_ASC)->limit (1));
    $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $rs['text']);
    $text_word = mb_strlen($text,'utf-8');
    echo ceil($text_word / 400);
}
// 自定义编辑器
Typecho_Plugin::factory('admin/write-post.php')->bottom = array('editor', 'reset');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('editor', 'reset');
class editor
{
  public static function reset()
    {
        echo "<script src='" . Helper::options()->themeUrl . '/edit/extend.js?v1.3.3' . "'></script>";
        echo "<link rel='stylesheet' href='" . Helper::options()->themeUrl . '/edit/edit.css?v1.1.3' . "'>";
    }

}
/* 判断是否是移动端 */
function isMobile()
{
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
    if (isset($_SERVER['HTTP_VIA'])) {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            return true;
    }
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}


// 说明：获取完整URL
function curPageURL() 
{
  $pageURL = 'http';
 
  if ($_SERVER["HTTPS"] == "on") 
  {
    $pageURL .= "s";
  }
  $pageURL .= "://";
 
  if ($_SERVER["SERVER_PORT"] != "80") 
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  } 
  else
  {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}