<?php
if (!defined('__TYPECHO_ROOT_DIR__'))
    exit;

require_once('api/api.php');
require_once('config/custom_config.php');

// 处理清除缓存的请求
if (isset($_GET['clearCache']) && is_numeric($_GET['clearCache'])) {
    // 检查用户是否为管理员
    $user = Typecho_Widget::widget('Widget_User');
    if ($user->hasLogin() && $user->pass('administrator')) {
        $cid = intval($_GET['clearCache']);
        // 清除该文章所有相关缓存
        clearCache('post_content_' . $cid);
        clearCache('post_full_content_' . $cid);
        clearCache('post_parsed_content_' . $cid);
        clearCache('post_parsed_content_v2_' . $cid);
        clearCache('post_details_' . $cid);
        clearCache('post_basic_' . $cid);
        clearCache('post_thumb_' . $cid);
        clearCache('theme_fields_' . $cid);
        
        // 清除不同用户状态的缓存版本
        clearCache('post_parsed_content_' . $cid . '_logged_in');
        clearCache('post_parsed_content_' . $cid . '_guest');
        clearCache('post_parsed_content_v2_' . $cid . '_logged_in');
        clearCache('post_parsed_content_v2_' . $cid . '_guest');
        
        // 清除全站统计相关缓存
        clearCache('all_characters');
        clearCache('site_statistics');
        clearCache('tags_num');
        clearCache('all_views');
        
        // 如果是AJAX请求，返回成功信息
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => true]);
            exit;
        }
    } else {
        // 如果不是管理员，返回错误信息
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode(['success' => false, 'message' => '只有管理员才能清除缓存']);
            exit;
        }
    }
}

// 新文章缩略图
function get_ArticleThumbnail($widget)
{
    $theme_path = Helper::options()->themeUrl;
    $random = $theme_path . '/img/DefualtThumbnail.jpg';
    $pattern = '/\<img.*?src\=\"(.*?)\"[^>]*>/i';
    $cid = 0;
    $content = '';

    if (is_array($widget)) {
        $cid = isset($widget['cid']) ? (int)$widget['cid'] : 0;
        if (!empty($widget['text'])) {
            $content = $widget['text'];
        } elseif (!empty($widget['content'])) {
            $content = $widget['content'];
        }
    } elseif (is_object($widget)) {
        $cid = isset($widget->cid) ? (int)$widget->cid : 0;
        if (!empty($widget->content)) {
            $content = $widget->content;
        } elseif (!empty($widget->text)) {
            $content = $widget->text;
        }
    }

    if ($cid > 0) {
        $cachedThumb = getCache('post_thumb_' . $cid, 604800);
        if ($cachedThumb !== false && !empty($cachedThumb)) {
            return $cachedThumb;
        }

        $customThumb = getThemeFieldValue($cid, 'thumb', '');
        if (!empty($customThumb)) {
            setCache('post_thumb_' . $cid, $customThumb);
            return $customThumb;
        }
    }

    if (!empty($content) && preg_match_all($pattern, $content, $thumbUrl) && strlen($thumbUrl[1][0]) > 7) {
        if ($cid > 0) {
            setCache('post_thumb_' . $cid, $thumbUrl[1][0]);
        }
        return $thumbUrl[1][0];
    } else {
        // 判断是否有Thumb文件夹
        $theme_dir = dirname(dirname(__FILE__)) . '/butterfly';
        if (is_dir($theme_dir . '/thumb')) {
            // 获取thumb目录下所有图片文件
            $images = array();
            foreach (array('jpg', 'jpeg', 'png', 'gif', 'webp') as $ext) {
                $images = array_merge($images, glob($theme_dir . '/thumb/*.' . $ext));
            }
            if (!empty($images)) {
                $index = ($cid > 0 ? $cid : 1) % count($images); // 使用取模运算确保索引在有效范围内
                $random_img = $images[$index];
                // $random_img = $images[array_rand($images)];
                $random = Helper::options()->themeUrl . '/thumb/' . basename($random_img);
                if ($cid > 0) {
                    setCache('post_thumb_' . $cid, $random);
                }
                return $random;
            }
        }
        if ($cid > 0) {
            setCache('post_thumb_' . $cid, $random);
        }
        return $random;
    }
}

function getThemeFieldMap($cid)
{
    static $fieldCache = array();
    $cid = (int)$cid;

    if ($cid <= 0) {
        return array();
    }

    if (isset($fieldCache[$cid])) {
        return $fieldCache[$cid];
    }

    $cache_key = 'theme_fields_' . $cid;
    $cached = getCache($cache_key, 86400);
    if (is_array($cached)) {
        $fieldCache[$cid] = $cached;
        return $cached;
    }

    $db = Typecho_Db::get();
    $rows = $db->fetchAll($db->select('name', 'str_value', 'int_value', 'float_value')
        ->from('table.fields')
        ->where('cid = ?', $cid));

    $fields = array();
    foreach ($rows as $row) {
        $value = null;
        if (array_key_exists('str_value', $row) && $row['str_value'] !== null && $row['str_value'] !== '') {
            $value = $row['str_value'];
        } elseif (array_key_exists('int_value', $row) && $row['int_value'] !== null) {
            $value = $row['int_value'];
        } elseif (array_key_exists('float_value', $row) && $row['float_value'] !== null) {
            $value = $row['float_value'];
        }

        if ($value !== null) {
            $fields[$row['name']] = $value;
        }
    }

    $fieldCache[$cid] = $fields;
    setCache($cache_key, $fields);
    return $fields;
}

function getThemeFieldValue($cid, $name, $default = null)
{
    $fields = getThemeFieldMap($cid);
    return array_key_exists($name, $fields) ? $fields[$name] : $default;
}

function getPostPermalinkByRow($post)
{
    static $permalinkCache = array();
    $cid = isset($post['cid']) ? (int)$post['cid'] : 0;

    if ($cid > 0 && isset($permalinkCache[$cid])) {
        return $permalinkCache[$cid];
    }

    if (!isset($post['type']) || empty($post['type'])) {
        $post['type'] = 'post';
    }

    if (!empty($post['created'])) {
        $post['year'] = date('Y', $post['created']);
        $post['month'] = date('m', $post['created']);
        $post['day'] = date('d', $post['created']);
    }

    try {
        $path = Typecho_Router::url($post['type'], $post);
        if (preg_match('/^https?:\/\//i', $path)) {
            $url = $path;
        } else {
            $url = Typecho_Common::url($path, Helper::options()->index);
        }
    } catch (Exception $e) {
        $slug = !empty($post['slug']) ? $post['slug'] : $cid;
        $url = rtrim(Helper::options()->siteUrl, '/') . '/' . ltrim($slug, '/');
    }

    if ($cid > 0) {
        $permalinkCache[$cid] = $url;
    }

    return $url;
}

function getPostBasicByCid($cid)
{
    static $postCache = array();
    $cid = (int)$cid;

    if ($cid <= 0) {
        return null;
    }

    if (isset($postCache[$cid])) {
        return $postCache[$cid];
    }

    $cache_key = 'post_basic_' . $cid;
    $cached = getCache($cache_key, 86400);
    if (is_array($cached)) {
        $postCache[$cid] = $cached;
        return $cached;
    }

    $db = Typecho_Db::get();
    $row = $db->fetchRow($db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'status', 'authorId', 'views')
        ->from('table.contents')
        ->where('cid = ?', $cid)
        ->limit(1));

    if (!$row) {
        $postCache[$cid] = null;
        return null;
    }

    $row['permalink'] = getPostPermalinkByRow($row);
    $postCache[$cid] = $row;
    setCache($cache_key, $row);
    return $row;
}

function getRelatedPostsLite($cid, $limit = 3)
{
    $cid = (int)$cid;
    $limit = max(1, (int)$limit);

    if ($cid <= 0) {
        return array();
    }

    $cache_key = 'related_posts_lite_' . $cid . '_' . $limit;
    $cached = getCache($cache_key, 600);
    if (is_array($cached)) {
        return $cached;
    }

    $db = Typecho_Db::get();
    $mids = $db->fetchAll($db->select('mid')
        ->from('table.relationships')
        ->where('cid = ?', $cid)
        ->limit(10));

    if (empty($mids)) {
        setCache($cache_key, array());
        return array();
    }

    $midList = array_map('intval', array_column($mids, 'mid'));
    $rows = $db->fetchAll($db->select('DISTINCT table.contents.cid', 'table.contents.title', 'table.contents.slug', 'table.contents.created', 'table.contents.modified', 'table.contents.type', 'table.contents.status', 'table.contents.authorId')
        ->from('table.contents')
        ->join('table.relationships', 'table.contents.cid = table.relationships.cid')
        ->where('table.relationships.mid IN ?', $midList)
        ->where('table.contents.cid <> ?', $cid)
        ->where('table.contents.status = ?', 'publish')
        ->where('table.contents.password IS NULL')
        ->where('table.contents.created < ?', time())
        ->where('table.contents.type = ?', 'post')
        ->order('table.contents.created', Typecho_Db::SORT_DESC)
        ->limit($limit));

    foreach ($rows as &$row) {
        $row['permalink'] = getPostPermalinkByRow($row);
    }
    unset($row);

    setCache($cache_key, $rows);
    return $rows;
}

function getRecentPostsLite($limit = 5)
{
    $limit = max(1, (int)$limit);
    $cache_key = 'recent_posts_lite_' . $limit;
    $cached = getCache($cache_key, 300);
    if (is_array($cached)) {
        return $cached;
    }

    $db = Typecho_Db::get();
    $rows = $db->fetchAll($db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'status', 'authorId', 'text')
        ->from('table.contents')
        ->where('status = ?', 'publish')
        ->where('type = ?', 'post')
        ->where('created < ?', time())
        ->where('password IS NULL')
        ->order('created', Typecho_Db::SORT_DESC)
        ->limit($limit));

    foreach ($rows as &$row) {
        $row['permalink'] = getPostPermalinkByRow($row);
    }
    unset($row);

    setCache($cache_key, $rows);
    return $rows;
}

function getRecentPostsLiteByPage($pageSize = 20, $currentPage = 1)
{
    $pageSize = max(1, (int)$pageSize);
    $currentPage = max(1, (int)$currentPage);
    $cache_key = 'recent_posts_lite_page_' . $pageSize . '_' . $currentPage;
    $cached = getCache($cache_key, 300);
    if (is_array($cached)) {
        return $cached;
    }

    $db = Typecho_Db::get();
    $rows = $db->fetchAll($db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'status', 'authorId', 'text')
        ->from('table.contents')
        ->where('status = ?', 'publish')
        ->where('type = ?', 'post')
        ->where('created < ?', time())
        ->where('password IS NULL')
        ->order('created', Typecho_Db::SORT_DESC)
        ->page($currentPage, $pageSize));

    foreach ($rows as &$row) {
        $row['permalink'] = getPostPermalinkByRow($row);
    }
    unset($row);

    setCache($cache_key, $rows);
    return $rows;
}

function getRecentPostsLiteCount()
{
    $cache_key = 'recent_posts_lite_count';
    $cached = getCache($cache_key, 300);
    if ($cached !== false) {
        return (int)$cached;
    }

    $db = Typecho_Db::get();
    $count = $db->fetchObject($db->select(array('COUNT(cid)' => 'num'))
        ->from('table.contents')
        ->where('status = ?', 'publish')
        ->where('type = ?', 'post')
        ->where('created < ?', time())
        ->where('password IS NULL'));

    $total = isset($count->num) ? (int)$count->num : 0;
    setCache($cache_key, $total);
    return $total;
}

function getCommentPermalink($comment)
{
    $cid = is_object($comment) ? (int)$comment->cid : (isset($comment['cid']) ? (int)$comment['cid'] : 0);
    $coid = is_object($comment) ? (int)$comment->coid : (isset($comment['coid']) ? (int)$comment['coid'] : 0);

    if ($cid <= 0) {
        return '#comment-' . $coid;
    }

    $post = getPostBasicByCid($cid);
    if (!$post || empty($post['permalink'])) {
        return '#comment-' . $coid;
    }

    return $post['permalink'] . '#comments';
}

// 主页文章缩略图
function GetRandomThumbnail($widget)
{
    // $random = 'https://i.loli.net/2020/05/01/gkihqEjXxJ5UZ1C.jpg';
    echo get_ArticleThumbnail($widget);
}
// 文章封面缩略图
function GetRandomThumbnailPost($widget)
{
    $img = '';
    if (isset($widget->cid)) {
        $img = getThemeFieldValue($widget->cid, 'thumb', '');
    }
    echo $img;
}

// 添加缓存辅助函数
function getCacheDirectoryPath()
{
    return rtrim(__TYPECHO_ROOT_DIR__, '/\\') . '/usr/cache/';
}

function setFileCachePermissionWarning($message)
{
    if (empty($GLOBALS['butterflyFileCachePermissionWarning'])) {
        $GLOBALS['butterflyFileCachePermissionWarning'] = $message;
    }
}

function getFileCachePermissionWarning()
{
    return isset($GLOBALS['butterflyFileCachePermissionWarning']) ? (string)$GLOBALS['butterflyFileCachePermissionWarning'] : '';
}

function ensureFileCacheDirectoryAvailable()
{
    static $available = null;

    if ($available !== null) {
        return $available;
    }

    if (Helper::options()->EnableCache != 'file') {
        $available = false;
        return false;
    }

    $cacheDir = getCacheDirectoryPath();
    $warningMessage = '文件缓存已开启，但 usr/cache/ 目录没有读写权限，已自动跳过缓存读写。';

    if (is_dir($cacheDir)) {
        if (is_readable($cacheDir) && is_writable($cacheDir)) {
            $available = true;
            return true;
        }

        setFileCachePermissionWarning($warningMessage);
        $available = false;
        return false;
    }

    if (@mkdir($cacheDir, 0755, true) && is_dir($cacheDir) && is_readable($cacheDir) && is_writable($cacheDir)) {
        $available = true;
        return true;
    }

    setFileCachePermissionWarning($warningMessage);
    $available = false;
    return false;
}

function renderFileCachePermissionWarning()
{
    if (Helper::options()->EnableCache == 'file') {
        ensureFileCacheDirectoryAvailable();
    }

    $message = getFileCachePermissionWarning();

    if ($message === '') {
        return;
    }

    echo '<div class="bf-cache-permission-warning" role="alert" style="background:#fff3cd;color:#856404;border-bottom:1px solid #ffe69c;padding:12px 16px;text-align:center;font-size:14px;line-height:1.7;position:relative;z-index:99999;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
}

function getCache($key, $expiration = 3600) {
    // 检查是否启用缓存
    if (Helper::options()->EnableCache != 'file') {
        return false;
    }

    if (!ensureFileCacheDirectoryAvailable()) {
        return false;
    }
    
    $cache_file = getCacheDirectoryPath() . md5($key) . '.cache';
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < $expiration)) {
        $data = @file_get_contents($cache_file);
        if ($data === false) {
            return false;
        }
        
        $unserialized = @unserialize($data);
        if ($unserialized === false && $data !== 'b:0;') {
            // 缓存数据损坏，删除缓存文件
            @unlink($cache_file);
            return false;
        }
        
        return $unserialized;
    }
    return false;
}

function setCache($key, $value) {
    // 检查是否启用缓存
    if (Helper::options()->EnableCache != 'file') {
        return;
    }

    if (!ensureFileCacheDirectoryAvailable()) {
        return;
    }

    $cache_file = getCacheDirectoryPath() . md5($key) . '.cache';
    if (@file_put_contents($cache_file, serialize($value)) === false) {
        setFileCachePermissionWarning('文件缓存已开启，但 usr/cache/ 目录没有写入权限，已自动跳过缓存写入。');
    }
}

// 优化全站字数统计，添加缓存
function allOfCharacters()
{
    $site = getSiteStatistics();
    echo $site['charCount'];
}

// function thumb($cid)
// {
//     if (empty($imgurl)) {
//         $rand_num = 10; //随机图片数量，根据图片目录中图片实际数量设置
//         if ($rand_num == 0) {
//             $imgurl = "img/0.jpg";
//             //如果$rand_num = 0,则显示默认图片，须命名为"0.jpg"，注意是绝对地址
//         } else {
//             $imgurl = "img/" . rand(1, $rand_num) . ".jpg";
//             //随机图片，须按"1.jpg","2.jpg","3.jpg"...的顺序命名，注意是绝对地址
//         }
//     }
//     $db = Typecho_Db::get();
//     $rs = $db->fetchRow($db->select('table.contents.text')
//         ->from('table.contents')
//         ->where('table.contents.type = ?', 'attachment')
//         ->where('table.contents.parent= ?', $cid)
//         ->order('table.contents.cid', Typecho_Db::SORT_ASC)
//         ->limit(1));
//     $img = unserialize($rs['text']);
//     if (empty($img)) {
//         echo $imgurl;
//     } else {
//         echo '你的博客地址' . $img['path'];
//     }
// }


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
    $codeBlocks = array();
    $obj = protectCodeBlocks($obj, $codeBlocks);
    $obj = preg_replace_callback('/<h([1-6])(.*?)>(.*?)<\/h\1>/i', function ($obj) {
        global $catalog;
        global $catalog_count;
        $catalog_count++;
        $catalog[] = array('text' => trim(strip_tags($obj[3])), 'depth' => $obj[1], 'count' => $catalog_count);
        return '<h' . $obj[1] . $obj[2] . ' id="cl-' . $catalog_count . '"><a class="markdownIt-Anchor" href="#cl-' . $catalog_count . '"></a>' . $obj[3] . '</h' . $obj[1] . '>';
    }, $obj);
    return restoreCodeBlocks($obj, $codeBlocks);
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

function protectCodeBlocks($text, &$codeBlocks)
{
    $codeBlocks = array();
    $patterns = array(
        '/<figure\b[^>]*class=("|\")[^>]*\bhighlight\b[^>]*\1[^>]*>[\s\S]*?<\/figure>/is',
        '/(`{3,})([^\r\n]*)\R[\s\S]*?(?:\R\1|\1)(?=\s*(?:\R|$))/m',
        '/<pre\b[^>]*>.*?<\/pre>/is'
    );

    foreach ($patterns as $pattern) {
        $text = preg_replace_callback($pattern, function ($matches) use (&$codeBlocks) {
            $placeholder = '__BF_CODE_BLOCK_' . str_replace('.', '_', uniqid('', true)) . '_' . count($codeBlocks) . '__';
            $codeBlocks[$placeholder] = $matches[0];
            return $placeholder;
        }, $text);
    }

    return $text;
}

function restoreCodeBlocks($text, $codeBlocks)
{
    if (empty($codeBlocks)) {
        return $text;
    }

    return strtr($text, $codeBlocks);
}

function normalizeCodeBlockLanguage($language)
{
    $language = strtolower(trim((string) $language));
    $language = preg_replace('/^(language|lang)-/i', '', $language);
    $language = preg_replace('/[^a-z0-9#+._-]/i', '', $language);

    if ($language === '' || $language === 'text' || $language === 'plaintext' || $language === 'plain-text') {
        return 'plain';
    }

    return $language;
}

function extractCodeLanguageFromAttributes($attributes)
{
    if (preg_match('/\bdata-language=("|\")(.*?)\1/i', $attributes, $matches)) {
        return normalizeCodeBlockLanguage($matches[2]);
    }

    if (preg_match('/\bclass=("|\")(.*?)\1/i', $attributes, $matches)) {
        $classes = preg_split('/\s+/', trim($matches[2]));
        foreach ($classes as $className) {
            if (preg_match('/^(?:language|lang)-(.+)$/i', $className, $classMatches)) {
                return normalizeCodeBlockLanguage($classMatches[1]);
            }
        }
    }

    return 'plain';
}

function hasHighlightedCodeMarkup($code)
{
    return preg_match('/<span\b/i', (string) $code) === 1;
}

function normalizeHighlightedCodeHtmlClasses($html)
{
    return preg_replace_callback('/\bclass=("|\")(.*?)\1/i', function ($matches) {
        $classes = preg_split('/\s+/', trim($matches[2]));
        $normalizedClasses = array();

        foreach ($classes as $className) {
            if ($className === '' || $className === 'hljs') {
                continue;
            }

            if (strpos($className, 'hljs-') === 0) {
                $className = substr($className, 5);
            }

            if ($className === '' || in_array($className, $normalizedClasses, true)) {
                continue;
            }

            $normalizedClasses[] = $className;
        }

        if (empty($normalizedClasses)) {
            return '';
        }

        return 'class="' . implode(' ', $normalizedClasses) . '"';
    }, (string) $html);
}

function closeHighlightedCodeTags($openTags)
{
    $closingTags = '';

    for ($i = count($openTags) - 1; $i >= 0; $i--) {
        $closingTags .= '</' . $openTags[$i]['name'] . '>';
    }

    return $closingTags;
}

function reopenHighlightedCodeTags($openTags)
{
    $openingTags = '';

    foreach ($openTags as $openTag) {
        $openingTags .= $openTag['tag'];
    }

    return $openingTags;
}

function isSelfClosingHighlightedCodeTag($token)
{
    if (preg_match('/\/\s*>$/', $token)) {
        return true;
    }

    return preg_match('/^<\s*(?:area|base|br|col|embed|hr|img|input|link|meta|param|source|track|wbr)\b/i', $token) === 1;
}

function splitHighlightedCodeHtmlLines($html)
{
    $html = normalizeHighlightedCodeHtmlClasses($html);
    $html = str_replace(array("\r\n", "\r"), "\n", (string) $html);
    $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
    $html = rtrim($html, "\n");

    if ($html === '') {
        return array('');
    }

    $tokens = preg_split('/(<[^>]+>)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    $lines = array();
    $currentLine = '';
    $openTags = array();
    $pushCurrentLine = function () use (&$lines, &$currentLine, &$openTags) {
        $lineHtml = $currentLine . closeHighlightedCodeTags($openTags);
        $lines[] = $lineHtml === '' ? ' ' : $lineHtml;
        $currentLine = reopenHighlightedCodeTags($openTags);
    };

    foreach ($tokens as $token) {
        if (preg_match('/^<br\s*\/?>$/i', $token)) {
            $pushCurrentLine();
            continue;
        }

        if ($token !== '' && $token[0] === '<') {
            if (preg_match('/^<\s*\/\s*([a-z0-9:-]+)/i', $token, $matches)) {
                $currentLine .= $token;
                $tagName = strtolower($matches[1]);

                for ($i = count($openTags) - 1; $i >= 0; $i--) {
                    if ($openTags[$i]['name'] === $tagName) {
                        array_splice($openTags, $i, 1);
                        break;
                    }
                }

                continue;
            }

            $currentLine .= $token;

            if (preg_match('/^<\s*([a-z0-9:-]+)/i', $token, $matches) && !isSelfClosingHighlightedCodeTag($token)) {
                $openTags[] = array(
                    'name' => strtolower($matches[1]),
                    'tag' => $token
                );
            }

            continue;
        }

        $segments = explode("\n", $token);
        $lastSegmentIndex = count($segments) - 1;

        foreach ($segments as $index => $segment) {
            $currentLine .= $segment;

            if ($index !== $lastSegmentIndex) {
                $pushCurrentLine();
            }
        }
    }

    if ($currentLine !== '' || empty($lines)) {
        $lines[] = $currentLine === '' ? ' ' : $currentLine;
    }

    return $lines;
}

function getButterflyCodeBlockLines($code, $isHighlightedHtml = false)
{
    if ($isHighlightedHtml) {
        return splitHighlightedCodeHtmlLines($code);
    }

    $normalizedCode = str_replace(array("\r\n", "\r"), "\n", html_entity_decode((string) $code, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $normalizedCode = rtrim($normalizedCode, "\n");
    $lines = $normalizedCode === '' ? array('') : explode("\n", $normalizedCode);

    foreach ($lines as $index => $line) {
        $lines[$index] = htmlspecialchars($line, ENT_NOQUOTES, 'UTF-8');
        if ($lines[$index] === '') {
            $lines[$index] = ' ';
        }
    }

    return $lines;
}

function buildButterflyCodeBlockHtml($code, $language = 'plain', $isHighlightedHtml = false)
{
    $language = normalizeCodeBlockLanguage($language);
    $lines = getButterflyCodeBlockLines($code, $isHighlightedHtml);
    $gutterHtml = '';
    $codeHtml = '';
    $lastIndex = count($lines) - 1;

    foreach ($lines as $index => $line) {
        $lineBreak = $index !== $lastIndex ? '<br>' : '';
        $gutterHtml .= '<span class="line">' . ($index + 1) . '</span>' . $lineBreak;
        $codeHtml .= '<span class="line">' . $line . '</span>' . $lineBreak;
    }

    return '<figure class="highlight ' . $language . '"><table><tbody><tr><td class="gutter"><pre>' . $gutterHtml . '</pre></td><td class="code"><pre>' . $codeHtml . '</pre></td></tr></tbody></table></figure>';
}

function convertCodeBlockToButterflyHtml($block)
{
    $trimmedBlock = trim($block);

    if (preg_match('/^<figure\b[^>]*class=("|\")[^>]*\bhighlight\b[^>]*\1[^>]*>/i', $trimmedBlock)) {
        return $block;
    }

    if (preg_match('/^(`{3,})([^\r\n]*)\R([\s\S]*?)(?:\R\1|\1)\s*$/', $trimmedBlock, $matches)) {
        $infoString = trim($matches[2]);
        $language = $infoString === '' ? 'plain' : preg_split('/\s+/', $infoString)[0];
        return buildButterflyCodeBlockHtml($matches[3], $language);
    }

    if (preg_match('/<pre\b([^>]*)>([\s\S]*?)<\/pre>/is', $block, $matches)) {
        $preAttributes = $matches[1];
        $preContent = $matches[2];
        $language = extractCodeLanguageFromAttributes($preAttributes);
        $codeContent = $preContent;
        $isHighlightedHtml = false;

        if (preg_match('/<code\b([^>]*)>([\s\S]*?)<\/code>/is', $preContent, $codeMatches)) {
            $codeContent = $codeMatches[2];
            $codeLanguage = extractCodeLanguageFromAttributes($codeMatches[1]);
            if ($language === 'plain' || $codeLanguage !== 'plain') {
                $language = $codeLanguage;
            }

            $isHighlightedHtml = hasHighlightedCodeMarkup($codeContent);
        }

        if ($isHighlightedHtml) {
            return buildButterflyCodeBlockHtml($codeContent, $language, true);
        }

        $codeContent = preg_replace('/<br\s*\/?>/i', "\n", $codeContent);
        return buildButterflyCodeBlockHtml(strip_tags($codeContent), $language);
    }

    return $block;
}

function convertProtectedCodeBlocksToButterflyHtml($codeBlocks)
{
    if (empty($codeBlocks)) {
        return $codeBlocks;
    }

    foreach ($codeBlocks as $placeholder => $block) {
        $codeBlocks[$placeholder] = convertCodeBlockToButterflyHtml($block);
    }

    return $codeBlocks;
}

function replaceHideContent($text, $canViewHiddenContent)
{
    $codeBlocks = array();
    $text = protectCodeBlocks($text, $codeBlocks);

    if ($canViewHiddenContent) {
        $text = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '<div class="reply-content">$1</div>', $text);
    } else {
        $text = preg_replace("/\[hide\](.*?)\[\/hide\]/sm", '<p class="need-reply">此处内容 <a href="#comments">回复</a> 可见</p>', $text);
    }

    $codeBlocks = convertProtectedCodeBlocksToButterflyHtml($codeBlocks);
    return restoreCodeBlocks($text, $codeBlocks);
}

/* 格式化标签 */
function ParseCode($text)
{
    $codeBlocks = array();
    $text = protectCodeBlocks($text, $codeBlocks);
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
    $text = ParseGallery($text);
    $text = PostImage($text);
    $codeBlocks = convertProtectedCodeBlocksToButterflyHtml($codeBlocks);
    return restoreCodeBlocks($text, $codeBlocks);
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
function getImageAttributeValue($imgTag, $attribute)
{
    $pattern = '/\b' . preg_quote($attribute, '/') . '\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))/i';

    if (preg_match($pattern, $imgTag, $matches)) {
        foreach (array(1, 2, 3) as $index) {
            if (array_key_exists($index, $matches)) {
                return $matches[$index];
            }
        }
    }

    return '';
}

function buildImageAltText($src, $alt = '', $title = '')
{
    $text = trim(strip_tags(html_entity_decode((string)$alt, ENT_QUOTES, 'UTF-8')));

    if ($text === '') {
        $text = trim(strip_tags(html_entity_decode((string)$title, ENT_QUOTES, 'UTF-8')));
    }

    if ($text === '') {
        $path = parse_url($src, PHP_URL_PATH);
        if (!empty($path)) {
            $filename = pathinfo($path, PATHINFO_FILENAME);
            $filename = trim(preg_replace('/[-_]+/', ' ', $filename));
            if ($filename !== '') {
                $text = $filename;
            }
        }
    }

    return $text !== '' ? $text : '文章配图';
}

function parseButterflyGalleryTagArgs($args)
{
    $result = array();
    preg_match_all('/"([^"]*)"|\'([^\']*)\'|(\S+)/u', trim((string)$args), $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        if (isset($match[1]) && $match[1] !== '') {
            $result[] = $match[1];
        } elseif (isset($match[2]) && $match[2] !== '') {
            $result[] = $match[2];
        } elseif (isset($match[3])) {
            $result[] = trim($match[3]);
        }
    }

    return $result;
}

function parseButterflyGalleryOptions($args)
{
    $parts = array_map('trim', explode(',', trim((string)$args)));

    return array(
        'lazyload' => isset($parts[0]) && $parts[0] !== '' ? $parts[0] : 'false',
        'rowHeight' => isset($parts[1]) && $parts[1] !== '' ? $parts[1] : '220',
        'limit' => isset($parts[2]) && $parts[2] !== '' ? $parts[2] : '10'
    );
}

function normalizeButterflyGalleryText($text)
{
    $text = html_entity_decode((string)$text, ENT_QUOTES, 'UTF-8');
    $text = trim(strip_tags($text));

    return $text;
}

function normalizeButterflyGalleryUrl($url)
{
    return trim(html_entity_decode((string)$url, ENT_QUOTES, 'UTF-8'));
}

function sanitizeButterflyGalleryUrl($url, $allowDataImage = false)
{
    $url = normalizeButterflyGalleryUrl($url);
    if ($url === '' || preg_match('/[\x00-\x20\x7f"\'<>]/u', $url)) {
        return '';
    }

    if (strpos($url, '//') === 0 || strpos($url, '/') === 0 || strpos($url, './') === 0 || strpos($url, '../') === 0) {
        return $url;
    }

    if (preg_match('/^[a-z][a-z0-9+.-]*:/i', $url)) {
        $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
        if (in_array($scheme, array('http', 'https'), true)) {
            return $url;
        }

        if ($allowDataImage && $scheme === 'data' && preg_match('/^data:image\/(?:gif|png|jpe?g|webp);base64,[a-z0-9+\/]+=*$/i', $url)) {
            return $url;
        }

        return '';
    }

    return $url;
}

function addButterflyGalleryImage(&$images, $src, $alt = '', $title = '')
{
    $src = sanitizeButterflyGalleryUrl($src, true);
    if ($src === '') {
        return;
    }

    $alt = normalizeButterflyGalleryText($alt);
    $title = normalizeButterflyGalleryText($title);

    if ($alt === '') {
        $alt = buildImageAltText($src, '', $title);
    }
    if ($title === '') {
        $title = $alt;
    }

    $images[] = array(
        'url' => $src,
        'alt' => $alt,
        'title' => $title
    );
}

function parseButterflyGalleryImages($content)
{
    $items = array();
    $content = (string)$content;

    if (preg_match_all('/<img\b[^>]*>/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[0] as $match) {
            $imgTag = $match[0];
            $src = getImageAttributeValue($imgTag, 'data-lazy-src');
            if ($src === '') {
                $src = getImageAttributeValue($imgTag, 'src');
            }

            $items[] = array(
                'offset' => $match[1],
                'src' => $src,
                'alt' => getImageAttributeValue($imgTag, 'alt'),
                'title' => getImageAttributeValue($imgTag, 'title')
            );
        }
    }

    if (preg_match_all('/!\[([^\]]*)\]\(\s*([^\s\)]+)(?:\s+(?:"([^"]*)"|\'([^\']*)\'|\(([^\)]*)\)))?\s*\)/u', $content, $matches, PREG_OFFSET_CAPTURE)) {
        foreach ($matches[0] as $index => $match) {
            $title = '';
            foreach (array(3, 4, 5) as $titleIndex) {
                if (isset($matches[$titleIndex][$index][0]) && $matches[$titleIndex][$index][0] !== '') {
                    $title = $matches[$titleIndex][$index][0];
                    break;
                }
            }

            $items[] = array(
                'offset' => $match[1],
                'src' => $matches[2][$index][0],
                'alt' => isset($matches[1][$index][0]) ? $matches[1][$index][0] : '',
                'title' => $title
            );
        }
    }

    usort($items, function ($a, $b) {
        if ($a['offset'] == $b['offset']) {
            return 0;
        }

        return $a['offset'] < $b['offset'] ? -1 : 1;
    });

    $images = array();
    foreach ($items as $item) {
        $src = normalizeButterflyGalleryUrl($item['src']);
        if ($src === '') {
            continue;
        }

        addButterflyGalleryImage($images, $src, $item['alt'], $item['title']);
    }

    return $images;
}

function isButterflyGalleryLazyload($value)
{
    $value = strtolower(trim((string)$value));

    return in_array($value, array('true', 'show', 'on', '1', 'yes'), true);
}

function normalizeButterflyGalleryNumber($value, $default)
{
    $value = (int)trim((string)$value);

    return $value > 0 ? $value : $default;
}

function renderButterflyGalleryHtml($images, $lazyload = 'false', $rowHeight = '220', $limit = '10')
{
    if (empty($images) || !is_array($images)) {
        return '';
    }

    $isLazyload = isButterflyGalleryLazyload($lazyload);
    $rowHeight = normalizeButterflyGalleryNumber($rowHeight, 220);
    $limit = normalizeButterflyGalleryNumber($limit, 10);
    $json = json_encode($images, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return '';
    }

    return '<div class="gallery butterfly-gallery" style="display:block"><div class="fj-gallery' . ($isLazyload ? ' lazyload' : '') . '" data-rowHeight="' . $rowHeight . '" data-limit="' . $limit . '"><div class="gallery-data">' . htmlspecialchars($json, ENT_NOQUOTES, 'UTF-8') . '</div></div>' . ($isLazyload ? '<button type="button">加载更多<i class="fas fa-angle-down"></i></button>' : '') . '</div>';
}

function ParseGallery($text)
{
    $text = preg_replace_callback('/(?:<p>\s*)?\{%\s*galleryGroup\s+([^%]*?)\s*%\}(?:\s*<\/p>)?/iu', function ($matches) {
        $args = parseButterflyGalleryTagArgs($matches[1]);
        $name = isset($args[0]) ? $args[0] : '';
        $description = isset($args[1]) ? $args[1] : '';
        $url = isset($args[2]) ? $args[2] : '';
        $img = isset($args[3]) ? $args[3] : '';

        if ($name === '' && $url === '' && $img === '') {
            return $matches[0];
        }

        $url = sanitizeButterflyGalleryUrl($url, false);
        $img = sanitizeButterflyGalleryUrl($img, true);

        return '<figure class="gallery-group"><img class="gallery-group-img no-lightbox" src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="Group Image Gallery"><figcaption><div class="gallery-group-name">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</div><p>' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '</p><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"></a></figcaption></figure>';
    }, $text);

    $text = preg_replace_callback('/(?:<p>\s*)?\{%\s*gallery\b([^%]*?)\s*%\}(?:\s*<br\s*\/?>)?(?:\s*<\/p>)?([\s\S]*?)(?:<p>\s*)?\{%\s*endgallery\s*%\}(?:\s*<\/p>)?/iu', function ($matches) {
        $options = parseButterflyGalleryOptions($matches[1]);
        $images = parseButterflyGalleryImages($matches[2]);
        $html = renderButterflyGalleryHtml($images, $options['lazyload'], $options['rowHeight'], $options['limit']);

        return $html !== '' ? $html : $matches[0];
    }, $text);

    return $text;
}

function getButterflyPhotoGalleryCover($row)
{
    $cid = isset($row['cid']) ? (int)$row['cid'] : 0;
    $thumb = getThemeFieldValue($cid, 'thumb', '');
    $thumb = sanitizeButterflyGalleryUrl($thumb, true);
    if ($thumb !== '') {
        return $thumb;
    }

    if (!empty($row['text'])) {
        $contentImages = parseButterflyGalleryImages($row['text']);
        if (!empty($contentImages[0]['url'])) {
            return $contentImages[0]['url'];
        }
    }

    return get_ArticleThumbnail($row);
}

function getButterflyPhotoGalleryDescription($row)
{
    $cid = isset($row['cid']) ? (int)$row['cid'] : 0;
    $description = getThemeFieldValue($cid, 'summaryContent', '');
    if (trim((string)$description) === '') {
        $description = getThemeFieldValue($cid, 'desc', '');
    }

    if (trim((string)$description) === '' && !empty($row['text'])) {
        $description = preg_replace('/\{%\s*gallery[\s\S]*?\{%\s*endgallery\s*%\}/iu', '', $row['text']);
        $description = preg_replace('/\{%\s*galleryGroup\s+[^%]*?%\}/iu', '', $description);
    }

    $description = normalizeButterflyGalleryText($description);
    if (function_exists('mb_substr')) {
        return mb_substr($description, 0, 80, 'UTF-8');
    }

    return substr($description, 0, 240);
}

function getButterflyPhotoGalleryPages($excludeCid = 0)
{
    $excludeCid = (int)$excludeCid;
    $db = Typecho_Db::get();
    $rows = $db->fetchAll($db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'status', 'authorId', 'text', 'template')
        ->from('table.contents')
        ->where('status = ?', 'publish')
        ->where('type = ?', 'page')
        ->where('created < ?', time())
        ->where('password IS NULL')
        ->order('table.contents.order', Typecho_Db::SORT_ASC));

    $pages = array();
    foreach ($rows as $row) {
        $cid = isset($row['cid']) ? (int)$row['cid'] : 0;
        if ($cid <= 0 || $cid === $excludeCid) {
            continue;
        }

        $template = isset($row['template']) ? (string)$row['template'] : '';
        $hasGalleryTag = !empty($row['text']) && preg_match('/\{%\s*gallery\b/i', $row['text']);
        if ($template !== 'photos.php' && !$hasGalleryTag) {
            continue;
        }

        $row['permalink'] = getPostPermalinkByRow($row);
        $row['cover'] = getButterflyPhotoGalleryCover($row);
        $row['description'] = getButterflyPhotoGalleryDescription($row);
        $pages[] = $row;
    }

    return $pages;
}

function renderButterflyPhotoGalleryGroup($name, $description, $url, $img)
{
    $url = sanitizeButterflyGalleryUrl($url, false);
    $img = sanitizeButterflyGalleryUrl($img, true);
    if ($url === '' || $img === '') {
        return '';
    }

    return '<figure class="gallery-group"><img class="gallery-group-img no-lightbox" src="' . htmlspecialchars($img, ENT_QUOTES, 'UTF-8') . '" alt="Group Image Gallery"><figcaption><div class="gallery-group-name">' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</div><p>' . htmlspecialchars($description, ENT_QUOTES, 'UTF-8') . '</p><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"></a></figcaption></figure>';
}

function renderButterflyPhotoGalleryList($excludeCid = 0)
{
    $pages = getButterflyPhotoGalleryPages($excludeCid);
    if (empty($pages)) {
        return '<div class="gallery-group-main"><p class="is-center">暂无照片墙</p></div>';
    }

    $html = '<div class="gallery-group-main">';
    foreach ($pages as $page) {
        $html .= renderButterflyPhotoGalleryGroup(
            isset($page['title']) ? $page['title'] : '',
            isset($page['description']) ? $page['description'] : '',
            isset($page['permalink']) ? $page['permalink'] : '',
            isset($page['cover']) ? $page['cover'] : ''
        );
    }
    $html .= '</div>';

    return $html;
}

function PostImage($text)
{
    $text = preg_replace_callback('/<img\b[^>]*>/i', function ($matches) {
        $imgTag = $matches[0];
        $src = getImageAttributeValue($imgTag, 'src');

        if ($src === '') {
            return $imgTag;
        }

        $altText = buildImageAltText(
            $src,
            getImageAttributeValue($imgTag, 'alt'),
            getImageAttributeValue($imgTag, 'title')
        );

        $attributes = preg_replace('/^<img\b/i', '', $imgTag);
        $attributes = preg_replace('/\/?\s*>$/', '', $attributes);
        $attributes = preg_replace('/\s(?:src|data-lazy-src|alt|title)\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $attributes);
        $attributes = trim($attributes);

        $newTag = '<img';
        if ($attributes !== '') {
            $newTag .= ' ' . $attributes;
        }
        $newTag .= ' title="' . htmlspecialchars($altText, ENT_QUOTES, 'UTF-8') . '"';
        $newTag .= ' alt="' . htmlspecialchars($altText, ENT_QUOTES, 'UTF-8') . '"';
        $newTag .= ' data-lazy-src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
        $newTag .= ' src="' . htmlspecialchars(GetLazyLoad(), ENT_QUOTES, 'UTF-8') . '">';

        return $newTag;
    }, $text);

    return $text;
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
    $cache_key = 'tags_num';
    $cache_time = Helper::options()->CacheTime ? intval(Helper::options()->CacheTime) : 3600;
    $cached_data = getCache($cache_key, $cache_time);
    
    if ($cached_data === false) {
        $db = Typecho_Db::get();
        $total_tags = $db->fetchObject($db->select(array('COUNT(mid)' => 'num'))
            ->from('table.metas')
            ->where('table.metas.type = ?', 'tag'))->num;
        
        setCache($cache_key, $total_tags);
    } else {
        $total_tags = $cached_data;
    }
    
    if ($display) {
        echo $total_tags;
    } else {
        return $total_tags;
    }
}


//获取Gravatar头像 QQ邮箱取用qq头像
function getGravatar($email, $name, $comments_a, $s = 96, $d = 'mp', $r = 'g')
{
    $avatarAlt = htmlspecialchars((trim((string)$name) !== '' ? trim((string)$name) : '访客') . ' 的头像', ENT_QUOTES, 'UTF-8');
    preg_match_all('/((\d)*)@qq.com/', $email, $vai);
    if (empty($vai['1']['0'])) {
        $url = Helper::options()->GravatarSelect;
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" alt="' . $avatarAlt . '" title="' . $avatarAlt . '">';
    } else {
        $url = 'https://cravatar.cn/avatar/'.md5(strtolower(trim($email)));
        $imga = '<img ' . $comments_a . ' src="' . GetLazyLoad() . '" data-lazy-src="' . $url . '" alt="' . $avatarAlt . '" title="' . $avatarAlt . '">';
    }
    return $imga;
}

// 获取作者头像URL (融合自定义URL与Gravatar逻辑)
function getAuthorAvatarUrl() {
    $options = Helper::options();
    $mode = $options->LogoAvatarMode;
    
    // 如果选择 Gravatar 模式且填写了邮箱
    if ($mode == 'gravatar' && !empty($options->authorGravatarEmail)) {
        $email = $options->authorGravatarEmail;
        $hash = md5(strtolower(trim($email)));
        
        // 匹配是否为 QQ 邮箱
        preg_match_all('/((\d)*)@qq.com/', $email, $vai);
        if (empty($vai['1']['0'])) {
            // 普通邮箱，调用后台选择的源
            $cdn = $options->GravatarSelect;
        } else {
            // QQ邮箱，按照你评论区的逻辑，直接强制使用 cravatar 或者是对应的QQ源
            $cdn = 'https://cravatar.cn/avatar/';
        }
        
        // 返回拼接好的链接，s=200 设置图片大小更清晰
        return $cdn . $hash . '?s=200';
    } 
    
    // 默认或未填邮箱时，返回自定义URL
    return $options->logoUrl;
}

// 获取浏览器信息
function getBrowser($agent)
{
    $browsers = [
        '/MSIE\s([^\s|;]+)/i' => ['label' => 'Internet Explorer', 'icon' => 'fab fa-internet-explorer'],
        '/FireFox\/([^\s]+)/i' => ['label' => 'FireFox', 'icon' => 'fab fa-firefox-browser'],
        '/Maxthon([\d]*)\/([^\s]+)/i' => ['label' => '遨游', 'icon' => 'iconfont icon-maxthon'],
        '#360([a-zA-Z0-9.]+)#i' => ['label' => '360极速浏览器', 'icon' => 'iconfont icon-chrome'],
        '/Edg([\d]*)\/([^\s]+)/i' => ['label' => 'Microsoft Edge', 'icon' => 'fab fa-edge'],
        '/UC/i' => ['label' => 'UC浏览器', 'icon' => 'iconfont icon-UCliulanqi'],
        '/QQ/i' => ['label' => 'QQ浏览器', 'icon' => 'iconfont icon-QQliulanqi'],
        '/QQBrowser\/([^\s]+)/i' => ['label' => 'QQ浏览器', 'icon' => 'iconfont icon-QQliulanqi'],
        '/UBrowser/i' => ['label' => 'UC浏览器', 'icon' => 'iconfont icon-UCliulanqi'],
        '/Opera[\s|\/]([^\s]+)|OPR/i' => ['label' => 'Opera', 'icon' => 'fab fa-opera'],
        '/YaBrowser/i' => ['label' => 'Yandex', 'icon' => 'fab fa-yandex-international'],
        '/Quark/i' => ['label' => 'Quark', 'icon' => 'iconfont icon-quark'],
        '/XiaoMi/i' => ['label' => '小米浏览器', 'icon' => 'iconfont icon-XiaoMi'],
        '/Chrome([\d]*)\/([^\s]+)/i' => ['label' => 'Google Chrome', 'icon' => 'fab fa-chrome'],
        '/safari\/([^\s]+)/i' => ['label' => 'Safari', 'icon' => 'fab fa-safari'],
    ];
    $defaultBrowser = ['label' => 'Google Chrome', 'icon' => 'fab fa-chrome'];
    foreach ($browsers as $pattern => $info) {
        if (preg_match($pattern, $agent, $regs)) {
            echo "<i class='{$info['icon']}'></i>&nbsp;&nbsp;{$info['label']}";
            return;
        }
    }
    echo "<i class='{$defaultBrowser['icon']}'></i>&nbsp;&nbsp;{$defaultBrowser['label']}";
}

// 获取操作系统信息
function getOs($agent) {
    $osData = [
        'Windows Vista' => ['/nt 6.0/i', 'iconfont icon-windows'],
        'Windows 7' => ['/nt 6.1/i', 'iconfont icon-windows'],
        'Windows 8' => ['/nt 6.2/i', 'fab fa-windows'],
        'Windows 8.1' => ['/nt 6.3/i', 'fab fa-windows'],
        'Windows XP' => ['/nt 5.1/i', 'iconfont icon-windows'],
        'Windows 10' => ['/nt 10.0/i', 'fab fa-windows'],
        'Windows 11' => ['/nt 11.0/i', 'fab fa-windows'],
        'Android Pie(9)' => ['/android 9/i', 'fab fa-android'],
        'Android ICS(4)' => ['/android 4/i', 'fab fa-android'],
        'Android Lollipop(5)' => ['/android 5/i', 'fab fa-android'],
        'Android M(6)' => ['/android 6/i', 'fab fa-android'],
        'Android Nougat(7)' => ['/android 7/i', 'fab fa-android'],
        'Android Oreo(8)' => ['/android 8/i', 'fab fa-android'],
        'Android Q(10)' => ['/android 10/i', 'fab fa-android'],
        'Android 11' => ['/android 11/i', 'fab fa-android'],
        'Android 12' => ['/android 12/i', 'fab fa-android'],
        'Android 13' => ['/android 13/i', 'fab fa-android'],
        'Ubuntu' => ['/ubuntu/i', 'fab fa-ubuntu'],
        'Arch Linux' => ['/Arch/i', 'iconfont icon-Arch-Linux'],
        'Manjaro' => ['/manjaro/i', 'iconfont icon-manjaro'],
        'Debian' => ['/debian/i', 'iconfont icon-debianos'],
        'Linux' => ['/linux/i', 'fab fa-linux'],
        'iOS(iPad)' => ['/iPad/i', 'fab fa-apple'],
        'iOS(iPhone)' => ['/iPhone/i', 'fab fa-apple'],
        'MacOS' => ['/mac/i', 'fab fa-apple'],
        'Android' => ['/fusion/i', 'fab fa-android'],
    ];

    foreach ($osData as $osName => list($pattern, $iconClass)) {
        if (preg_match($pattern, $agent)) {
            echo '&nbsp;&nbsp;<i class="' . $iconClass . '"></i>&nbsp;' . $osName . '&nbsp;/&nbsp;';
            return;
        }
    }

    // Default case
    echo '&nbsp;&nbsp;<i class="fab fa-linux"></i>&nbsp;&nbsp;Linux&nbsp;/&nbsp;';
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
    static $commentParentCache = array();
    $coid = (int)$coid;

    if ($coid <= 0) {
        return;
    }

    if (isset($commentParentCache[$coid])) {
        echo $commentParentCache[$coid];
        return;
    }

    $db = Typecho_Db::get();
    $prow = $db->fetchRow($db->select('parent,status')->from('table.comments')
        ->where('coid = ?', $coid)); //当前评论
    $mail = "";
    $parent = @$prow['parent'];
    $output = '';
    if ($parent != "0") { //子评论
        $arow = $db->fetchRow($db->select('author,status,mail')->from('table.comments')
            ->where('coid = ?', $parent)); //查询该条评论的父评论的信息
        @$author = @$arow['author']; //作者名称
        $mail = @$arow['mail'];
        if (@$author && $arow['status'] == "approved") { //父评论作者存在且父评论已经审核通过
            if (@$prow['status'] == "waiting") {
                $output .= '<p class="commentReview">（评论审核中）)</p>';
            }
            $output .= '<a onclick="b(this);return false;" href="#comment-' . $parent . '">@' . $author . '</a>';
        } else { //父评论作者不存在或者父评论没有审核通过
            if (@$prow['status'] == "waiting") {
                $output .= '<p class="commentReview">（评论审核中）)</p>';
            }
        }

    } else { //母评论，无需输出锚点链接
        if (@$prow['status'] == "waiting") {
            $output .= '<p class="commentReview">（评论审核中）)</p>';
        }
    }

    $commentParentCache[$coid] = $output;
    echo $output;
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
                <div class="vuser">
                    <cite class="vnick" title="<?php $comments->author; ?>">
                        <?php $comments->author(); ?>
                    </cite>
                    <?php commentRank($comments, $comments->mail); ?>
                </div>
            </div>
            <div class="vhead">
                <b>
                    <?php $parentMail = get_comment_at($comments->coid) ?>
                    <?php echo $parentMail; ?>
                </b>
                <a class="vtime" href="#comment-<?php echo $comments->coid; ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
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
    $online_log = __DIR__ . '/online.dat'; // 保存人数的文件到当前主题目录
    $timeout = 30; //30秒内没动作者,认为掉线
    $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? trim((string) $_SERVER['REMOTE_ADDR']) : trim((string) getenv('REMOTE_ADDR'));

    if ($remoteAddr === '') {
        echo 0;
        return;
    }

    if (!file_exists($online_log)) {
        $createFile = @fopen($online_log, 'w');
        if ($createFile === false) {
            echo 0;
            return;
        }
        fclose($createFile);
    }

    if (!is_readable($online_log) || !is_writable($online_log)) {
        echo 0;
        return;
    }

    $entries = @file($online_log);
    if ($entries === false) {
        echo 0;
        return;
    }

    $temp = array();
    for ($i = 0; $i < count($entries); $i++) {
        $entry = explode(",", trim($entries[$i]));
        if (count($entry) < 2) {
            continue;
        }
        if (($entry[0] != $remoteAddr) && ((int) $entry[1] > time())) {
            array_push($temp, $entry[0] . "," . $entry[1] . "\n"); //取出其他浏览者的信息,并去掉超时者,保存进$temp
        }
    }
    array_push($temp, $remoteAddr . "," . (time() + ($timeout)) . "\n"); //更新浏览者的时间
    $slzxrs = count($temp); //计算在线人数
    $entries = implode("", $temp);
    //写入文件
    $fp = @fopen($online_log, "w");
    if ($fp === false) {
        echo 0;
        return;
    }
    if (!@flock($fp, LOCK_EX)) {
        fclose($fp);
        echo 0;
        return;
    }
    fwrite($fp, $entries);
    flock($fp, LOCK_UN);
    fclose($fp);
    echo $slzxrs;
}

function only_get_post_view($archive)
{
    $cid = $archive->cid;
    if (is_object($archive) && isset($archive->views)) {
        $exist = (int)$archive->views;
    } else {
        $post = getPostBasicByCid($cid);
        $exist = $post && isset($post['views']) ? (int)$post['views'] : 0;
    }
    if ($exist >= 10000) {
        $out = sprintf('%.2f W', $exist / 10000);
    } else {
        $out = sprintf('%d', $exist);
    }
    echo $out;
}
//总访问量
function theAllViews()
{
    $site = getSiteStatistics();
    echo $site['totalViews'];
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
    $sql = $db->select('cid')->from('table.contents')
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

/**
 * 获取下一篇文章mid
 *
 * 如果没有下一篇,返回null
 */
function theNextCid($widget, $default = NULL)
{
    $db = Typecho_Db::get();
    $sql = $db->select('cid')->from('table.contents')
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

//调用博主最近文章更新时间
function get_last_update()
{
    $num = '1';
    $type = 'post';
    $status = 'publish';
    $now = time();
    $db = Typecho_Db::get();
    // $prefix = $db->getPrefix();
    $create = $db->fetchRow($db->select('created')->from('table.contents')->where('table.contents.type=? and status=?', $type, $status)->order('created', Typecho_Db::SORT_DESC)->limit($num));
    $update = $db->fetchRow($db->select('modified')->from('table.contents')->where('table.contents.type=? and status=?', $type, $status)->order('modified', Typecho_Db::SORT_DESC)->limit($num));
    if ($create >= $update && $create['created'] != 0) {
        echo Typecho_I18n::dateWord(isset($create['created']), $now);
    } else {
        $lastModified = $now - $update['modified'];
        $timeIntervals = [
            31536000 => '年',
            2592000 => '个月',
            86400 => '天',
            3600 => '小时',
            60 => '分钟',
            1 => '秒'
        ];
        foreach ($timeIntervals as $interval => $label) {
            if ($lastModified > $interval) {
                $value = floor($lastModified / $interval);
                echo $value . ' ' . $label . '前';
                break;
            }
        }
    }
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
        
        // 使用HEREDOC语法来输出JavaScript代码，便于IDE高亮和编辑
        echo <<<JS
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 获取发布按钮和保存按钮
                let publishBtn1 = document.querySelector('#b-wmd-md-submit');
                let publishBtn2 = document.querySelector('#btn-submit');
                
                // 定义清除缓存的函数
                function clearArticleCache() {
                    // 获取当前文章ID
                    var cid = document.querySelector('input[name="cid"]');
                    if (cid && cid.value) {
                        fetch('?clearCache=' + cid.value)
                            .then(response => {
                                if (response.ok) {
                                    console.log('缓存已清除');
                                }
                            })
                            .catch(error => {
                                console.error('清除缓存失败:', error);
                            });
                    }
                }
                
                // 监听第一个按钮
                if (publishBtn1) {
                    publishBtn1.addEventListener('click', clearArticleCache);
                }
                
                // 监听第二个按钮
                if (publishBtn2) {
                    publishBtn2.addEventListener('click', clearArticleCache);
                }
            });
        </script>
        JS;
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
    if (!empty($siteKey) && !empty($secretKey) && !$login) {
        echo '<script src="https://recaptcha.net/recaptcha/api.js" async defer data-no-instant></script>
                              <div class="g-recaptcha" data-sitekey=' . $siteKey . '></div>';
    }
    if (!empty(Helper::options()->hcaptchaSecretKey) && !empty(Helper::options()->hcaptchaAPIKey) && !$login) {
        echo '
            <div id="h-captcha" class="h-captcha" data-sitekey=' . Helper::options()->hcaptchaSecretKey . '></div>';
    }
    if (!empty(Helper::options()->turnstileSiteKey) && !empty(Helper::options()->turnstileKey) && !$login) {
        echo '
        <div style="margin-top: 10px;" class="cf-turnstile" id="cf-turnstile" 
        data-sitekey=' . Helper::options()->turnstileSiteKey . '
        data-theme="auto"></div>
        <script data-pjax>
        (() => {
            const state = window.__butterflyTurnstileState || (window.__butterflyTurnstileState = {
                widgetId: null,
                observer: null,
                scriptLoading: false
            });

            window.initTurnstileWidget = function() {
                const target = document.getElementById("cf-turnstile");
                if (!target || typeof turnstile === "undefined" || typeof turnstile.render !== "function") {
                    return;
                }

                const theme = document.documentElement.getAttribute("data-theme") || "auto";

                if (state.widgetId !== null) {
                    try {
                        turnstile.remove(state.widgetId);
                    } catch (error) {}
                    state.widgetId = null;
                }

                target.innerHTML = "";
                state.widgetId = turnstile.render("#cf-turnstile", {
                    sitekey: "' . Helper::options()->turnstileSiteKey . '",
                    theme: theme
                });
            };

            window.loadTurnstile = function() {
                if (!document.getElementById("cf-turnstile")) {
                    return;
                }

                if (typeof turnstile !== "undefined") {
                    window.initTurnstileWidget();
                    return;
                }

                if (state.scriptLoading) {
                    return;
                }

                const existingScript = document.querySelector("script[data-turnstile-api=\"true\"]");
                if (existingScript) {
                    state.scriptLoading = true;
                    existingScript.addEventListener("load", function handleTurnstileLoad() {
                        existingScript.removeEventListener("load", handleTurnstileLoad);
                        state.scriptLoading = false;
                        window.initTurnstileWidget();
                    });
                    return;
                }

                state.scriptLoading = true;
                const script = document.createElement("script");
                script.src = "https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit";
                script.async = true;
                script.defer = true;
                script.dataset.turnstileApi = "true";
                script.onload = function() {
                    state.scriptLoading = false;
                    window.initTurnstileWidget();
                };
                script.onerror = function() {
                    state.scriptLoading = false;
                };
                document.head.appendChild(script);
            };

            window.setupThemeObserver = function() {
                if (state.observer) {
                    return;
                }

                state.observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === "attributes" && mutation.attributeName === "data-theme" && document.getElementById("cf-turnstile")) {
                            window.initTurnstileWidget();
                        }
                    });
                });

                state.observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ["data-theme"]
                });
            };

            window.loadTurnstile();
            window.setupThemeObserver();
        })();
        </script>
        ';
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

function turnstile_filter($comment){
    if (isset($_REQUEST['text']) != null) {
        if ($_POST['cf-turnstile-response'] == null) {
            throw new Typecho_Widget_Exception(_t('人机验证失败,确认你加载了Cloudflare Turnstile人机验证并通过验证'));
        } else {
            if (isset($_POST['cf-turnstile-response']) && !empty($_POST['cf-turnstile-response'])) {
                $secret = Helper::options()->turnstileKey;
                $cfResponse = $_POST['cf-turnstile-response'];
                $ip = $_SERVER['REMOTE_ADDR'];
                $header = array(
                    "Content-Type: application/x-www-form-urlencoded"
                );
                $params = array('secret' => $secret, 'response' => $cfResponse, 'remoteip' => $ip);
                $verifyResponse = restFull('https://challenges.cloudflare.com/turnstile/v0/siteverify',$params, 'POST', $header);
                $responseData = json_decode($verifyResponse,true);
                $isSuccess = $responseData['success'];
                if ($isSuccess) {
                    return $comment;
                } else {
                    $errorCodes = $responseData['error-codes'][0];
                    switch ($errorCodes) {
                        case 'timeout-or-duplicate':
                            throw new Typecho_Widget_Exception(_t('验证超时或连续重复发言,请刷新重新评论'));
                            break;
                        case 'invalid-input-secret':
                            throw new Typecho_Widget_Exception(_t('网站管理员填了无效的siteKey或者secretKey...'));
                            break;
                        case 'bad-request':
                            throw new Typecho_Widget_Exception(_t('请求错误！请检查网络'));
                            break;
                        default:
                            throw new Typecho_Widget_Exception($errorCodes);
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
    $headers = array(
        'referer: https://weibo.com/newlogin?tabtype=list&openLoginLayer=0&url=https://weibo.com/',
    );
    $api = restFull('https://weibo.com/ajax/side/hotSearch', array(), 'GET', $headers);
    $result = json_decode($api, true);
    $data = isset($result['data']['realtime']) && is_array($result['data']['realtime']) ? $result['data']['realtime'] : array();

    $jyzy = array(
        '电影' => '影',
        '剧集' => '剧',
        '综艺' => '综',
        '音乐' => '音',
        '演出' => '演',
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
        '演' => 'weibo-jyzy',
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
        if (!empty($item['flag_desc']) && isset($jyzy[$item['flag_desc']])) {
            $hot = $jyzy[$item['flag_desc']];
        }
        $hotClass = isset($hotness[$hot]) ? $hotness[$hot] : 'weibo-recommend';
        echo '<div class="weibo-list-item"><div class="weibo-hotness ' . $hotClass . '">' . $hot . '</div><span class="weibo-title"><a title="' . $item['note'] . '" href="https://s.weibo.com/weibo?q=%23' . $item['word'] . '%23" target="_blank" rel="external nofollow noreferrer" style="color:#a08ed5">' . $item['note'] . '</a></span><div class="weibo-num"><span>' . $item['num'] . '</span></div></div>';
    }
}

// 自定义文章摘要
function summaryContent($widget)
{
    $summaryContent = '';
    $customSummary = isset($widget->cid) ? getThemeFieldValue($widget->cid, 'summaryContent', '') : '';
    if ($customSummary) {
        $summaryContent = $customSummary;
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
    $noCover = isset($widget->cid) ? getThemeFieldValue($widget->cid, 'NoCover', 'on') : 'on';
    if ($noCover == "off") {
        return false;
    }
    return true;
}

function cdnBaseUrl(){
    $StaticFile = Helper::options()->StaticFile;
    $CDNURL = Helper::options()->CDNURL;
    if($StaticFile == 'CDN' && $CDNURL == ''){
        echo 'https://' . Helper::options()->jsdelivrLink . '/gh/wehaox/CDN@main/butterfly';
    }
    elseif($StaticFile == 'CDN' && $CDNURL != ''){
        echo $CDNURL;
    }
    else{
        echo Helper::options()->themeUrl . '/static';
    }
}

function darkTimeFunc(){
    $time = Helper::options()->darkTime;
    if(empty($time)){
        $time = "20-7";
    }
    $timeSlot = explode('-', $time);
    echo "e >= $timeSlot[0] || e <= $timeSlot[1]";
}

// 优化文章详情获取，添加缓存
function get_post_details($archive)
{
    $cid = $archive->cid;
    $cache_key = 'post_details_' . $cid;
    
    // 对于浏览量，我们不缓存，因为需要实时更新
    // 但对于文章内容分析，可以缓存
    $content_cache_key = 'post_content_' . $cid;
    $content_details = getCache($content_cache_key, 86400); // 缓存一天
    
    $db = Typecho_Db::get();
    $row = $db->fetchRow($db->select('text', 'views')->from('table.contents')->where('cid = ?', $cid)->limit(1));
    $views = (int)$row['views'];
    
    if ($content_details === false) {
        $text = $row['text'];
        $total_length = mb_strlen($text, 'UTF-8');
        $chinese_text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $text);
        $chinese_length = mb_strlen($chinese_text, 'utf-8');
        $reading_time = ceil($chinese_length / 400);
        
        $content_details = [
            'total_length' => $total_length,
            'chinese_length' => $chinese_length,
            'reading_time' => $reading_time
        ];
        
        setCache($content_cache_key, $content_details);
    }

    if ($archive->is('single')) {
        $cookie = Typecho_Cookie::get('contents_views');
        $cookie = $cookie ? explode(',', $cookie) : array();

        if (!in_array($cid, $cookie)) {
            $db->query($db->update('table.contents')
                ->rows(array('views' => $views + 1))
                ->where('cid = ?', $cid));
            $views += 1;
            array_push($cookie, $cid);
            $cookie = implode(',', $cookie);
            Typecho_Cookie::set('contents_views', $cookie);
        }
    }

    return array_merge($content_details, ['views' => $views]);
}

// 优化站点统计函数，合并多个查询并添加缓存
function getSiteStatistics()
{
    $cache_key = 'site_statistics';
    $cache_time = Helper::options()->CacheTime ? intval(Helper::options()->CacheTime) : 86400;
    $cached_data = getCache($cache_key, $cache_time);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    $db = Typecho_Db::get();
    $now = time();

    // 合并查询，获取所有需要的信息
    $query = $db->select(
        ['SUM(LENGTH(text))' => 'totalChars', 'SUM(views)' => 'totalViews', 'MAX(created)' => 'latestCreate', 'MAX(modified)' => 'latestModify']
    )->from('table.contents')->where('table.contents.status = ?', 'publish')->where('table.contents.type = ?', 'post');

    $result = $db->fetchRow($query);

    // 计算字符数并添加单位
    $chars = $result['totalChars'];
    $unit = '';
    if ($chars >= 10000) {
        $chars /= 10000;
        $unit = 'W';
    } elseif ($chars >= 1000) {
        $chars /= 1000;
        $unit = 'K';
    }
    $charCount = sprintf('%.2lf %s', $chars, $unit);

    // 获取总浏览次数
    $totalViews = $result['totalViews'];

    // 获取最后更新信息
    $latestCreate = $result['latestCreate'];
    $latestModify = $result['latestModify'];
    $lastUpdate = '';

    if ($latestCreate >= $latestModify && $latestCreate) {
        $lastUpdate = Typecho_I18n::dateWord($latestCreate, $now);
    } else {
        $lastModified = $now - $latestModify;
        $timeIntervals = [
            31536000 => '年',
            2592000 => '个月',
            86400 => '天',
            3600 => '小时',
            60 => '分钟',
            1 => '秒'
        ];
        foreach ($timeIntervals as $interval => $label) {
            if ($lastModified > $interval) {
                $value = floor($lastModified / $interval);
                $lastUpdate = $value . ' ' . $label . '前';
                break;
            }
        }
    }

    $stat = Typecho_Widget::widget('Widget_Stat');
    $publishedPostsNum = $stat->publishedPostsNum;
    $categoriesNum = $stat->categoriesNum;

    $tagsNum = tagsNum(false);
    
    // 返回结果数组
    $result = [
        'charCount' => $charCount,
        'totalViews' => $totalViews,
        'lastUpdate' => $lastUpdate,
        'publishedPostsNum' => $publishedPostsNum,
        'categoriesNum' => $categoriesNum,
        'tagsNum' => $tagsNum,
    ];
    
    setCache($cache_key, $result);
    return $result;
}

function getThemeVersion()
{
  $version = Typecho_Plugin::parseInfo(Helper::options()->themeFile(Helper::options()->theme, "index.php"))["version"];
  return $version;
}

// 请求封装
function restFull($url, $params, $method, $header = array(), $timeout = 5, $cookie = null)
{
  // 确保 header 是数组
  if (!is_array($header)) {
    $header = array();
  }

  if($timeout == null){
    $timeout = 5;
  }

  // 设置基础 cURL 选项
  $opts = array(
    CURLOPT_TIMEOUT => $timeout,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => $header
  );

  // 如果有 cookie，添加到选项中
  if ($cookie !== null) {
    $opts[CURLOPT_COOKIE] = $cookie;
  }

  // 根据请求类型设置特定参数
  switch (strtoupper($method)) {
    case 'GET':
      $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
      break;
    case 'POST':
      $opts[CURLOPT_URL] = $url;
      $opts[CURLOPT_POST] = 1;
      $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
      break;
    case 'DELETE':
      $opts[CURLOPT_URL] = $url;
      $header[] = "X-HTTP-Method-Override: DELETE";
      $opts[CURLOPT_HTTPHEADER] = $header;
      $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
      $opts[CURLOPT_POSTFIELDS] = $params;
      break;
    case 'PUT':
      $opts[CURLOPT_URL] = $url;
      $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
      $opts[CURLOPT_POSTFIELDS] = $params;
      break;
    default:
      throw new Exception('不支持的请求方式！');
  }

  // 初始化并执行 cURL 请求
  $ch = curl_init();
  curl_setopt_array($ch, $opts);
  $data = curl_exec($ch);
  $error = curl_error($ch);
  curl_close($ch);
  if ($error) {
    return "error: " . $error;
  } else {
    return $data;
  }
}

// 添加缓存清理函数，可以在文章更新、评论发布等操作后调用
function clearCache($key = null) {
    if (Helper::options()->EnableCache != 'file') {
        return;
    }

    if (!ensureFileCacheDirectoryAvailable()) {
        return;
    }

    if ($key) {
        $cache_file = getCacheDirectoryPath() . md5($key) . '.cache';
        if (file_exists($cache_file)) {
            @unlink($cache_file);
        }
    } else {
        // 清除所有缓存
        $cache_dir = getCacheDirectoryPath();
        if (is_dir($cache_dir)) {
            $files = glob($cache_dir . '*.cache');
            foreach ($files as $file) {
                @unlink($file);
            }
        }
    }
}

// 在文章保存后清除相关缓存
function clearCacheOnSave($contents, $edit) {
    // 清除全站统计相关缓存
    clearCache('all_characters');
    clearCache('site_statistics');
    clearCache('tags_num');
    clearCache('all_views');
    
    // 如果是编辑现有文章
    if (isset($edit->cid)) {
        // 清除该文章所有相关缓存
        $cid = $edit->cid;
        clearCache('post_content_' . $cid);
        clearCache('post_full_content_' . $cid);
        clearCache('post_parsed_content_' . $cid);
        clearCache('post_parsed_content_v2_' . $cid);
        clearCache('post_details_' . $cid);
        clearCache('post_basic_' . $cid);
        clearCache('post_thumb_' . $cid);
        clearCache('theme_fields_' . $cid);
        
        // 清除不同用户状态的缓存版本
        clearCache('post_parsed_content_' . $cid . '_logged_in');
        clearCache('post_parsed_content_' . $cid . '_guest');
        clearCache('post_parsed_content_v2_' . $cid . '_logged_in');
        clearCache('post_parsed_content_v2_' . $cid . '_guest');
        
        // 清除评论用户的缓存版本
        $db = Typecho_Db::get();
        $commenters = $db->fetchAll($db->select('DISTINCT mail')->from('table.comments')
            ->where('cid = ?', $cid));
        foreach ($commenters as $commenter) {
            if ($commenter['mail']) {
                clearCache('post_parsed_content_' . $cid . '_' . md5($commenter['mail']));
                clearCache('post_parsed_content_' . $cid . '_' . md5($commenter['mail']) . '_commented');
                clearCache('post_parsed_content_v2_' . $cid . '_' . md5($commenter['mail']));
                clearCache('post_parsed_content_v2_' . $cid . '_' . md5($commenter['mail']) . '_commented');
            }
        }
    }
    
    return $contents;
}

// 在评论发布后清除相关缓存
function clearCacheOnComment($comment) {
    $cid = 0;
    $mail = '';

    if (is_object($comment)) {
        $cid = isset($comment->cid) ? (int)$comment->cid : 0;
        $mail = isset($comment->mail) ? $comment->mail : '';
    } elseif (is_array($comment)) {
        $cid = isset($comment['cid']) ? (int)$comment['cid'] : 0;
        $mail = isset($comment['mail']) ? $comment['mail'] : '';
    }

    if ($cid > 0) {
        // 清除文章详情缓存
        clearCache('post_details_' . $cid);
        clearCache('post_basic_' . $cid);
        clearCache('post_thumb_' . $cid);
        
        // 清除该评论者相关的文章内容缓存
        if ($mail) {
            clearCache('post_parsed_content_' . $cid . '_' . md5($mail));
            clearCache('post_parsed_content_' . $cid . '_' . md5($mail) . '_commented');
            clearCache('post_parsed_content_v2_' . $cid . '_' . md5($mail));
            clearCache('post_parsed_content_v2_' . $cid . '_' . md5($mail) . '_commented');
        }
        
        // 清除访客版本的缓存
        clearCache('post_parsed_content_' . $cid . '_guest');
        clearCache('post_parsed_content_v2_' . $cid . '_guest');

        // 切换评论缓存版本
        bumpCommentsCacheVersion($cid);
    }
    return $comment;
}

function getCommentsCacheVersion($cid)
{
    $cid = (int)$cid;
    if ($cid <= 0) {
        return '0';
    }

    $key = 'comments_version_' . $cid;
    $version = getCache($key, 31536000);
    if ($version === false) {
        $version = '1';
        setCache($key, $version);
    }

    return (string)$version;
}

function bumpCommentsCacheVersion($cid)
{
    $cid = (int)$cid;
    if ($cid > 0) {
        $registryKey = 'comments_list_registry_' . $cid;
        $registry = getCache($registryKey, 31536000);
        if (is_array($registry)) {
            foreach ($registry as $cacheKey) {
                clearCache($cacheKey);
            }
        }
        clearCache($registryKey);
        setCache('comments_version_' . $cid, (string)microtime(true));
    }
}

function registerCommentsCacheKey($cid, $cacheKey)
{
    $cid = (int)$cid;
    if ($cid <= 0 || empty($cacheKey)) {
        return;
    }

    $registryKey = 'comments_list_registry_' . $cid;
    $registry = getCache($registryKey, 31536000);
    if (!is_array($registry)) {
        $registry = array();
    }

    if (!in_array($cacheKey, $registry, true)) {
        $registry[] = $cacheKey;
        setCache($registryKey, $registry);
    }
}

function getCommentsCacheIdentity($archive)
{
    $user = Typecho_Widget::widget('Widget_User');
    $author = trim((string)$archive->remember('author', true));
    $mail = trim((string)$archive->remember('mail', true));
    $uid = $user->hasLogin() ? (string)$user->uid : '0';

    if ($author !== '' || $mail !== '' || $uid !== '0') {
        return 'viewer_' . md5($uid . '|' . $author . '|' . $mail);
    }

    return 'guest_public';
}

function getCommentsCurrentPage($archive)
{
    if (isset($archive->parameter) && isset($archive->parameter->commentPage)) {
        return (int)$archive->parameter->commentPage;
    }

    if (isset($archive->request)) {
        return (int)$archive->request->filter('int')->get('commentPage');
    }

    return 0;
}

function buildCommentsListHtml($archive)
{
    ob_start();
    $archive->comments()->to($comments);
    if ($comments->have()) :
?>
        <h3><?php $archive->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h3>
        <?php $comments->listComments(); ?>
        <?php $comments->pageNav('<i class="fas fa-chevron-left fa-fw"></i>', '<i class="fas fa-chevron-right fa-fw"></i>', 1, '...', array('wrapTag' => 'ol', 'wrapClass' => 'page-navigator', 'itemTag' => '', 'prevClass' => 'prev', 'nextClass' => 'next', 'currentClass' => 'current')); ?>
<?php
    endif;
    return ob_get_clean();
}

function getCachedCommentsListHtml($archive)
{
    if (Helper::options()->EnableCache != 'file') {
        return buildCommentsListHtml($archive);
    }

    $cid = (int)$archive->cid;
    $page = getCommentsCurrentPage($archive);
    $version = getCommentsCacheVersion($cid);
    $identity = getCommentsCacheIdentity($archive);
    $cache_key = 'comments_list_' . $cid . '_p' . $page . '_' . $version . '_' . $identity;
    $cache_time = Helper::options()->CacheTime ? intval(Helper::options()->CacheTime) : 86400;
    $cached = getCache($cache_key, $cache_time);

    if ($cached !== false) {
        return $cached;
    }

    $html = buildCommentsListHtml($archive);
    registerCommentsCacheKey($cid, $cache_key);
    setCache($cache_key, $html);
    return $html;
}

// 优化缓存文章内容函数
function getPostContent($cid) {
    // 检查是否启用缓存
    if (Helper::options()->EnableCache != 'file') {
        $db = Typecho_Db::get();
        $row = $db->fetchRow($db->select('text')->from('table.contents')->where('cid = ?', $cid));
        return $row ? $row['text'] : null;
    }
    
    $cache_key = 'post_full_content_' . $cid;
    $cache_time = Helper::options()->CacheTime ? intval(Helper::options()->CacheTime) : 86400;
    $cached_data = getCache($cache_key, $cache_time);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    $db = Typecho_Db::get();
    $row = $db->fetchRow($db->select('text')->from('table.contents')->where('cid = ?', $cid));
    
    if ($row) {
        $content = $row['text'];
        setCache($cache_key, $content);
        return $content;
    }
    
    return null;
}

// 重写评论显示函数
function getCachedPostContent($archive) {
    $cid = $archive->cid;
    
    // 检查是否启用缓存
    if (Helper::options()->EnableCache != 'file') {
        // 直接处理内容，不使用缓存
        return processPostContent($archive);
    }
    
    // 检查用户登录状态
    $user = Typecho_Widget::widget('Widget_User');
    $isLoggedIn = $user->hasLogin();
    
    $userEmail = $archive->remember('mail', true);
    
    // 根据不同状态使用不同的缓存键
    $cache_key = 'post_parsed_content_v2_' . $cid;
    if ($isLoggedIn) {
        $cache_key .= '_logged_in';
    } elseif ($userEmail) {
        $cache_key .= '_' . md5($userEmail);
    } else {
        $cache_key .= '_guest';
    }
    
    // 添加额外的缓存标识，确保不同状态下的内容都被缓存
    $hasCommented = false;
    if ($userEmail) {
        $db = Typecho_Db::get();
        $sql = $db->select('coid')->from('table.comments')
            ->where('cid = ?', $cid)
            ->where('mail = ?', $userEmail)
            ->limit(1);
        $result = $db->fetchRow($sql);
        $hasCommented = !empty($result);
        
        if ($hasCommented) {
            $cache_key .= '_commented';
        }
    }
    
    $cache_time = Helper::options()->CacheTime ? intval(Helper::options()->CacheTime) : 86400;
    $cached_data = getCache($cache_key, $cache_time);
    
    if ($cached_data !== false) {
        return $cached_data;
    }
    
    // 处理内容
    $content = replaceHideContent($archive->content, $isLoggedIn || $hasCommented);
    
    // 缓存处理后的内容
    setCache($cache_key, $content);
    return $content;
}

// 处理文章内容的辅助函数
function processPostContent($archive) {
    $cid = $archive->cid;
    
    // 检查用户登录状态
    $user = Typecho_Widget::widget('Widget_User');
    $isLoggedIn = $user->hasLogin();
    
    // 检查用户是否已评论
    $hasCommented = false;
    $userEmail = $archive->remember('mail', true);
    if ($userEmail) {
        $db = Typecho_Db::get();
        $sql = $db->select('coid')->from('table.comments')
            ->where('cid = ?', $cid)
            ->where('mail = ?', $userEmail)
            ->limit(1);
        $result = $db->fetchRow($sql);
        $hasCommented = !empty($result);
    }
    
    // 根据用户状态处理隐藏内容
    $content = replaceHideContent($archive->content, $isLoggedIn || $hasCommented);
    
    return $content;
}

class ThemeCacheHooks
{
    public static function handleSave($contents, $edit)
    {
        return clearCacheOnSave($contents, $edit);
    }

    public static function handleComment($comment)
    {
        return clearCacheOnComment($comment);
    }

    public static function handleCommentEdit($comment)
    {
        return clearCacheOnComment($comment);
    }

    public static function handleCommentMark($comment, $edit, $status)
    {
        return clearCacheOnComment($comment);
    }
}

Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishPublish = array('ThemeCacheHooks', 'handleSave');
Typecho_Plugin::factory('Widget_Contents_Page_Edit')->finishPublish = array('ThemeCacheHooks', 'handleSave');
Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('ThemeCacheHooks', 'handleComment');
Typecho_Plugin::factory('Widget_Comments_Edit')->finishComment = array('ThemeCacheHooks', 'handleCommentEdit');
Typecho_Plugin::factory('Widget_Comments_Edit')->mark = array('ThemeCacheHooks', 'handleCommentMark');

// Typecho_Plugin::factory('Widget_Contents_Post_Edit')->finishPublish = array('ThemeCacheCleaner', 'clearCache');
// Typecho_Plugin::factory('Widget_Contents_Page_Edit')->finishPublish = array('ThemeCacheCleaner', 'clearCache');
// // Typecho_Plugin::factory('Widget_Feedback')->finishComment = 'clearCacheOnComment';

// class ThemeCacheCleaner {
//     public static function clearCache() {
//         clearCache();
//     }
// }
