<?php

function routeApiRequest($apiPath)
{
    // 定义路由表，键为路径，值为处理函数名称
    $routes = [
        'getPosts' => 'getPosts',
        'getPost' => 'getPost'
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
    $posts = $db->fetchAll($db->select()->from('table.contents')
        ->where('type = ?', 'post')
        ->order('created', Typecho_Db::SORT_DESC));

    responseJson(['status' => 'success', 'data' => $posts]);
}

function getPost($cid)
{
    if ($cid) {
        $db = Typecho_Db::get();
        $post = $db->fetchRow($db->select()->from('table.contents')
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

function responseJson($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

?>