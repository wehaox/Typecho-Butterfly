<?php

if (!defined('__TYPECHO_ROOT_DIR__')) {
    $currentDir = __DIR__;
    $rootDir = '';

    while ($currentDir !== dirname($currentDir)) {
        if (file_exists($currentDir . '/config.inc.php')) {
            $rootDir = $currentDir;
            break;
        }
        $currentDir = dirname($currentDir);
    }

    if ($rootDir === '') {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array('status' => 'error', 'message' => 'Typecho bootstrap failed'));
        exit;
    }

    require_once $rootDir . '/config.inc.php';

    if (class_exists('\\Widget\\Init')) {
        \Widget\Init::alloc();
    } else {
        Typecho_Widget::widget('Widget_Init');
    }
}

function routeApiRequest($apiPath)
{
    // 定义路由表，键为路径，值为处理函数名称
    $routes = [
        'getPosts' => 'getPosts',
        'getPost' => 'getPost',
        'weibohot' => 'getWeiboHot'
    ];

    // 提取路径中的第一个部分作为 action
    $parts = explode('/', $apiPath);
    $action = isset($parts[0]) ? filter_var($parts[0], FILTER_SANITIZE_SPECIAL_CHARS) : '';

    // 查找对应的处理函数
    if (isset($routes[$action])) {
        $handler = $routes[$action];

        // 如果是 getPost 路由，处理参数
        if ($action === 'getPost') {
            $cid = isset($parts[1]) ? filter_var($parts[1], FILTER_SANITIZE_NUMBER_INT) : null;
            call_user_func($handler, $cid);
        } else {
            call_user_func($handler);
        }
    } else {
        responseJson(['status' => 'error', 'message' => 'Unknown action']);
    }
}

function getPosts()
{
    $db = Typecho_Db::get();
    $posts = $db->fetchAll($db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'status', 'authorId', 'commentsNum', 'views')->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->order('created', Typecho_Db::SORT_DESC)
        ->limit(50));

    responseJson(['status' => 'success', 'data' => $posts]);
}

function getPost($cid)
{
    if ($cid) {
        $db = Typecho_Db::get();
        $post = $db->fetchRow($db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'status', 'text', 'authorId', 'commentsNum', 'views')->from('table.contents')
            ->where('cid = ?', $cid));

        if ($post) {
            responseJson(['status' => 'success', 'data' => $post]);
        } else {
            responseJson(['status' => 'error', 'message' => 'Post not found']);
        }
    } else {
        responseJson(['status' => 'error', 'message' => 'Invalid post ID']);
    }
}

function requestWeiboHotData()
{
    $headers = array(
        'Referer: https://weibo.com/newlogin?tabtype=list&openLoginLayer=0&url=https://weibo.com/',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
        'Accept: application/json, text/plain, */*'
    );

    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://weibo.com/ajax/side/hotSearch',
        CURLOPT_TIMEOUT => 3,
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    ));

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error || $response === false) {
        return array();
    }

    $result = json_decode($response, true);
    return isset($result['data']['realtime']) && is_array($result['data']['realtime']) ? $result['data']['realtime'] : array();
}

function normalizeOptionList($value)
{
    if (is_array($value)) {
        return $value;
    }

    if ($value === null || $value === '') {
        return array();
    }

    if (is_string($value)) {
        return array_map('trim', explode(',', $value));
    }

    return (array)$value;
}

function isWeiboHotEnabled()
{
    $options = Helper::options();
    $sidebarBlock = normalizeOptionList(isset($options->sidebarBlock) ? $options->sidebarBlock : array());
    $postSidebarBlock = normalizeOptionList(isset($options->PostSidebarBlock) ? $options->PostSidebarBlock : array());

    return in_array('ShowWeiboHot', $sidebarBlock, true) || in_array('ShowWeiboHot', $postSidebarBlock, true);
}

function renderWeiboHotHtml($data)
{
    if (empty($data)) {
        return '<div class="weibo-list-item"><span class="weibo-title">微博热搜暂时不可用</span></div>';
    }

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

    $html = '';

    foreach ($data as $item) {
        if (isset($item['is_ad'])) {
            continue;
        }

        $hot = '荐';
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
        $note = htmlspecialchars(isset($item['note']) ? (string)$item['note'] : '', ENT_QUOTES, 'UTF-8');
        $word = isset($item['word']) ? (string)$item['word'] : '';
        $num = htmlspecialchars(isset($item['num']) ? (string)$item['num'] : '', ENT_QUOTES, 'UTF-8');
        $url = 'https://s.weibo.com/weibo?q=' . rawurlencode('#' . $word . '#');

        $html .= '<div class="weibo-list-item"><div class="weibo-hotness ' . $hotClass . '">' . $hot . '</div><span class="weibo-title"><a title="' . $note . '" href="' . $url . '" target="_blank" rel="external nofollow noreferrer" style="color:#a08ed5">' . $note . '</a></span><div class="weibo-num"><span>' . $num . '</span></div></div>';
    }

    if ($html === '') {
        return '<div class="weibo-list-item"><span class="weibo-title">微博热搜暂时不可用</span></div>';
    }

    return $html;
}

function getWeiboHot()
{
    if (!isWeiboHotEnabled()) {
        if (function_exists('http_response_code')) {
            http_response_code(403);
        }

        responseJson(array(
            'status' => 'error',
            'message' => '微博热搜功能未开启'
        ));
    }

    $data = requestWeiboHotData();
    responseJson(array(
        'status' => 'success',
        'data' => array(
            'html' => renderWeiboHotHtml($data)
        )
    ));
}

function responseJson($data)
{
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $action = isset($_GET['action']) ? trim((string)$_GET['action']) : '';
    routeApiRequest($action);
}

?>
