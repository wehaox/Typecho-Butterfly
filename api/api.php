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
        'search' => 'searchPosts',
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

function getSearchProtectionConfig()
{
    return array(
        'min_keyword_length' => 2,
        'max_keywords' => 3,
        'max_limit' => 10,
        'cache_ttl' => 180,
        'rate_window' => 10,
        'rate_limit' => 15,
        'query_too_short_message' => '请输入至少 2 个字的关键词',
        'rate_limit_message' => '搜索过于频繁，请稍后再试',
        'service_unavailable_message' => '搜索服务暂时不可用，请稍后再试',
    );
}

function logSearchProtectionError($message)
{
    if (is_string($message) && $message !== '') {
        error_log('[butterfly-search] ' . $message);
    }
}

function getSearchRequestRawQuery()
{
    $keywordKeys = array('keywords', 'keyword', 'q', 's');

    foreach ($keywordKeys as $key) {
        if (!isset($_GET[$key])) {
            continue;
        }

        $rawKeywords = trim((string)$_GET[$key]);
        // 限制关键词长度，防止 ReDoS 和资源耗尽
        if (function_exists('mb_substr')) {
            $rawKeywords = mb_substr($rawKeywords, 0, 200, 'UTF-8');
        } else {
            $rawKeywords = substr($rawKeywords, 0, 200);
        }
        if ($rawKeywords !== '') {
            return $rawKeywords;
        }
    }

    return '';
}

function getSearchRequestKeywords()
{
    $config = getSearchProtectionConfig();
    $rawKeywords = getSearchRequestRawQuery();

    if ($rawKeywords === '') {
        return array();
    }

    $parts = preg_split('/[\s,，]+/u', preg_replace('/\s+/u', ' ', $rawKeywords));
    $keywords = array();

    foreach ($parts as $part) {
        $keyword = trim((string)$part);
        if ($keyword === '') {
            continue;
        }

        if (function_exists('mb_substr')) {
            $keyword = mb_substr($keyword, 0, 32, 'UTF-8');
        } else {
            $keyword = substr($keyword, 0, 32);
        }

        if (searchTextLength($keyword) < (int)$config['min_keyword_length']) {
            continue;
        }

        $keywords[$keyword] = $keyword;
        if (count($keywords) >= (int)$config['max_keywords']) {
            break;
        }
    }

    return array_values($keywords);
}

function hasInvalidSearchKeywords($rawQuery)
{
    $config = getSearchProtectionConfig();
    $rawQuery = trim((string)$rawQuery);

    if ($rawQuery === '') {
        return false;
    }

    $parts = preg_split('/[\s,，]+/u', preg_replace('/\s+/u', ' ', $rawQuery));
    foreach ($parts as $part) {
        $keyword = trim((string)$part);
        if ($keyword === '') {
            continue;
        }

        if (searchTextLength($keyword) < (int)$config['min_keyword_length']) {
            return true;
        }
    }

    return false;
}

function getSearchRequestLimit()
{
    $config = getSearchProtectionConfig();
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
    // 限制范围在 1-100 之间
    $limit = max(1, min(100, $limit));
    if ($limit <= 0) {
        $limit = 20;
    }

    return min($limit, (int)$config['max_limit']);
}

function getSearchStorageDirectory()
{
    static $directory = null;

    if ($directory !== null) {
        return $directory;
    }

    $candidates = array(
        rtrim(__TYPECHO_ROOT_DIR__, '/\\') . '/usr/cache/butterfly-search-api',
        rtrim(sys_get_temp_dir(), '/\\') . '/butterfly-search-api'
    );

    foreach ($candidates as $candidate) {
        if (is_dir($candidate) || @mkdir($candidate, 0755, true)) {
            $directory = $candidate;
            return $directory;
        }
    }

    $directory = false;
    return $directory;
}

function requireSearchStorageDirectory()
{
    $directory = getSearchStorageDirectory();
    if ($directory) {
        return $directory;
    }

    logSearchProtectionError('search protection storage unavailable');
    return false;
}

function readSearchStoragePayload($file)
{
    if (!is_string($file) || $file === '' || !is_file($file)) {
        return null;
    }

    $raw = @file_get_contents($file);
    if ($raw === false || $raw === '') {
        return null;
    }

    $payload = json_decode($raw, true);
    return is_array($payload) ? $payload : null;
}

function writeSearchStoragePayload($file, $payload)
{
    if (!is_string($file) || $file === '') {
        return false;
    }

    $encoded = json_encode($payload);
    if ($encoded === false) {
        logSearchProtectionError('failed to encode search storage payload for ' . $file);
        return false;
    }

    if (@file_put_contents($file, $encoded, LOCK_EX) === false) {
        logSearchProtectionError('failed to write search storage payload to ' . $file);
        return false;
    }

    return true;
}

function buildSearchCacheKey($keywords, $limit)
{
    return 'search:' . md5(json_encode(array(
        'keywords' => array_values($keywords),
        'limit' => (int)$limit,
    )));
}

function getCachedSearchResponse($cacheKey)
{
    $directory = requireSearchStorageDirectory();
    if (!$directory) {
        return null;
    }

    $file = $directory . '/cache_' . md5($cacheKey) . '.json';
    $payload = readSearchStoragePayload($file);
    if (!is_array($payload) || !isset($payload['expires_at'])) {
        return null;
    }

    if ((int)$payload['expires_at'] < time()) {
        @unlink($file);
        return null;
    }

    return isset($payload['data']) && is_array($payload['data']) ? $payload['data'] : null;
}

function cacheSearchResponse($cacheKey, $data, $ttl)
{
    $directory = requireSearchStorageDirectory();
    if (!$directory) {
        return false;
    }

    $file = $directory . '/cache_' . md5($cacheKey) . '.json';
    return writeSearchStoragePayload($file, array(
        'expires_at' => time() + max(1, (int)$ttl),
        'data' => $data,
    ));
}

function getSearchRequestClientIp()
{
    $cfIp = isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? trim((string)$_SERVER['HTTP_CF_CONNECTING_IP']) : '';
    if ($cfIp !== '' && filter_var($cfIp, FILTER_VALIDATE_IP)) {
        return $cfIp;
    }

    $remoteAddr = isset($_SERVER['REMOTE_ADDR']) ? trim((string)$_SERVER['REMOTE_ADDR']) : '';
    if ($remoteAddr !== '' && filter_var($remoteAddr, FILTER_VALIDATE_IP)) {
        return $remoteAddr;
    }

    return 'unknown';
}

function consumeSearchRateLimit(&$retryAfter, &$storageUnavailable)
{
    $config = getSearchProtectionConfig();
    $directory = requireSearchStorageDirectory();
    $retryAfter = 0;
    $storageUnavailable = false;

    if (!$directory) {
        $storageUnavailable = true;
        return false;
    }

    $window = max(1, (int)$config['rate_window']);
    $limit = max(1, (int)$config['rate_limit']);
    $now = time();
    $file = $directory . '/rate_' . md5(getSearchRequestClientIp()) . '.json';
    $handle = @fopen($file, 'c+');
    if ($handle === false) {
        logSearchProtectionError('failed to open rate limit file ' . $file);
        $storageUnavailable = true;
        return false;
    }

    $allowed = false;

    if (!flock($handle, LOCK_EX)) {
        fclose($handle);
        logSearchProtectionError('failed to lock rate limit file ' . $file);
        $storageUnavailable = true;
        return false;
    }

    $raw = stream_get_contents($handle);
    $payload = ($raw !== false && $raw !== '') ? json_decode($raw, true) : null;
    $timestamps = isset($payload['timestamps']) && is_array($payload['timestamps']) ? $payload['timestamps'] : array();
    $timestamps = array_values(array_filter($timestamps, function ($timestamp) use ($now, $window) {
        return (int)$timestamp > ($now - $window);
    }));

    if (count($timestamps) >= $limit) {
        $retryAfter = max(1, $window - ($now - (int)$timestamps[0]));
    } else {
        $timestamps[] = $now;
        $allowed = true;
    }

    $encoded = json_encode(array(
        'expires_at' => $now + $window,
        'timestamps' => $timestamps,
    ));

    if ($encoded === false || ftruncate($handle, 0) === false || rewind($handle) === false || fwrite($handle, $encoded) === false) {
        flock($handle, LOCK_UN);
        fclose($handle);
        logSearchProtectionError('failed to update rate limit file ' . $file);
        $storageUnavailable = true;
        return false;
    }

    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    return $allowed;
}

function buildApiPostPermalink($post)
{
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
            return $path;
        }

        return Typecho_Common::url($path, Helper::options()->index);
    } catch (Exception $e) {
        $slug = !empty($post['slug']) ? $post['slug'] : (isset($post['cid']) ? $post['cid'] : '');
        return rtrim(Helper::options()->siteUrl, '/') . '/' . ltrim((string)$slug, '/');
    }
}

function getSearchPlainText($text)
{
    $text = (string)$text;
    $text = preg_replace('/\[hide\].*?\[\/hide\]/su', ' ', $text);
    $text = preg_replace('/\[hide-inline(?:\s+[^\]]*)?\].*?\[\/hide-inline\]/su', ' ', $text);
    $text = preg_replace('/\[hide-block(?:\s+[^\]]*)?\].*?\[\/hide-block\]/su', ' ', $text);
    $text = preg_replace('/\[hide-toggle(?:\s+[^\]]*)?\].*?\[\/hide-toggle\]/su', ' ', $text);
    $text = preg_replace('/```[\s\S]*?```/u', ' ', $text);
    $text = preg_replace('/`([^`]*)`/u', '$1', $text);
    $text = preg_replace('/!\[([^\]]*)\]\(([^)]*)\)/u', '$1', $text);
    $text = preg_replace('/\[([^\]]+)\]\(([^)]*)\)/u', '$1', $text);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = strip_tags($text);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim($text);
}

function searchTextLength($text)
{
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
}

function searchTextSlice($text, $start, $length)
{
    return function_exists('mb_substr') ? mb_substr($text, $start, $length, 'UTF-8') : substr($text, $start, $length);
}

function searchTextPosition($text, $keyword)
{
    if ($keyword === '') {
        return false;
    }

    return function_exists('mb_stripos') ? mb_stripos($text, $keyword, 0, 'UTF-8') : stripos($text, $keyword);
}

function doesSearchTextMatch($title, $content, $keywords)
{
    foreach ($keywords as $keyword) {
        $inTitle = searchTextPosition((string)$title, $keyword) !== false;
        $inContent = searchTextPosition((string)$content, $keyword) !== false;

        if (!$inTitle && !$inContent) {
            return false;
        }
    }

    return true;
}

function buildSearchExcerpt($content, $keywords)
{
    $content = trim((string)$content);
    if ($content === '') {
        return '';
    }

    $contentLength = searchTextLength($content);
    $excerptLength = 140;
    $start = 0;
    $firstPosition = false;

    foreach ($keywords as $keyword) {
        $position = searchTextPosition($content, $keyword);
        if ($position !== false && ($firstPosition === false || $position < $firstPosition)) {
            $firstPosition = $position;
        }
    }

    if ($firstPosition !== false) {
        $start = max(0, $firstPosition - 30);
    }

    $excerpt = searchTextSlice($content, $start, $excerptLength);
    if ($excerpt === false) {
        $excerpt = '';
    }

    if ($start > 0) {
        $excerpt = '...' . ltrim($excerpt);
    }

    if ($start + searchTextLength($excerpt) < $contentLength) {
        $excerpt = rtrim($excerpt, '.') . '...';
    }

    return trim($excerpt);
}

function searchPosts()
{
    $config = getSearchProtectionConfig();
    $rawQuery = getSearchRequestRawQuery();

    if (hasInvalidSearchKeywords($rawQuery)) {
        responseJson(array(
            'status' => 'error',
            'message' => $config['query_too_short_message']
        ), 400);
    }

    $keywords = getSearchRequestKeywords();
    $query = implode(' ', $keywords);
    $limit = getSearchRequestLimit();

    if ($rawQuery === '') {
        responseJson(array(
            'status' => 'success',
            'data' => array(
                'query' => '',
                'items' => array(),
            )
        ));
    }

    if (empty($keywords)) {
        responseJson(array(
            'status' => 'error',
            'message' => $config['query_too_short_message']
        ), 400);
    }

    $retryAfter = 0;
    $storageUnavailable = false;
    if (!consumeSearchRateLimit($retryAfter, $storageUnavailable)) {
        if ($storageUnavailable) {
            responseJson(array(
                'status' => 'error',
                'message' => $config['service_unavailable_message']
            ), 503);
        }

        responseJson(array(
            'status' => 'error',
            'message' => $config['rate_limit_message']
        ), 429, array(
            'Retry-After: ' . (int)$retryAfter
        ));
    }

    $cacheKey = buildSearchCacheKey($keywords, $limit);
    $cachedResponse = getCachedSearchResponse($cacheKey);
    if (is_array($cachedResponse)) {
        responseJson($cachedResponse, 200, array(
            'Cache-Control: public, max-age=' . (int)$config['cache_ttl'],
            'X-Search-Cache: HIT'
        ));
    }

    $db = Typecho_Db::get();
    $select = $db->select('cid', 'title', 'slug', 'created', 'modified', 'type', 'text')
        ->from('table.contents')
        ->where('status = ?', 'publish')
        ->where('type IN ?', array('post', 'page'))
        ->where('created < ?', time())
        ->where('(password IS NULL OR password = ?)', '');

    foreach ($keywords as $keyword) {
        $like = '%' . $keyword . '%';
        $select->where('(title LIKE ? OR text LIKE ?)', $like, $like);
    }

    $rows = $db->fetchAll($select
        ->order('modified', Typecho_Db::SORT_DESC)
        ->limit($limit));

    $items = array();

    foreach ($rows as $row) {
        $title = isset($row['title']) ? (string)$row['title'] : '';
        $plainText = getSearchPlainText(isset($row['text']) ? $row['text'] : '');

        if (!doesSearchTextMatch($title, $plainText, $keywords)) {
            continue;
        }

        $items[] = array(
            'title' => $title,
            'content' => buildSearchExcerpt($plainText, $keywords),
            'url' => buildApiPostPermalink($row),
        );
    }

    $response = array(
        'status' => 'success',
        'data' => array(
            'query' => $query,
            'items' => $items,
        )
    );

    cacheSearchResponse($cacheKey, $response, (int)$config['cache_ttl']);

    responseJson($response, 200, array(
        'Cache-Control: public, max-age=' . (int)$config['cache_ttl'],
        'X-Search-Cache: MISS'
    ));
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
        responseJson(array(
            'status' => 'error',
            'message' => '微博热搜功能未开启'
        ), 403);
    }

    $data = requestWeiboHotData();
    responseJson(array(
        'status' => 'success',
        'data' => array(
            'html' => renderWeiboHotHtml($data)
        )
    ));
}

function responseJson($data, $statusCode = 200, $extraHeaders = array())
{
    if (function_exists('http_response_code')) {
        http_response_code((int)$statusCode);
    }

    header('Content-Type: application/json; charset=UTF-8');

    if (is_array($extraHeaders)) {
        foreach ($extraHeaders as $headerLine) {
            if (is_string($headerLine) && $headerLine !== '') {
                header($headerLine);
            }
        }
    }

    echo json_encode($data);
    exit;
}

if (isset($_SERVER['SCRIPT_FILENAME']) && realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $action = isset($_GET['action']) ? trim((string)$_GET['action']) : '';

    $allowedActions = array(
        'getPosts',
        'getPost',
        'search',
        'weibohot'
    );

    if ($action !== '' && !in_array($action, $allowedActions, true)) {
        responseJson(array('error' => 'Invalid action'), 400);
    }

    routeApiRequest($action);
}

?>
