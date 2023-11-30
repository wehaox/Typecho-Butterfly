<?php
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
    <script src='https://static.wehao.org/postdomai.js'></script>
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
         注意：你需要额外<a href="https://github.com/wehaox/Typecho-Butterfly/releases/download/1.7.7/static-23.11.zip">下载</a>对应版本的静态资源放进主题根目录直接解压即可<br>
         此文件与下方的自定义CDN文件通用'
    );
    $form->addInput($StaticFile->multiMode());

    $CDNURL = new Typecho_Widget_Helper_Form_Element_Text(
        'CDNURL',
        NULL,
        NULL,
        '自定义CDNURL(由@origami-tech提供)',
        '需要选择博客静态资源加载方式为CDN加载 此项才会生效 且<b>本地加载>自定义CDNURL>jsdelivr源</b><br>
    注意：你需要额外<a href="https://github.com/wehaox/Typecho-Butterfly/releases/download/1.7.7/static-23.11.zip">下载</a>静态资源放CDN解压<br>
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
        '是否使用Link插件进行友链(需点击<a href="https://static.wehao.org/Links.zip">这里</a>下载)',
        '介绍：新手和手残党极其友好,默认从主题读取防止报错'
    );
    $friendset->setAttribute('id', 'friends');
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
    $gaud_map_key = new Typecho_Widget_Helper_Form_Element_Text('gaud_map_key', NULL, null, _t('时钟高德地图web服务key'), _t('侧栏显示时钟用到的key，同上'));
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

    $themeColor = new Typecho_Widget_Helper_Form_Element_Text('themeColor', NULL, 
    _t('#eee'), _t('主题色'), _t('主要用于支持沉浸式状态栏的浏览器,默认为#eee'));
    $themeColor->setAttribute('id', 'CustomColor');
    $form->addInput($themeColor);
    //暗色模式选项
    $darkModeSelect = new Typecho_Widget_Helper_Form_Element_Select(
        'darkModeSelect',
        array(
            "1" => '始终亮色模式',
            '2' => '跟随系统（默认）',
            '3' => '跟随系统且按时间自动深色',
            '4' => '始终深色',
        ),
        '2',
        '暗色模式相关',
        '介绍：如果用户在左下角设置了颜色模式这里将不会生效'
    );
    $form->addInput($darkModeSelect->multiMode());

    $darkTime = new Typecho_Widget_Helper_Form_Element_Text('darkTime', NULL, 
    _t('7-20'), _t('自动暗色时间段'), _t('默认为7-20,24小时格式,按照格式(7-20)填写'));
    $form->addInput($darkTime);

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
?>