<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;
function themeConfig($form)
{
    ?>
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('css/themedash.css?v1.5.3'); ?>">
    <div class='set_toc'>
        <div class='mtoc'>
            <a href='#themeBackup'>主题备份与还原</a>
            <a href='#cids'>文章置顶及公共部分</a>
            <a href='#pjax'>pjax设置</a>
            <a href='#friends'>友情链接设置</a>
            <a href='#reward'>打赏功能</a>
            <a href='#aside'>侧边栏显示设置</a>
            <a href='#beautifyBlock'>美化选项</a>
            <a href='#ShowLive2D'>Live2D设置</a>
            <a href='#otherCustom'>其他自定义内容</a>
            <a href='#CustomColor'>自定义颜色</a>
            <a href='#NULL' id='point'>返回上次保存设置时的锚点</a>
        </div>
    </div>
    <form class="protected" action="?butterflybf" method="post" id="themeBackup">
        <input type="submit" name="type" class="btn btn-s" value="备份主题数据" />&nbsp;&nbsp;<input type="submit" name="type"
            class="btn btn-s" value="还原主题数据" />&nbsp;&nbsp;<input type="submit" name="type" class="btn btn-s"
            value="删除备份数据" />
    </form>
    <script src='https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js'></script>
    <script src="<?php Helper::options()->themeUrl('js/themecustom.js?v1.5.3'); ?>"></script>
    <script src='https://cdn.staticaly.com/gh/wehaox/CDN/main/postdomai.js'></script>
    <?php
    $sticky_cids = new Typecho_Widget_Helper_Form_Element_Text('sticky_cids', NULL, NULL, '置顶文章的 cid', '<div style="font-family:arial; background:#E8EFD1; padding:8px">按照排序输入, 请以半角逗号或空格分隔 cid</div>');
    $sticky_cids->setAttribute('id', 'cids');
    $form->addInput($sticky_cids);

    $slide_cids = new Typecho_Widget_Helper_Form_Element_Text('slide_cids', NULL, NULL, '主页轮播图文章的 cid', '填入自动开启，填入方式同上，<b style="color:red">注意：填入错误cid会导致页面出错</b>');
    $form->addInput($slide_cids);

    $StaticFile = new Typecho_Widget_Helper_Form_Element_Select(
        'StaticFile',
        array(
            'CDN' => 'CDN加载(默认)',
            'local' => '本地加载',
        ),
        'CDN',
        '博客静态资源加载方式',
        '介绍：无网络服务器或者CDN炸了可开启此项<br>
         将博客静态资源，如js、css、图片从服务器加载(会稍微增加服务器流量消耗)<br>
         注意：你需要额外<a href="https://github.com/wehaox/Typecho-Butterfly/releases">下载</a>对应版本的静态资源放进主题根目录直接解压即可<br>
         此文件与下方的自定义CDN文件通用'
    );
    $form->addInput($StaticFile->multiMode());

    $CDNURL = new Typecho_Widget_Helper_Form_Element_Text(
        'CDNURL',
        NULL,
        NULL,
        '自定义CDNURL(由@origami-tech提供)',
        '需要选择博客静态资源加载方式为CDN加载 此项才会生效 且<b>本地加载>自定义CDNURL>jsdelivr源</b><br>
    注意：你需要额外<a href="https://github.com/wehaox/Typecho-Butterfly/releases">下载</a>静态资源放CDN解压<br>
    链接填写规则：填写static文件夹的父文件夹 无需最后的/ 例如 https://pub-gcdn.starsdust.cn/libs/butterfly '
    );
    $form->addInput($CDNURL);

    $jsdelivrLink = new Typecho_Widget_Helper_Form_Element_Select(
        'jsdelivrLink',
        array(
            'cdn.jsdelivr.net' => '官方默认源',
            'gcore.jsdelivr.net' => 'gcore源',
            'fastly.jsdelivr.net' => 'fastly源',
            'raw.fastgit.org' => 'fastgit源',
        ),
        'gcore.jsdelivr.net',
        'jsdelivr提供的cdn源切换(默认采用gcore源)',
        '需要开启上方的CDN加载'
    );
    $form->addInput($jsdelivrLink->multiMode());

    $NewTabLink = new Typecho_Widget_Helper_Form_Element_Select(
        'NewTabLink',
        array(
            'on' => '开启（默认）',
            'off' => '关闭',
        ),
        'on',
        '是否开启新标签打开外部链接',
        '介绍：非站内链接在新标签打开'
    );
    $form->addInput($NewTabLink->multiMode());

    $showFramework = new Typecho_Widget_Helper_Form_Element_Select(
        'showFramework',
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
        '介绍： 下方密码留空则显示网站维护否则显示输入密码访问，登录用户不受限制'
    );
    $form->addInput($Defend->multiMode());

    $ThemePassword = new Typecho_Widget_Helper_Form_Element_Text('ThemePassword', NULL, NULL, _t('全站密码访问(非必填)'), _t('输入访问网站的密码，<b>需要在上方开启网站维护或密码访问</b>'));
    $form->addInput($ThemePassword);

    $NoQQ = new Typecho_Widget_Helper_Form_Element_Select(
        'NoQQ',
        array('off' => '关闭（默认）', 'on' => '开启'),
        'off',
        '是否开启网站禁止手机QQ访问',
        '介绍：烦人的QQ'
    );
    $form->addInput($NoQQ->multiMode());

    $SiteLogo = new Typecho_Widget_Helper_Form_Element_Text('SiteLogo', NULL, NULL, _t('站点名称设置为图片logo(非必填)'), _t('当设置此项时站点名称将不会在导航栏左上角显示,使用png格式'));
    $form->addInput($SiteLogo);

    $Sitefavicon = new Typecho_Widget_Helper_Form_Element_Text('Sitefavicon', NULL, NULL, _t('网站图标'), _t('网站图标，使用png格式，大小建议不超过64x64'));
    $form->addInput($Sitefavicon);

    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, _t('#null'), _t('作者头像'), _t('在这里填入图片地址，它会显示在右侧栏的作者头像'));
    $form->addInput($logoUrl);

    $author_description = new Typecho_Widget_Helper_Form_Element_Text('author_description', NULL, _t('作者描述'), _t('作者描述'), _t('在这里填入站点描述，它会显示在右侧栏的作者信息'));
    $form->addInput($author_description);

    $author_site_description = new Typecho_Widget_Helper_Form_Element_Text('author_site_description', NULL, _t('个人网站'), _t('作者链接描述'), _t('作者链接描述'));
    $form->addInput($author_site_description);

    $author_site = new Typecho_Widget_Helper_Form_Element_Text('author_site', NULL, _t('#null'), _t('作者链接'), _t('在这里填入作者链接，它会显示在右侧栏的作者信息的个人网站上'));
    $form->addInput($author_site);

    $author_bottom = new Typecho_Widget_Helper_Form_Element_Textarea('author_bottom', NULL, _t(''), _t('侧栏作者信息最底部内容（非必填）'), _t('这里填入html代码,不会勿填'));
    $form->addInput($author_bottom);

    $announcement = new Typecho_Widget_Helper_Form_Element_Textarea('announcement', NULL, _t('这里是公告<br>'), _t('公告'), _t('在这里填入公告，它会显示在右侧栏的公告上,采用html写法'));
    $form->addInput($announcement);

    $AD = new Typecho_Widget_Helper_Form_Element_Textarea('AD', NULL, NULL, _t('广告(由@yzl3014提供)'), _t('在这里填入广告，填入后自动显示在侧栏中公告栏的下方，支持html'));
    $form->addInput($AD);

    $headerimg = new Typecho_Widget_Helper_Form_Element_Text('headerimg', NULL, _t('https://s2.loli.net/2023/01/18/bIJTVaR3MLPzcZ7.jpg'), _t('主页顶图(banner image)'), _t('填入主页头图链接'));
    $form->addInput($headerimg);

    $buildtime = new Typecho_Widget_Helper_Form_Element_Text('buildtime', NULL, _t('2021/04/05'), _t('建站时间'), _t('按照输入框内格式填写'));
    $form->addInput($buildtime);

    $outoftime = new Typecho_Widget_Helper_Form_Element_Text('outoftime', NULL, _t('15'), _t('文章过时提醒'), _t('设置文章过时提醒最大天数，默认15天，可在文章管理单独设置是否显示过期提醒'));
    $form->addInput($outoftime);

    $archivelink = new Typecho_Widget_Helper_Form_Element_Text('archivelink', NULL, _t('#null'), _t('侧栏文章(归档)链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($archivelink);

    $tagslink = new Typecho_Widget_Helper_Form_Element_Text('tagslink', NULL, _t('#null'), _t('侧栏标签链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($tagslink);

    $categorylink = new Typecho_Widget_Helper_Form_Element_Text('categorylink', NULL, _t('#null'), _t('侧栏分类链接'), _t('需在独立页面创建并手动填入链接'));
    $form->addInput($categorylink);

    $CloseComments = new Typecho_Widget_Helper_Form_Element_Select(
        'CloseComments',
        array(
            'off' => '关闭（默认）',
            "on" => '开启'
        ),
        'off',
        '全站关闭评论',
        '介绍：开启后所有文章不能评论'
    );
    $form->addInput($CloseComments->multiMode());

    $EnableCommentsLogin = new Typecho_Widget_Helper_Form_Element_Select(
        'EnableCommentsLogin',
        array(
            'off' => '关闭（默认）',
            "on" => '开启'
        ),
        'off',
        '开启用户评论区登录',
        '介绍：开启后在评论区会显示登录按钮
        '
    );
    $form->addInput($EnableCommentsLogin->multiMode());

    $ShowRelatedPosts = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowRelatedPosts',
        array(
            'off' => '关闭（默认）',
            'on' => '开启',
        ),
        'off',
        '是否显示文章内相关推荐',
        '介绍：开启后文章结束后会显示相关的推荐文章(根据文章标签推荐，不一定每篇文章都会显示)'
    );
    $form->addInput($ShowRelatedPosts->multiMode());

    $RelatedPostsNum = new Typecho_Widget_Helper_Form_Element_Select(
        'RelatedPostsNum',
        array(
            '3' => '3篇（默认）',
            '6' => '6篇',
        ),
        '3',
        '相关推荐显示数量',
        '介绍：最多显示3篇或者6篇相关推荐文章'
    );
    $form->addInput($RelatedPostsNum->multiMode());

    $DefaultEncoding = new Typecho_Widget_Helper_Form_Element_Select(
        'DefaultEncoding',
        array(
            '2' => '简体（默认）',
            '1' => '繁体',
        ),
        '2',
        '博客默认字体',
        '介绍：如果你使用繁体写文章请选择繁体'
    );
    $form->addInput($DefaultEncoding->multiMode());

    $themeFontSize = new Typecho_Widget_Helper_Form_Element_Text('themeFontSize', NULL, _t(''), _t('默认字体大小'), _t('填入像素值，例如14px'));
    $form->addInput($themeFontSize);

    $GravatarSelect = new Typecho_Widget_Helper_Form_Element_Select(
        'GravatarSelect',
        array(
            "https://gravatar.loli.net/avatar/" => 'loli（默认）',
            'https://gravatar.helingqi.com/wavatar/' => '禾令奇',
            "https://sdn.geekzu.org/avatar/" => '极客族',
            "https://cdn.sep.cc/avatar/" => '九月的风',
            "https://gravatar.com/avatar/" => '官方源(被墙)',
            "https://cravatar.cn/avatar/" => '中国官方源(推荐)',
        ),
        'loli',
        'gravatar源选择',
        '介绍：评论区头像gravatar源'
    );
    $GravatarSelect->setAttribute('id', 'gravatarlist');
    $form->addInput($GravatarSelect->multiMode());

    $baidustatistics = new Typecho_Widget_Helper_Form_Element_Text('baidustatistics', NULL, _t(''), _t('百度统计'), _t('仅需要https://hm.baidu.com/hm.js?xxxxxxxxxxxxxxxxxx部分即可'));
    $form->addInput($baidustatistics);

    $googleadsense = new Typecho_Widget_Helper_Form_Element_Text('googleadsense', NULL, _t(''), _t('谷歌广告(实验性功能)'), _t('填入client后的部分,如ca-pub-xxxxx'));
    $form->addInput($googleadsense);

    $EnablePjax = new Typecho_Widget_Helper_Form_Element_Select(
        'EnablePjax',
        array(
            'off' => '关闭（默认）',
            "on" => '开启'
        ),
        'off',
        '开启PJAX',
        '介绍：页面无刷新加载,有效提高页面加载速度<br>
         请先查看<a href="https://blog.wehaox.com/archives/typecho-butterfly.html#cl-13">使用文档</a>'
    );
    $EnablePjax->setAttribute('id', 'pjax');
    $form->addInput($EnablePjax->multiMode());

    $PjaxCallBack = new Typecho_Widget_Helper_Form_Element_Textarea(
        'PjaxCallBack',
        NULL,
        NULL,
        'PJAX回调函数（非必填）',
        '用于解决开启pjax导致某些js失效问题(填入js代码)'
    );
    $form->addInput($PjaxCallBack);

    /* 友链设置 */
    $friendset = new Typecho_Widget_Helper_Form_Element_Select(
        'friendset',
        array(
            '0' => '主题模式',
            '1' => '插件模式',
        ),
        '0',
        '是否使用Link插件进行友链(需点击<a href="https://github.com/JoyNop/Typecho-Links">这里</a>下载)',
        '介绍：新手和手残党极其友好,默认从主题读取防止报错'
    );
    $friendset->setAttribute('id', 'friendset');
    $form->addInput($friendset);

    $Friends = new Typecho_Widget_Helper_Form_Element_Textarea(
        'Friends',
        NULL,
        NULL,
        '友情链接（非必填）',
        '介绍：用于填写友情链接 <br />
         注意：需在独立页面创建友链，该项才会生效 <br />
         格式：博客名称 || 博客地址 || 博客头像 || 博客简介 <br />
         其他：一行一个，一行代表一个友链'
    );
    $Friends->setAttribute('id', 'friends');
    $form->addInput($Friends);

    $LazyLoad = new Typecho_Widget_Helper_Form_Element_Text(
        'LazyLoad',
        NULL,
        NULL,
        '全局懒加载图（非必填）',
        '介绍：用于修改懒加载图片 格式：base64 或者 图片url'
    );
    $form->addInput($LazyLoad);


    $ShowGlobalReward = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowGlobalReward',
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
    $RewardInfo = new Typecho_Widget_Helper_Form_Element_Textarea(
        'RewardInfo',
        NULL,
        _t('微信 || https://cdn.jsdelivr.net/gh/wehaox/CDN@main/reward/wechat.jpg
支付宝 || https://cdn.jsdelivr.net/gh/wehaox/CDN@main/reward/alipay.jpg'),
        '打赏信息（非必填）',
        '注意：需在开启打赏功能，该项才会显示 <br />
         格式：打赏名称 || 图片地址 <br />一行一个'
    );
    $form->addInput($RewardInfo);

    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'sidebarBlock',
        array(
            'ShowAuthorInfo' => _t('显示作者信息'),
            'ShowAnnounce' => _t('显示公告'),
            'ShowRecentPosts' => _t('显示最新文章'),
            'ShowRecentComments' => _t('显示最近回复'),
            'ShowCategory' => _t('显示分类'),
            'ShowTag' => _t('显示标签'),
            'ShowArchive' => _t('显示归档'),
            'ShowWebinfo' => _t('显示网站资讯'),
            'ShowOther' => _t('显示其它杂项'),
            'ShowMobileSide' => _t('手机端显示侧栏'),
            'ShowWeiboHot' => _t('显示微博热搜')
        ),
        array('ShowAuthorInfo', 'ShowAnnounce', 'ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowTag', 'ShowArchive', 'ShowWebinfo', 'ShowMobileSide'),
        _t('侧边栏显示')
    );
    $sidebarBlock->setAttribute('id', 'aside');
    $form->addInput($sidebarBlock->multiMode());
    // 在线人数显示
    $ShowOnlinePeople = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowOnlinePeople',
        array(
            'on' => '开启',
            'off' => '关闭（默认）',
        ),
        'off',
        '是否显示在线人数',
        '介绍：侧栏网站咨询模块在线人数统计,防止某些虚拟主机无法开启导致500错误'
    );
    $form->addInput($ShowOnlinePeople->multiMode());

    $sidderArchiveNum = new Typecho_Widget_Helper_Form_Element_Text('sidderArchiveNum', NULL, _t('5'), _t('侧栏归档显示行数'), _t('默认为5'));
    $form->addInput($sidderArchiveNum);

    // 文章侧边栏设置
    $PostSidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'PostSidebarBlock',
        array(
            'ShowAuthorInfo' => _t('显示作者信息'),
            'ShowAnnounce' => _t('显示公告'),
            'ShowRecentPosts' => _t('显示最新文章'),
            'ShowWebinfo' => _t('显示网站咨询'),
            'ShowOther' => _t('显示其它杂项'),
            'ShowWeiboHot' => _t('显示微博热搜')
        ),
        array('ShowAuthorInfo', 'ShowAnnounce', 'ShowRecentPosts', 'ShowWebinfo'),
        _t('文章侧边栏显示'),
        _t('说明:单独设置文章内侧栏')
    );
    $form->addInput($PostSidebarBlock->multiMode());

    // 美化选项
    $beautifyBlock = new Typecho_Widget_Helper_Form_Element_Checkbox(
        'beautifyBlock',
        array(
            'ShowBeautifyChange' => _t('是否开启美化(基于butterfly小康的魔改)'),
            'ShowColorTags' => _t('是否开启彩色标签云'),
            'ShowTopimg' => _t('是否显示主页顶图'),
            'PostShowTopimg' => _t('是否显示文章示顶图'),
            'PageShowTopimg' => _t('是否显示独立页面顶图'),
            'showLineNumber' => _t('是否显示代码块行号'),
            'showSnackbar' => _t('是否显示主题以及简繁切换弹窗'),
            'showLazyloadBlur' => _t('是否开启懒加载模糊效果'),
            'showButterflyClock' => _t('是否开启侧栏显示时钟(需要在下方填写和风和高德key)'),
            'showNoAlertSearch' => _t('是否开启无弹窗搜索框'),
        ),
        array('ShowTopimg', 'PostShowTopimg', 'PageShowTopimg', 'showLineNumber', 'showSnackbar', 'showLazyloadBlur', 'showNoAlertSearch'),
        _t('美化选项')
    );
    $beautifyBlock->setAttribute('id', 'beautifyBlock');
    $form->addInput($beautifyBlock->multiMode());

    // 封面位置
    $coverPosition = new Typecho_Widget_Helper_Form_Element_Select(
        'coverPosition',
        array(
            'left' => '靠左',
            'cross' => '交叉(默认)',
            'right' => '靠右',
        ),
        'cross',
        '主页文章列表封面显示位置',
        '个人还是还是觉得交叉好看'
    );
    $form->addInput($coverPosition->multiMode());

    $qweather_key = new Typecho_Widget_Helper_Form_Element_Text('qweather_key', NULL, null, _t('时钟和风天气key'), _t('<a href="https://github.com/anzhiyu-c/hexo-butterfly-clock-anzhiyu/#安装">按照教程获取key</a>'));
    $gaud_map_key = new Typecho_Widget_Helper_Form_Element_Text('gaud_map_key', NULL, null, _t('时钟高得地图web服务key'), _t('侧栏显示时钟用到的key，同上'));
    $form->addInput($qweather_key);
    $form->addInput($gaud_map_key);

    $ShowLive2D = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowLive2D',
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
    $SnackbarPosition = new Typecho_Widget_Helper_Form_Element_Select(
        'SnackbarPosition',
        array(
            'top-left' => '左上(默认)',
            'top-center' => '中上',
            'top-right' => '右上',
            'bottom-left' => '左下',
            'bottom-center' => '中下',
            'bottom-right' => '右下',
        ),
        'top-left',
        '主题以及简繁切换弹窗位置',
        '选择其中一个,需要开启是否显示主题以及简繁切换弹窗 '
    );
    $form->addInput($SnackbarPosition->multiMode());

    $CursorEffects = new Typecho_Widget_Helper_Form_Element_Select(
        'CursorEffects',
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
    $form->addInput($CustomSubtitle);

    $SubtitleLoop = new Typecho_Widget_Helper_Form_Element_Select(
        'SubtitleLoop',
        array(
            'true' => '开启循环打字（默认）',
            "false" => '关闭循环打字'
        ),
        'true',
        '副标题循环打字',
        '介绍：开启后主页副标题循环打字'
    );
    $form->addInput($SubtitleLoop->multiMode());


    $EnableAutoHeaderLink = new Typecho_Widget_Helper_Form_Element_Select(
        'EnableAutoHeaderLink',
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
        '介绍：目前使用html写法 <b style="color:red">完全自定义链接记得关闭上方选项</b>'
    );
    $CustomHeaderLink->setAttribute('id', 'otherCustom');
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

    //自定义颜色    
    $EnableCustomColor = new Typecho_Widget_Helper_Form_Element_Select(
        'EnableCustomColor',
        array(
            "false" => '关闭（默认）',
            'true' => '开启'
        ),
        'false',
        '开启主题自定义颜色(实验性功能)',
        '介绍：需要开启此选项下面的自定义颜色才能生效，且下面关于颜色的必填'
    );
    $EnableCustomColor->setAttribute('id', 'CustomColor');
    $form->addInput($EnableCustomColor->multiMode());

    $CustomColorMain = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorMain',
        NULL,
        _t('#49b1f5'),
        '自定主题主要颜色',
        '介绍：使用hex格式或者颜色英文，如#fff、white'
    );
    $form->addInput($CustomColorMain);

    $CustomColorButtonBG = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorButtonBG',
        NULL,
        _t('#49b1f5'),
        '自定按钮颜色',
        '介绍：同上'
    );
    $form->addInput($CustomColorButtonBG);

    $CustomColorButtonHover = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorButtonHover',
        NULL,
        _t('#ff7242'),
        '自定按钮悬停色',
        '介绍：同上'
    );
    $form->addInput($CustomColorButtonHover);

    $CustomColorSelection = new Typecho_Widget_Helper_Form_Element_Text(
        'CustomColorSelection',
        NULL,
        _t('#00c4b6'),
        '自定文本选择色',
        '介绍：同上'
    );
    $form->addInput($CustomColorSelection);
    //自定义颜色end

    $siteKey = new Typecho_Widget_Helper_Form_Element_Text(
        'siteKey',
        NULL,
        null,
        '评论区谷歌验证码 <br> Site Key for reCAPTCHAv2:',
        '<a href="https://www.google.com/recaptcha/admin/create">点击获取密钥</a>'
    );

    $secretKey = new Typecho_Widget_Helper_Form_Element_Text('secretKey', NULL, null, _t('Serect Key for reCAPTCHAv2:'), _t('填写两处密钥评论区自动开启谷歌验证码'));
    $form->addInput($siteKey);
    $form->addInput($secretKey);


    $hcaptchaSecretKey = new Typecho_Widget_Helper_Form_Element_Text(
        'hcaptchaSecretKey',
        NULL,
        null,
        '<hr> 评论区hcaptch人机验证 <br> 密钥(sietkey)- 使用它作为 secret 来检查用户令牌:',
        '<a href="https://dashboard.hcaptcha.com/welcome">点击获取密钥</a>'
    );

    $hcaptchaAPIKey = new Typecho_Widget_Helper_Form_Element_Text('hcaptchaAPIKey', NULL, null, _t('API 密钥:'), _t('填写两处密钥评论区自动开启hcaptch人机验证'));

    $form->addInput($hcaptchaSecretKey);
    $form->addInput($hcaptchaAPIKey);




    $db = Typecho_Db::get();
    $sjdq = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterfly'));
    $ysj = $sjdq['value'];
    if (isset($_POST['type'])) {
        if ($_POST["type"] == "备份主题数据") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'))) {
                $update = $db->update('table.options')->rows(array('value' => $ysj))->where('name = ?', 'theme:butterflybf');
                $updateRows = $db->query($update);
                echo '<div class="tongzhi">备份已更新，请等待自动刷新！如果等不到请点击';
                ?>
                <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
                <script
                    language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);</script>
                <?php
            } else {
                if ($ysj) {
                    $insert = $db->insert('table.options')->rows(array('name' => 'theme:butterflybf', 'user' => '0', 'value' => $ysj));
                    $insertId = $db->query($insert);
                    echo '<div class="tongzhi">备份完成，请等待自动刷新！如果等不到请点击';
                    ?>
                    <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
                    <script
                        language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);</script>
                    <?php
                }
            }
        }
        if ($_POST["type"] == "还原主题数据") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'))) {
                $sjdub = $db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'));
                $bsj = $sjdub['value'];
                $update = $db->update('table.options')->rows(array('value' => $bsj))->where('name = ?', 'theme:butterfly');
                $updateRows = $db->query($update);
                echo '<div class="tongzhi">检测到主题备份数据，恢复完成，请等待自动刷新！如果等不到请点击';
                ?>
                <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
                <script
                    language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2000);</script>
                <?php
            } else {
                echo '<div class="tongzhi">没有主题备份数据，恢复不了哦！</div>';
            }
        }
        if ($_POST["type"] == "删除备份数据") {
            if ($db->fetchRow($db->select()->from('table.options')->where('name = ?', 'theme:butterflybf'))) {
                $delete = $db->delete('table.options')->where('name = ?', 'theme:butterflybf');
                $deletedRows = $db->query($delete);
                echo '<div class="tongzhi">删除成功，请等待自动刷新，如果等不到请点击';
                ?>
                <a href="<?php Helper::options()->adminUrl('options-theme.php'); ?>">这里</a></div>
                <script
                    language="JavaScript">window.setTimeout("location=\'<?php Helper::options()->adminUrl('options-theme.php'); ?>\'", 2500);</script>
                <?php
            } else {
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

    $summaryContent = new Typecho_Widget_Helper_Form_Element_Textarea(
        'summaryContent',
        NULL,
        NULL,
        '自定义文章摘要',
        '不喜欢自动生成的摘要？那就来自定义吧！'
    );
    $layout->addItem($summaryContent);

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

    $ShowReward = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowReward',
        array(
            'off' => '关闭（默认）',
            'show' => '开启打赏',
        ),
        'off',
        '是否开启文章打赏',
        '介绍：开启此功能需要在主题设置中添加二维码图片'
    );
    $layout->addItem($ShowReward);
    $ShowToc = new Typecho_Widget_Helper_Form_Element_Select(
        'ShowToc',
        array(
            'show' => '开启（默认）',
            'off' => '关闭',
        ),
        'show',
        '是否显示文章目录',
        '介绍：或许有的文章不需要目录功能,默认是开启的,一般不需要设置'
    );
    $layout->addItem($ShowToc);

    $CopyRight = new Typecho_Widget_Helper_Form_Element_Select(
        'CopyRight',
        array(
            'on' => ' CC BY-NC-SA 4.0（默认）',
            'off' => '禁止转载',
        ),
        'on',
        '文章版权说明',
        '介绍：默认为CC BY-NC-SA 4.0'
    );
    $layout->addItem($CopyRight);

    $NoCover = new Typecho_Widget_Helper_Form_Element_Select(
        'NoCover',
        array(
            'on' => '显示封面',
            'off' => '不显示封面',
        ),
        'on',
        '主页是否显示封面',
        '介绍：这篇文章看来不需要封面'
    );
    $layout->addItem($NoCover);
}
// 新文章缩略图
function get_ArticleThumbnail($widget)
{
    // 当文章无图片时的随机缩略图
//   $rand = mt_rand(1, 26); // 随机 1-9 张缩略图
//   // 缩略图加速
//   $rand_url;
//   if(!empty(Helper::options()->articleImgSpeed)){
//     $rand_url = Helper::options()->articleImgSpeed;
//   }else {
//     $rand_url = $widget->widget('Widget_Options')->themeUrl . '/images/articles/';
//   }
//   $random =  $rand_url . $rand . '.jpg'; // 随机缩略图路径
//   $random =  'https://static01.imgkr.com/temp/517e5d14c312427dbf93304563869279.png';
//   $attach = $widget->attachments(1)->attachment;
    $random = '/usr/themes/butterfly/img/DefualtThumbnail.jpg';
    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';

    //如果有自定义缩略图
    if ($widget->fields->thumb) {
        return $widget->fields->thumb;
    } else if (preg_match_all($pattern, $widget->content, $thumbUrl) && strlen($thumbUrl[1][0]) > 7) {
        return $thumbUrl[1][0];
    } else {
        return $random;
    }
}
;




// 主页文章缩略图
function GetRandomThumbnail($widget)
{
    // $random = 'https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg';
    $random = '/usr/themes/butterfly/img/DefualtThumbnail.jpg';
    if (Helper::options()->futureRandom) {
        $moszu = explode("\r\n", Helper::options()->futureRandom);
        $random = $moszu[array_rand($moszu, 1)] . "?futureRandom=" . mt_rand(0, 1000000);
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
    $img = '';
    if ($widget->fields->thumb) {
        $img = $widget->fields->thumb;
    }
    echo $img;
}

// 文章字数统计
function art_count($cid)
{
    $db = Typecho_Db::get();
    $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid=?', $cid)->order('table.contents.cid', Typecho_Db::SORT_ASC)->limit(1));
    echo mb_strlen($rs['text'], 'UTF-8');
}
// 文章字数统计2
function charactersNum($archive)
{
    return mb_strlen($archive->text, 'UTF-8');
}

// 全站字数统计
function allOfCharacters()
{
    $showPrivate = 0;
    $chars = 0;
    $db = Typecho_Db::get();
    if ($showPrivate == 0) {
        $select = $db->select('text')->from('table.contents')->where('table.contents.status = ?', 'publish');
    } else {
        $select = $db->select('text')->from('table.contents');
    }
    $rows = $db->fetchAll($select);
    foreach ($rows as $row) {
        $chars += mb_strlen($row['text'], 'UTF-8');
    }
    $unit = '';
    if ($chars >= 10000) {
        $chars /= 10000;
        $unit = 'W';
    } else if ($chars >= 1000) {
        $chars /= 1000;
        $unit = 'K';
    }
    $out = sprintf('%.2lf %s', $chars, $unit);
    echo $out;
}

function thumb($cid)
{
    if (empty($imgurl)) {
        $rand_num = 10; //随机图片数量，根据图片目录中图片实际数量设置
        if ($rand_num == 0) {
            $imgurl = "img/0.jpg";
            //如果$rand_num = 0,则显示默认图片，须命名为"0.jpg"，注意是绝对地址
        } else {
            $imgurl = "img/" . rand(1, $rand_num) . ".jpg";
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
    if (empty($img)) {
        echo $imgurl;
    } else {
        echo '你的博客地址' . $img['path'];
    }
}


// 评论时间
function timesince($older_date, $comment_date = false)
{
    $chunks = array(
        array(86400, '天'),
        array(3600, '小时'),
        array(60, '分'),
        array(1, '秒'),
    );
    $newer_date = time();
    $since = abs($newer_date - $older_date);
    if ($since < 2592000) {
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0)
                break;
        }
        $output = $count . $name . '前';
    } else {
        $output = !$comment_date ? (date('Y-m-j G:i', $older_date)) : (date('Y-m-j', $older_date));
    }
    return $output;
}



// 文章内获取第一张图做封面
function getPostImg($archive)
{
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

function createCatalog($obj)
{ //为文章标题添加锚点
    global $catalog;
    global $catalog_count;
    $catalog = array();
    $catalog_count = 0;
    $obj = preg_replace_callback('/<h([1-6])(.*?)>(.*?)<\/h\1>/i', function ($obj) {
        global $catalog;
        global $catalog_count;
        $catalog_count++;
        $catalog[] = array('text' => trim(strip_tags($obj[3])), 'depth' => $obj[1], 'count' => $catalog_count);
        return '<h' . $obj[1] . $obj[2] . ' id="cl-' . $catalog_count . '"><a class="markdownIt-Anchor" href="#cl-' . $catalog_count . '"></a>' . $obj[3] . '</h' . $obj[1] . '>';
    }, $obj);
    return $obj;
}


// 目录树
function getCatalog()
{ //输出文章目录容器
    global $catalog;
    $index = '';
    if ($catalog) {
        $prev_depth = '';
        $to_depth = 0;
        foreach ($catalog as $catalog_item) {
            $catalog_depth = $catalog_item['depth'];
            if ($prev_depth) {
                if ($catalog_depth == $prev_depth) {
                    $index .= '</li >' . "\n";
                } elseif ($catalog_depth > $prev_depth) {
                    $to_depth++;
                    $index .= '<ol class="toc-child">' . "\n";
                } else {
                    $to_depth2 = ($to_depth > ($prev_depth - $catalog_depth)) ? ($prev_depth - $catalog_depth) : $to_depth;
                    if ($to_depth2) {
                        for ($i = 0; $i < $to_depth2; $i++) {
                            $index .= '</li>' . "\n" . '</ol>' . "\n";
                            $to_depth--;
                        }
                    }
                    $index .= '</li>';
                }
            }
            $index .= '<li class="toc-item">
            <a class="toc-link" href="#cl-' . $catalog_item['count'] . '">
            <span class="toc-number"></span>
            <span class="toc-text">' . $catalog_item['text'] . '</span>
            </a>';
            $prev_depth = $catalog_item['depth'];
        }
        for ($i = 0; $i <= $to_depth; $i++) {
            $index .= '</li>' . "\n";
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
    $text = Font($text);
    $text = ArtPlayer($text);
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
        return '<details class="toggle"><summary class="toggle-button">' . $text[1] . '</summary><div class="toggle-content">' . $text[2] . '</div></details>';
    }, $text);
    return $text;
}
// 复选框
function Cheak_Box($text)
{
    $text = preg_replace_callback('/\[cb type=\"(.*?)\".*?\ checked=\"(.*?)"\](.*?)\[\/cb\]/ism', function ($text) {
        return '<div class="checkbox ' . $text[1] . ' checked"><input type="checkbox" ' . $text[2] . '>' . $text[3] . '</div>';
    }, $text);
    return $text;
}
// 行内标签
function inline_Tag($text)
{
    $text = preg_replace_callback('/\[in-tag color=\"(.*?)\"](.*?)\[\/in-tag\]/ism', function ($text) {
        return '<span class="inline-tag ' . $text[1] . '">' . $text[2] . '</span>';
    }, $text);
    return $text;
}
// 单选框-radio
function Bf_Radio($text)
{
    $text = preg_replace_callback('/\[radio color=\"(.*?)\".*?\ checked=\"(.*?)"\](.*?)\[\/radio\]/ism', function ($text) {
        return '<div class="checkbox ' . $text[1] . ' checked"><input type="radio" ' . $text[2] . '>' . $text[3] . '</div>';
    }, $text);
    return $text;
}

function Bf_Mark($text)
{
    $text = preg_replace_callback('/\[label color=\"(.*?)\".*?\](.*?)\[\/label\]/ism', function ($text) {
        return '<mark class="hl-label ' . $text[1] . '">' . $text[2] . '</mark>';
    }, $text);
    return $text;
}

function Font($text)
{
    $text = preg_replace_callback('/\[font size=\"(.*?)"\ color=\"(.*?)"\](.*?)\[\/font\]/ism', function ($text) {
        return '<font style="font-size: ' . $text[1] . 'px;color:' . $text[2] . '">' . $text[3] . '</font>';
    }, $text);
    return $text;
}

function ArtPlayer($text)
{
    $text = preg_replace_callback('/\[video title=\"(.*?)"\ url=\"(.*?)"\ container=\"(.*?)"\ subtitle=\"(.*?)"\ poster=\"(.*?)"\](.*?)\[\/video\]/ism', function ($text) {
        $t = explode("<br>", $text[6]);
        for ($i = 0; $i < count($t); $i++) {
            $a[] = explode("|", $t[$i]);
        }
        for ($i = 0; $i < count($a); $i++) {
            $cut[$i]['time'] = isset($a[$i][0]) ? (int) $a[$i][0] : 0;
            $cut[$i]['text'] = isset($a[$i][1]) ? $a[$i][1] : '';
            unset($cut[$i][0]);
            unset($cut[$i][1]);
        }
        $cut[0]['time'] == null ? $highlight = '[]' : $highlight = json_encode($cut);
        $text[4] == ' ' ? $tooltip = '无字幕' : $tooltip = '默认字幕';
        return '
    <div class="iframe_video artplayer artplayer-' . $text[3] . '"></div>
    <script>
        var ' . $text[3] . ' = new Artplayer({
            container: ".artplayer-' . $text[3] . '",
            url: "' . $text[2] . '",
            title: "' . $text[1] . '",
            poster: "' . $text[5] . '",
            subtitle: {
                url: "' . $text[4] . '",
            },            
            volume: 0.5,
            muted: false,
            autoplay: false,
            pip: true,
            autoSize: true,
            autoMini: false,
            screenshot: true,
            setting: true,
            loop: true,
            flip: true,
            playbackRate: true,
            aspectRatio: true,
            fullscreen: true,
            fullscreenWeb: true,
            subtitleOffset: true,
            miniProgressBar: true,
            mutex: true,
            backdrop: true,
            playsInline: true,
            autoPlayback: true,
            theme: "#23ade5",
            lang: navigator.language.toLowerCase(),
            whitelist: ["*"],
            moreVideoAttr: {
                crossOrigin: "anonymous",
            },
            settings: [{
                width: 200,
                html: "字幕",
                tooltip: "' . $tooltip . '",
                selector: [{
                    html: "Display",
                    tooltip: "显示",
                    switch: true,
                    onSwitch: function (item) {
                        item.tooltip = item.switch ? "关闭" : "显示";
                        ' . $text[3] . '.subtitle.show = !item.switch;
                        return !item.switch;
                    },
                }],
                onSelect: function(item) {
                    art.subtitle.switch(item.url, {
                        name: item.html,
                    });
                    return item.html;
                },
            }, ],
            highlight: ' . $highlight . '
        });
    </script>';
    }, $text);
    return $text;
}

// 重写文章图片加载
function PostImage($text)
{
    $doc = new DOMDocument('1.0', 'UTF-8');
    @$doc->loadHTML('<?xml encoding="UTF-8">' . $text);
    $images = $doc->getElementsByTagName('img');
    foreach ($images as $image) {
        $src = $image->getAttribute('src');
        $alt = $image->getAttribute('alt');
        $newImage = $doc->createElement('img');
        $newImage->setAttribute('title', $alt);
        $newImage->setAttribute('alt', $alt);
        $newImage->setAttribute('data-lazy-src', $src);
        $newImage->setAttribute('src', GetLazyLoad());
        $image->parentNode->replaceChild($newImage, $image);
    }
    return $doc->saveHTML();
}



function themeInit($archive)
{
    if (Helper::options()->EnablePjax == "on") {
        Helper::options()->commentsAntiSpam = false;
    }
    if ($archive->is('single')) {
        $archive->content = createCatalog($archive->content);
        $archive->content = ParseCode($archive->content);
    }
    $loginStatus = $archive->widget('Widget_User')->hasLogin();
    if (Helper::options()->siteKey !== "" && Helper::options()->secretKey !== "" && !$loginStatus) {
        comments_filter($archive);
    }
    if (Helper::options()->hcaptchaSecretKey !== "" && Helper::options()->hcaptchaAPIKey !== "" && !$loginStatus) {
        hcaptcha_filter($archive);
    }
    if ($archive->is('index')) {
        // echo '<script src="'..'"></script>';        
    }

}

/**
 * 判断时间区间
 * 
 * 使用方法  if(timeZone($this->date->timeStamp)) echo 'ok';
 */
function timeZone($from)
{
    $now = new Typecho_Date(Typecho_Date::gmtTime());
    return $now->timeStamp - $from < 24 * 60 * 60 ? true : false;
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
    if ($display) {
        echo $total_tags;
    } else {
        return $total_tags;
    }
}


//获取Gravatar头像 QQ邮箱取用qq头像
function getGravatar($email, $name, $comments_a, $s = 96, $d = 'mp', $r = 'g')
{
    preg_match_all('/((\d)*)@qq.com/', $email, $vai);
    if (empty($vai['1']['0'])) {
        // $hasGravatar = hasGravatar($email);
        // if($hasGravatar){
        // $url = 'https://gravatar.loli.net/avatar/';
        $url = Helper::options()->GravatarSelect;
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" >';
        // }else{
        //     $imga = '<img avatar="'.$name.'" color '.$comments_a.'>';
        //     }
    } else {
        $qquser = $vai['1']['0'];
        $db = Typecho_Db::get();
        if (!array_key_exists('qqk', $db->fetchRow($db->select()->from('table.comments')))) {
            $db->query('ALTER TABLE `' . $db->getPrefix() . 'comments` ADD `qqk` varchar(64) DEFAULT NULL;');
        }
        $dbk = $db->fetchRow($db->select('qqk')->from('table.comments')->where('mail=?', $email))['qqk'];
        if ($dbk == NULL) {
            $geturl = 'https://ptlogin2.qq.com/getface?&imgtype=1&uin=' . $qquser;
            $qqurl = file_get_contents($geturl);
            $str1 = explode('sdk&k=', $qqurl);
            if (isset($str1[1])) {
                $str2 = explode('&t=', $str1[1]);
                $k = $str2[0];
                $db->query($db->update('table.comments')->rows(array('qqk' => $k))->where('mail=?', $email));
            }
            $url = 'https://q1.qlogo.cn/headimg_dl?dst_uin=' . $qquser . '&spec=100';
        } else {
            $url = 'https://q1.qlogo.cn/g?b=qq&k=' . $dbk . '&s=100';
        }
        $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" >';
    }
    return $imga;
}

// 获取浏览器信息
function getBrowser($agent)
{
    if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
        $outputer = '<i class="fab fa-internet-explorer"></i>&nbsp;&nbsp;Internet Explore';
    } else if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('Firefox/', $regs[0]);
        $outputer = '<i class="fab fa-firefox-browser"></i>&nbsp;&nbsp;FireFox';
    } else if (preg_match('/Maxthon([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('Maxthon/', $agent);
        $outputer = '<i class="iconfont icon-maxthon"></i>&nbsp遨游';
    } else if (preg_match('#360([a-zA-Z0-9.]+)#i', $agent, $regs)) {
        $outputer = '<i class="iconfont icon-chrome"></i>&nbsp;360极速浏览器';
    } else if (preg_match('/Edg([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('Edge/', $regs[0]);
        $outputer = '<i class="fab fa-edge"></i>&nbsp;&nbsp;MicroSoft Edge';
    } else if (preg_match('/UC/i', $agent)) {
        $str1 = explode('rowser/', $agent);
        $outputer = '<i class="iconfont icon-UCliulanqi"></i>&nbsp;UC浏览器';
    } else if (preg_match('/QQ/i', $agent, $regs) || preg_match('/QQBrowser\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('rowser/', $agent);
        $outputer = '<i class="iconfont icon-QQliulanqi"></i>&nbsp;QQ浏览器';
    } else if (preg_match('/UBrowser/i', $agent, $regs)) {
        $str1 = explode('rowser/', $agent);
        $outputer = '<i class="iconfont icon-UCliulanqi"></i>&nbsp;UC浏览器';
    } else if (preg_match('/Opera[\s|\/]([^\s]+)|OPR/i', $agent, $regs)) {
        $outputer = '<i class="fab fa-opera"></i>&nbsp;&nbsp;Opera';
    } else if (preg_match('/YaBrowser/i', $agent, $regs)) {
        $str1 = explode('Version/', $agent);
        $outputer = '<i class="fab fa-yandex-international"></i>&nbsp;&nbsp;Yandex';
    } else if (preg_match('/Quark/i', $agent, $regs)) {
        $str1 = explode('Version/', $agent);
        $outputer = '<i class="iconfont icon-quark"></i>&nbsp;Quark';
    } else if (preg_match('/XiaoMi/i', $agent, $regs)) {
        $outputer = '<i class="iconfont icon-XiaoMi"></i>&nbsp;小米浏览器';
    } else if (preg_match('/Chrome([\d]*)\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('Chrome/', $agent);
        $outputer = '<i class="fab fa-chrome""></i>&nbsp;&nbsp;Google Chrome';
    } else if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
        $str1 = explode('Version/', $agent);
        $outputer = '<i class="fab fa-safari"></i>&nbsp;&nbsp;Safari';
    } else {
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
        } else if (preg_match('/nt 6.3/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 8.1&nbsp;/&nbsp;';
        } else if (preg_match('/nt 5.1/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="iconfont icon-windows"></i>&nbsp;Windows XP&nbsp;/&nbsp;';
        } else if (preg_match('/nt 10.0/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 10&nbsp;/&nbsp;';
        } else if (preg_match('/nt 11.0/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows 11&nbsp;/&nbsp;';
        } else {
            $os = '&nbsp;&nbsp;<i class="fab fa-windows"></i>&nbsp;&nbsp;Windows X64&nbsp;/&nbsp;';
        }
    } else if (preg_match('/android/i', $agent)) {
        if (preg_match('/android 9/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Pie&nbsp;/&nbsp;';
        } else if (preg_match('/android 4/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android ICS&nbsp;/&nbsp;';
        } else if (preg_match('/android 5/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Lollipop&nbsp;/&nbsp;';
        } else if (preg_match('/android 6/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android M&nbsp;/&nbsp;';
        } else if (preg_match('/android 7/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Nougat&nbsp;/&nbsp;';
        } else if (preg_match('/android 8/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Oreo&nbsp;/&nbsp;';
        } else if (preg_match('/android 10/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android Q&nbsp;/&nbsp;';
        } else if (preg_match('/android 11/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android 11&nbsp;/&nbsp;';
        } else if (preg_match('/android 12/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android 12&nbsp;/&nbsp;';
        } else if (preg_match('/android 13/i', $agent)) {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android 13&nbsp;/&nbsp;';
        } else {
            $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android&nbsp;/&nbsp;';
        }
    } else if (preg_match('/ubuntu/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-ubuntu"></i>&nbsp;&nbsp;Ubuntu&nbsp;/&nbsp;';
    } else if (preg_match('/Arch/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="iconfont icon-Arch-Linux"></i>&nbsp;Arch Linux&nbsp;/&nbsp;';
    } else if (preg_match('/manjaro/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="iconfont icon-manjaro"></i>&nbsp;&nbsp;Manjaro&nbsp;/&nbsp;';
    } else if (preg_match('/debian/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="iconfont icon-debianos"></i>&nbsp;Debian&nbsp;/&nbsp;';
    } else if (preg_match('/linux/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-linux"></i>&nbsp;&nbsp;Linux&nbsp;/&nbsp;';
    } else if (preg_match('/iPad/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-apple"></i>&nbsp;&nbsp;iOS(iPad)&nbsp;/&nbsp;';
    } else if (preg_match('/iPhone/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-apple"></i>&nbsp;&nbsp;iOS(iPhone)&nbsp;/&nbsp;';
    } else if (preg_match('/mac/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-apple"></i>&nbsp;&nbsp;MacOS&nbsp;/&nbsp;';
    } else if (preg_match('/fusion/i', $agent)) {
        $os = '&nbsp;&nbsp;<i class="fab fa-android"></i>&nbsp;&nbsp;Android&nbsp;/&nbsp;';
    } else {
        $os = '&nbsp;&nbsp;<i class="fab fa-linux"></i>&nbsp;&nbsp;Linux&nbsp;/&nbsp;';
    }
    echo $os;
}

function commentRank($widget, $email = NULL)
{
    if (empty($email))
        return;
    $txt = Helper::options()->CustomAuthenticated;
    if ($txt == "") {
        $txt = 'x||x';
    }
    $string_arr = explode("\r\n", $txt);
    $long = count($string_arr);
    for ($i = 0; $i < $long; $i++) {
        $mailList[] = explode("||", $string_arr[$i])[0];
        $authList[] = explode("||", $string_arr[$i])[1];
    }
    $all = array_combine($mailList, $authList);

    if ($widget->authorId == $widget->ownerId) {
        echo '<span class="vtag vmaster">博主</span>';
    } else if (in_array($email, $mailList)) {
        echo '<span class="vtag vauth">' . $all[$email] . '</span>';

    } else {
        echo '<span class="vtag vvisitor">访客</span>';
    }
}

//获取评论的锚点链接
function get_comment_at($coid)
{
    $db = Typecho_Db::get();
    $prow = $db->fetchRow($db->select('parent,status')->from('table.comments')
        ->where('coid = ?', $coid)); //当前评论
    $mail = "";
    $parent = @$prow['parent'];
    if ($parent != "0") { //子评论
        $arow = $db->fetchRow($db->select('author,status,mail')->from('table.comments')
            ->where('coid = ?', $parent)); //查询该条评论的父评论的信息
        @$author = @$arow['author']; //作者名称
        $mail = @$arow['mail'];
        if (@$author && $arow['status'] == "approved") { //父评论作者存在且父评论已经审核通过
            if (@$prow['status'] == "waiting") {
                echo '<p class="commentReview">（评论审核中）)</p>';
            }
            echo '<a onclick="b(this);return false;" href="#comment-' . $parent . '">@' . $author . '</a>';
        } else { //父评论作者不存在或者父评论没有审核通过
            if (@$prow['status'] == "waiting") {
                echo '<p class="commentReview">（评论审核中）)</p>';
            } else {
                echo '';
            }
        }

    } else { //母评论，无需输出锚点链接
        if (@$prow['status'] == "waiting") {
            echo '<p class="commentReview">（评论审核中）)</p>';
        } else {
            echo '';
        }
    }
}
/**
 * 重写评论显示函数
 */
function threadedComments($comments, $options)
{
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
                <?php $email = $comments->mail;
                $name = $comments->author;
                $comments_a = 'class="vimg" style="border-radius: 50%;"';
                echo getGravatar($email, $name, $comments_a); ?>
                <cite class="vnick">
                    <?php $comments->author(); ?>
                </cite>
                <?php commentRank($comments, $comments->mail); ?>

            </div>
            <div class="vhead">
                <b>
                    <?php $parentMail = get_comment_at($comments->coid) ?>
                    <?php echo $parentMail; ?>
                </b>
                <a class="vtime" href="<?php $comments->permalink(); ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
                <?php if (Helper::options()->CloseComments == 'off'): ?>
                    <span class="comment-reply">
                        <?php $comments->reply(); ?>
                    </span>
                <?php endif ?>
            </div>
            <div class="comment-content">
                <?php $comments->content(); ?>
            </div>
            <span class="comment-ua">
                <?php getOs($comments->agent); ?>
                <?php getBrowser($comments->agent); ?>
            </span>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php }

// 主页封面
function img_postthemb($thiz, $default_img)
{
    $content = $thiz->content;
    $ret = preg_match("/\<img.*?src\=\"(.*?)\"[^>]*>/i", $content, $thumbUrl);
    if ($ret === 1 && count($thumbUrl) == 2) {
        return $thumbUrl[1];
    } else {
        return $default_img = "https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg";
    }
}

//  输出标签  
function printTag($that)
{ ?>
    <?php if (count($that->tags) > 0): ?>
        <?php foreach ($that->tags as $tags): ?>
            <a href="<?php print($tags['permalink']) ?>" class="post-meta__tags"><span>
                    <?php print($tags['name']) ?>
                </span></a>
        <?php endforeach; ?>
    <?php else: ?>
        <a class="post-meta__tags"><span>无标签</span></a>
    <?php endif; ?>
<?php }


//当前人数
function onlinePeople()
{
    $online_log = "usr/themes/butterfly/online.dat"; //保存人数的文件到根目录,
    $timeout = 30; //30秒内没动作者,认为掉线
    if (!file_exists($online_log)) {
        fopen($online_log, "w");
    }
    $entries = file($online_log);
    $temp = array();
    for ($i = 0; $i < count($entries); $i++) {
        $entry = explode(",", trim($entries[$i]));
        if (($entry[0] != getenv('REMOTE_ADDR')) && ($entry[1] > time())) {
            array_push($temp, $entry[0] . "," . $entry[1] . "\n"); //取出其他浏览者的信息,并去掉超时者,保存进$temp
        }
    }
    array_push($temp, getenv('REMOTE_ADDR') . "," . (time() + ($timeout)) . "\n"); //更新浏览者的时间
    $slzxrs = count($temp); //计算在线人数
    $entries = implode("", $temp);
    //写入文件
    $fp = fopen($online_log, "w");
    flock($fp, LOCK_EX); //flock() 不能在NFS以及其他的一些网络文件系统中正常工作
    fputs($fp, $entries);
    flock($fp, LOCK_UN);
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
        $db->query('ALTER TABLE `' . $db->getPrefix() . 'contents` ADD `views` INT(10) DEFAULT 0;');
    }
    $exist = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid))['views'];
    if ($archive->is('single')) {
        $cookie = Typecho_Cookie::get('contents_views');
        $cookie = $cookie ? explode(',', $cookie) : array();
        if (!in_array($cid, $cookie)) {
            $db->query($db->update('table.contents')
                ->rows(array('views' => (int) $exist + 1))
                ->where('cid = ?', $cid));
            $exist = (int) $exist + 1;
            array_push($cookie, $cid);
            $cookie = implode(',', $cookie);
            Typecho_Cookie::set('contents_views', $cookie);
        }
    }
    echo $exist == 0 ? '0' : ' ' . $exist;
}
//总访问量
function theAllViews()
{
    $db = Typecho_Db::get();
    if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
        $db->query('ALTER TABLE `' . $db->getPrefix() . 'contents` ADD `views` INT(10) DEFAULT 0;');
    }
    $row = $db->fetchAll($db->select('SUM(views)')->from('table.contents'));
    echo array_values($row[0])[0];
}
//  回复可见       
Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('myyodux', 'one');
Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('myyodux', 'one');
class myyodux
{
    public static function one($con, $obj, $text)
    {
        $text = empty($text) ? $con : $text;
        if (!$obj->is('single')) {
            $text = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '', $text);
            //   $text = preg_replace("/\n\s*){3,}/sm",' ',$text);
        }
        return $text;
    }
}

/**
 * 显示上一篇
 *
 * 如果没有下一篇,返回null
 */
function thePrevCid($widget, $default = NULL)
{
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
}
;

/**
 * 获取下一篇文章mid
 *
 * 如果没有下一篇,返回null
 */
function theNextCid($widget, $default = NULL)
{
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
}
;

//调用博主最近文章更新时间
function get_last_update()
{
    $num = '1';
    $type = 'post';
    $status = 'publish';
    $now = time();
    $db = Typecho_Db::get();
    $prefix = $db->getPrefix();
    $create = $db->fetchRow($db->select('created')->from('table.contents')->where('table.contents.type=? and status=?', $type, $status)->order('created', Typecho_Db::SORT_DESC)->limit($num));
    $update = $db->fetchRow($db->select('modified')->from('table.contents')->where('table.contents.type=? and status=?', $type, $status)->order('modified', Typecho_Db::SORT_DESC)->limit($num));
    if ($create >= $update) {
        echo Typecho_I18n::dateWord(isset($create['created']), $now);
    } else {
        $lastday = floor(date($now - $update['modified']) / 86400);
        if ($lastday > 365) {
            $lastyear = floor(date($now - $update['modified']) / 30758400);
            echo $lastyear . " 年前";
        } elseif ($lastday > 30) {
            $lastmom = floor(date($now - $update['modified']) / 2592000);
            echo $lastmom . " 个月前";
        } elseif ($lastday < 1) {
            $lasthour = floor(date($now - $update['modified']) / 3600);
            if ($lasthour < 1) {
                $lastmin = floor(date($now - $update['modified']) / 60);
                if ($lastmin < 1) {
                    $lastsecd = floor(date($now - $update['modified']));
                    echo $lastsecd . " 秒前";
                } else {
                    echo $lastmin . " 分钟前";
                }
            } else {
                echo $lasthour . " 小时前";
            }
        } else {
            echo $lastday . " 天前";
        }
    }
}
//文章阅读时间统计
function art_time($cid)
{
    $db = Typecho_Db::get();
    $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid=?', $cid)->order('table.contents.cid', Typecho_Db::SORT_ASC)->limit(1));
    $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $rs['text']);
    $text_word = mb_strlen($text, 'utf-8');
    echo ceil($text_word / 400);
}
// 自定义编辑器
Typecho_Plugin::factory('admin/write-post.php')->bottom = array('editor', 'reset');
Typecho_Plugin::factory('admin/write-page.php')->bottom = array('editor', 'reset');
class editor
{
    public static function reset()
    {
        echo "<script src='" . Helper::options()->themeUrl . '/edit/extend.js?v1.7.6' . "'></script>";
        echo "<link rel='stylesheet' href='" . Helper::options()->themeUrl . '/edit/edit.css?v1.6.3' . "'>";
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
function RunTime()
{
    $site_create_time = strtotime(Helper::options()->buildtime);
    $time = time() - $site_create_time;
    if (is_numeric($time)) {
        if ($time >= 86400) {
            $days = floor($time / 86400);
            $time = ($time % 86400);
            echo $days . ' 天';
        } else {
            echo '1 天';
        }

    } else {
        echo '';
    }
}
function RecapOutPut($login)
{
    $siteKey = Helper::options()->siteKey;
    $secretKey = Helper::options()->secretKey;
    if ($siteKey !== "" && $secretKey !== "" && !$login) {
        echo '<script src="https://recaptcha.net/recaptcha/api.js" async defer data-no-instant></script>
                              <div class="g-recaptcha" data-sitekey=' . $siteKey . '></div>';
    }
    if (Helper::options()->hcaptchaSecretKey !== "" && Helper::options()->hcaptchaAPIKey !== "" && !$login) {
        echo '
            <div id="h-captcha" class="h-captcha" data-sitekey=' . Helper::options()->hcaptchaSecretKey . '></div>';
    }
}

function comments_filter($comment)
{
    if (isset($_REQUEST['text']) != null) {
        if ($_POST['g-recaptcha-response'] == null) {
            throw new Typecho_Widget_Exception(_t('人机验证失败,确认你加载了谷歌人机验证并通过验证'));
        } else {
            $siteKey = Helper::options()->siteKey;
            $secretKey = Helper::options()->secretKey;
            function getCaptcha($recaptcha_response, $secretKey)
            {
                $response = file_get_contents("https://recaptcha.net/recaptcha/api/siteverify?secret=" . $secretKey . "&response=" . $recaptcha_response);
                $response = json_decode($response);
                return $response;
            }
            $resp = getCaptcha($_POST['g-recaptcha-response'], $secretKey);

            if ($resp->success == true) {
                return $comments;
            } else {
                switch ($resp->error - codes) {
                    case '{[0] => "timeout-or-duplicate"}':
                        throw new Typecho_Widget_Exception(_t('验证时间超过2分钟或连续重复发言！'));
                        break;
                    case '{[0] => "invalid-input-secret"}':
                        throw new Typecho_Widget_Exception(_t('博主填了无效的siteKey或者secretKey...'));
                        break;
                    case '{[0] => "bad-request"}':
                        throw new Typecho_Widget_Exception(_t('请求错误！请检查网络'));
                        break;
                    default:
                        throw new Typecho_Widget_Exception(_t('很遗憾，您被当成了机器人...'));
                }
            }
        }
    }
    return $comment;
}


function hcaptcha_filter($comment)
{
    if (isset($_REQUEST['text']) != null) {
        if ($_POST['h-captcha-response'] == null) {
            throw new Typecho_Widget_Exception(_t('人机验证失败,确认你加载了hcaptcha人机验证并通过验证'));
        } else {
            if (isset($_POST['h-captcha-response']) && !empty($_POST['h-captcha-response'])) {
                $secret = Helper::options()->hcaptchaAPIKey;
                $verifyResponse = file_get_contents('https://hcaptcha.com/siteverify?secret=' . $secret . '&response=' . $_POST['h-captcha-response'] . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
                $responseData = json_decode($verifyResponse);
                if ($responseData->success === true || $responseData->success === 1) {
                    return $comments;
                } else {
                    switch ($responseData->error - codes) {
                        case '{[0] => "timeout-or-duplicate"}':
                            throw new Typecho_Widget_Exception(_t('验证时间超过2分钟或连续重复发言！'));
                            break;
                        case '{[0] => "invalid-input-secret"}':
                            throw new Typecho_Widget_Exception(_t('网站管理员填了无效的siteKey或者secretKey...'));
                            break;
                        case '{[0] => "bad-request"}':
                            throw new Typecho_Widget_Exception(_t('请求错误！请检查网络'));
                            break;
                        default:
                            throw new Typecho_Widget_Exception(_t('很遗憾，您被当成了机器人...'));
                    }
                }
            }
        }
    }
    return $comment;
}

// 微博热搜
function weibohot()
{
    $api = file_get_contents('https://weibo.com/ajax/side/hotSearch');
    $data = json_decode($api, true)['data']['realtime'];

    $jyzy = array(
        '电影' => '影',
        '剧集' => '剧',
        '综艺' => '综',
        '音乐' => '音',
        '盛典' => '盛',
        '晚会' => '晚',
    );

    $hotness = array(
        '爆' => 'weibo-boom',
        '热' => 'weibo-hot',
        '沸' => 'weibo-boil',
        '新' => 'weibo-new',
        '荐' => 'weibo-recommend',
        '音' => 'weibo-jyzy',
        '影' => 'weibo-jyzy',
        '剧' => 'weibo-jyzy',
        '综' => 'weibo-jyzy',
        '盛' => 'weibo-jyzy',
        '晚' => 'weibo-jyzy',
    );

    foreach ($data as $item) {
        $hot = '荐';
        if (isset($item['is_ad'])) {
            continue;
        }
        if (isset($item['is_boom'])) {
            $hot = '爆';
        }
        if (isset($item['is_hot'])) {
            $hot = '热';
        }
        if (isset($item['is_fei'])) {
            $hot = '沸';
        }
        if (isset($item['is_new'])) {
            $hot = '新';
        }
        if (isset($item['flag_desc'])) {
            $hot = $jyzy[$item['flag_desc']];
        }
        echo '<div class="weibo-list-item"><div class="weibo-hotness ' . $hotness[$hot] . '">' . $hot . '</div><span class="weibo-title"><a title="' . $item['note'] . '" href="https://s.weibo.com/weibo?q=%23' . $item['word'] . '%23" target="_blank" rel="external nofollow noreferrer" style="color:#a08ed5">' . $item['note'] . '</a></span><div class="weibo-num"><span>' . $item['num'] . '</span></div></div>';
    }
}

// 自定义文章摘要
function summaryContent($widget)
{
    $summaryContent = '';
    if ($widget->fields->summaryContent) {
        $summaryContent = $widget->fields->summaryContent;
    } elseif ($widget->fields->excerpt && $widget->fields->excerpt != '') {
        $summaryContent = $widget->fields->excerpt;
    } else {
        $summaryContent = $widget->excerpt(130);
    }
    echo $summaryContent;
}

//主页封面处理函数
function noCover($widget)
{
    if ($widget->fields->NoCover == "off") {
        return false;
    }
    return true;
}