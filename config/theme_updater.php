<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php

function butterflyGetThemeUpdaterConfig()
{
    return array(
        'repo' => 'wehaox/Typecho-Butterfly',
        'release_api' => 'https://api.github.com/repos/wehaox/Typecho-Butterfly/releases/latest',
        'release_page' => 'https://github.com/wehaox/Typecho-Butterfly/releases',
        'cache_key' => 'butterfly_theme_latest_release',
        'cache_ttl' => 600,
        'lock_ttl' => 1800,
    );
}

function butterflyGetInstalledThemeVersion()
{
    $indexFile = butterflyGetThemeRootPath() . '/index.php';

    if (!is_file($indexFile) || !is_readable($indexFile)) {
        return '未知';
    }

    $indexContent = file_get_contents($indexFile);
    if ($indexContent === false) {
        return '未知';
    }

    if (preg_match('/@version\s+([^\s]+)/', $indexContent, $matches)) {
        return trim($matches[1]);
    }

    return '未知';
}

function butterflyGetThemeRootPath()
{
    return dirname(__DIR__);
}

function butterflyGetThemeParentPath()
{
    return dirname(butterflyGetThemeRootPath());
}

function butterflyGetThemeSlug()
{
    return basename(butterflyGetThemeRootPath());
}

function butterflyNormalizeVersion($version)
{
    $version = trim((string) $version);

    if ($version === '') {
        return '';
    }

    if (preg_match('/(\d+(?:\.\d+)+)/', $version, $matches)) {
        return $matches[1];
    }

    $version = ltrim($version, "vV");

    return preg_match('/^\d+(?:\.\d+)*$/', $version) ? $version : '';
}

function butterflyThemeUpdaterRequest($url, array $headers = array())
{
    $defaultHeaders = array(
        'User-Agent: Typecho-Butterfly-Updater',
    );

    $hasAcceptHeader = false;
    foreach ($headers as $headerLine) {
        if (stripos((string) $headerLine, 'Accept:') === 0) {
            $hasAcceptHeader = true;
            break;
        }
    }

    if (!$hasAcceptHeader) {
        $defaultHeaders[] = 'Accept: application/vnd.github+json';
    }

    $requestHeaders = array_merge($defaultHeaders, $headers);

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

        $body = curl_exec($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false) {
            return array(
                'success' => false,
                'status' => $status,
                'body' => '',
                'message' => $error !== '' ? $error : '网络请求失败。',
            );
        }

        if ($status >= 400) {
            return array(
                'success' => false,
                'status' => $status,
                'body' => (string) $body,
                'message' => '远程服务器返回了异常状态：' . $status,
            );
        }

        return array(
            'success' => true,
            'status' => $status,
            'body' => (string) $body,
            'message' => '',
        );
    }

    $context = stream_context_create(array(
        'http' => array(
            'method' => 'GET',
            'timeout' => 30,
            'ignore_errors' => true,
            'header' => implode("\r\n", $requestHeaders) . "\r\n",
        ),
        'ssl' => array(
            'verify_peer' => true,
            'verify_peer_name' => true,
        ),
    ));

    $body = @file_get_contents($url, false, $context);
    $status = 0;

    if (isset($http_response_header) && is_array($http_response_header)) {
        foreach ($http_response_header as $responseHeader) {
            if (preg_match('#^HTTP/[^\s]+\s+(\d{3})#', $responseHeader, $matches)) {
                $status = (int) $matches[1];
                break;
            }
        }
    }

    if ($body === false) {
        return array(
            'success' => false,
            'status' => $status,
            'body' => '',
            'message' => '网络请求失败。',
        );
    }

    if ($status >= 400) {
        return array(
            'success' => false,
            'status' => $status,
            'body' => (string) $body,
            'message' => '远程服务器返回了异常状态：' . $status,
        );
    }

    return array(
        'success' => true,
        'status' => $status,
        'body' => (string) $body,
        'message' => '',
    );
}

function butterflyGetLatestReleaseInfo($forceRefresh = false)
{
    $config = butterflyGetThemeUpdaterConfig();

    if (!$forceRefresh && function_exists('getCache')) {
        $cachedRelease = getCache($config['cache_key'], $config['cache_ttl']);
        if (is_array($cachedRelease) && !empty($cachedRelease['success'])) {
            return $cachedRelease;
        }
    }

    $response = butterflyThemeUpdaterRequest($config['release_api']);
    if (!$response['success']) {
        return array(
            'success' => false,
            'message' => '获取 GitHub Release 信息失败：' . $response['message'],
            'release_url' => $config['release_page'],
        );
    }

    $releaseData = json_decode($response['body'], true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($releaseData)) {
        return array(
            'success' => false,
            'message' => 'GitHub Release 返回数据解析失败。',
            'release_url' => $config['release_page'],
        );
    }

    $downloadUrl = '';
    $downloadName = '';
    $preferredAsset = butterflyFindThemeReleaseAsset(isset($releaseData['assets']) && is_array($releaseData['assets']) ? $releaseData['assets'] : array());
    if (!empty($preferredAsset)) {
        $downloadUrl = $preferredAsset['browser_download_url'];
        $downloadName = $preferredAsset['name'];
    }

    if ($downloadUrl === '' && !empty($releaseData['zipball_url'])) {
        $downloadUrl = $releaseData['zipball_url'];
        $downloadName = 'github-release.zip';
    }

    if ($downloadUrl === '') {
        return array(
            'success' => false,
            'message' => '未在 Release 中找到可下载的 zip 包。',
            'release_url' => !empty($releaseData['html_url']) ? $releaseData['html_url'] : $config['release_page'],
        );
    }

    $releaseInfo = array(
        'success' => true,
        'message' => '',
        'tag_name' => isset($releaseData['tag_name']) ? trim((string) $releaseData['tag_name']) : '',
        'version' => butterflyNormalizeVersion(isset($releaseData['tag_name']) ? $releaseData['tag_name'] : ''),
        'release_name' => isset($releaseData['name']) ? trim((string) $releaseData['name']) : '',
        'release_url' => !empty($releaseData['html_url']) ? $releaseData['html_url'] : $config['release_page'],
        'download_url' => $downloadUrl,
        'download_name' => $downloadName,
        'body' => isset($releaseData['body']) ? (string) $releaseData['body'] : '',
    );

    if (function_exists('setCache')) {
        setCache($config['cache_key'], $releaseInfo);
    }

    return $releaseInfo;
}

function butterflyFindThemeReleaseAsset(array $assets)
{
    $preferredAsset = array();
    $fallbackAsset = array();

    foreach ($assets as $asset) {
        if (empty($asset['browser_download_url']) || empty($asset['name'])) {
            continue;
        }

        $assetName = (string) $asset['name'];
        if (!preg_match('/\.zip$/i', $assetName)) {
            continue;
        }

        $assetNameLower = strtolower($assetName);
        $containsThemeName = strpos($assetNameLower, 'butterfly') !== false;
        $containsStaticPackage = strpos($assetNameLower, 'static') !== false;

        if ($containsThemeName && !$containsStaticPackage) {
            return $asset;
        }

        if (empty($fallbackAsset) && !$containsStaticPackage) {
            $fallbackAsset = $asset;
        }

        if (empty($preferredAsset)) {
            $preferredAsset = $asset;
        }
    }

    if (!empty($fallbackAsset)) {
        return $fallbackAsset;
    }

    return $preferredAsset;
}

function butterflyCheckThemeUpdatePermissions()
{
    $themeRoot = butterflyGetThemeRootPath();
    $themeParent = butterflyGetThemeParentPath();
    $tempDir = $themeRoot . '/temp';

    if (!class_exists('ZipArchive')) {
        return array(
            'ok' => false,
            'message' => '当前服务器未启用 ZipArchive，无法在线更新，请前往 Release 页面手动下载。',
            'theme_root' => $themeRoot,
            'theme_parent' => $themeParent,
            'temp_dir' => $tempDir,
        );
    }

    if (!is_dir($themeRoot) || !is_readable($themeRoot)) {
        return array(
            'ok' => false,
            'message' => '当前主题目录不可读，无法执行在线更新，请前往 Release 页面手动下载。',
            'theme_root' => $themeRoot,
            'theme_parent' => $themeParent,
            'temp_dir' => $tempDir,
        );
    }

    if (!is_dir($themeParent) || !is_writable($themeParent)) {
        return array(
            'ok' => false,
            'message' => '主题父目录没有写入权限，无法替换新旧版本，请前往 Release 页面手动下载。',
            'theme_root' => $themeRoot,
            'theme_parent' => $themeParent,
            'temp_dir' => $tempDir,
        );
    }

    if (!is_writable($themeRoot)) {
        return array(
            'ok' => false,
            'message' => '当前主题目录没有写入权限，无法创建 temp 目录，请前往 Release 页面手动下载。',
            'theme_root' => $themeRoot,
            'theme_parent' => $themeParent,
            'temp_dir' => $tempDir,
        );
    }

    $probeDir = $themeRoot . '/temp-update-check-' . uniqid();
    if (!@mkdir($probeDir, 0755, true)) {
        return array(
            'ok' => false,
            'message' => '无法在当前主题目录创建临时目录，请前往 Release 页面手动下载。',
            'theme_root' => $themeRoot,
            'theme_parent' => $themeParent,
            'temp_dir' => $tempDir,
        );
    }

    @rmdir($probeDir);

    return array(
        'ok' => true,
        'message' => '',
        'theme_root' => $themeRoot,
        'theme_parent' => $themeParent,
        'temp_dir' => $tempDir,
    );
}

function butterflyGetThemeUpdateState($currentVersion, $forceRefresh = false)
{
    $releaseInfo = butterflyGetLatestReleaseInfo($forceRefresh);
    $permissionInfo = butterflyCheckThemeUpdatePermissions();
    $normalizedCurrentVersion = butterflyNormalizeVersion($currentVersion);

    $state = array(
        'release_success' => !empty($releaseInfo['success']),
        'release_message' => isset($releaseInfo['message']) ? $releaseInfo['message'] : '',
        'current_version' => $currentVersion,
        'current_version_normalized' => $normalizedCurrentVersion,
        'latest_version' => !empty($releaseInfo['version']) ? $releaseInfo['version'] : '',
        'latest_tag_name' => !empty($releaseInfo['tag_name']) ? $releaseInfo['tag_name'] : '',
        'latest_version_display' => !empty($releaseInfo['tag_name']) ? $releaseInfo['tag_name'] : '获取失败',
        'release_url' => !empty($releaseInfo['release_url']) ? $releaseInfo['release_url'] : butterflyGetThemeUpdaterConfig()['release_page'],
        'download_name' => !empty($releaseInfo['download_name']) ? $releaseInfo['download_name'] : '',
        'has_update' => false,
        'can_update' => false,
        'permission_ok' => !empty($permissionInfo['ok']),
        'permission_message' => isset($permissionInfo['message']) ? $permissionInfo['message'] : '',
        'status_message' => '',
    );

    if (!$state['release_success']) {
        $state['status_message'] = $state['release_message'] !== '' ? $state['release_message'] : '暂时无法读取 GitHub Release 标签。';
        return $state;
    }

    if ($normalizedCurrentVersion === '' || $state['latest_version'] === '') {
        $state['status_message'] = '无法识别当前版本或 Release 标签版本，暂不执行在线更新。';
        return $state;
    }

    if (version_compare($state['latest_version'], $normalizedCurrentVersion, '>')) {
        $state['has_update'] = true;
        if ($state['permission_ok']) {
            $state['can_update'] = true;
            $state['status_message'] = '检测到新版本 ' . $state['latest_tag_name'] . '，可直接在线更新。';
        } else {
            $state['status_message'] = $state['permission_message'];
        }
        return $state;
    }

    if (version_compare($state['latest_version'], $normalizedCurrentVersion, '<')) {
        $state['status_message'] = '当前主题版本高于最新 Release 标签，暂不提供在线更新。';
        return $state;
    }

    $state['status_message'] = '当前已经是最新 Release 版本。';
    return $state;
}

function butterflyThemeUpdaterPrepareTempDirectory($tempDir)
{
    if (is_dir($tempDir) && !butterflyThemeUpdaterRecursiveDelete($tempDir)) {
        return false;
    }

    return @mkdir($tempDir, 0755, true);
}

function butterflyThemeUpdaterRecursiveDelete($path)
{
    if (!file_exists($path)) {
        return true;
    }

    if (is_file($path) || is_link($path)) {
        return @unlink($path);
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $item) {
        if ($item->isDir()) {
            if (!@rmdir($item->getPathname())) {
                return false;
            }
        } else {
            if (!@unlink($item->getPathname())) {
                return false;
            }
        }
    }

    return @rmdir($path);
}

function butterflyThemeUpdaterIsThemePackage($directory)
{
    return is_dir($directory)
        && is_file($directory . '/index.php')
        && is_file($directory . '/functions.php')
        && is_file($directory . '/config/custom_config.php');
}

function butterflyThemeUpdaterLocatePackageRoot($extractDir)
{
    if (butterflyThemeUpdaterIsThemePackage($extractDir)) {
        return $extractDir;
    }

    $queue = array(array('path' => $extractDir, 'depth' => 0));
    while (!empty($queue)) {
        $current = array_shift($queue);
        $path = $current['path'];
        $depth = $current['depth'];

        if ($depth >= 3) {
            continue;
        }

        $children = @scandir($path);
        if (!is_array($children)) {
            continue;
        }

        foreach ($children as $child) {
            if ($child === '.' || $child === '..' || $child === '__MACOSX') {
                continue;
            }

            $childPath = $path . '/' . $child;
            if (!is_dir($childPath)) {
                continue;
            }

            if (butterflyThemeUpdaterIsThemePackage($childPath)) {
                return $childPath;
            }

            $queue[] = array(
                'path' => $childPath,
                'depth' => $depth + 1,
            );
        }
    }

    return '';
}

function butterflyDownloadThemeReleaseZip($downloadUrl, $zipPath)
{
    $response = butterflyThemeUpdaterRequest($downloadUrl, array('Accept: application/octet-stream'));
    if (!$response['success']) {
        return array(
            'success' => false,
            'message' => '下载更新包失败：' . $response['message'],
        );
    }

    if (@file_put_contents($zipPath, $response['body']) === false) {
        return array(
            'success' => false,
            'message' => '下载完成但无法写入本地 zip 文件。',
        );
    }

    clearstatcache(true, $zipPath);

    if (!is_file($zipPath) || (int) @filesize($zipPath) <= 0) {
        return array(
            'success' => false,
            'message' => '下载的 zip 文件为空，已停止在线更新。',
        );
    }

    return array(
        'success' => true,
        'message' => '',
    );
}

function butterflyThemeUpdaterValidateZipArchive(ZipArchive $zip)
{
    if ($zip->numFiles <= 0) {
        return array(
            'success' => false,
            'message' => '更新包内容为空，已停止在线更新。',
        );
    }

    for ($index = 0; $index < $zip->numFiles; $index++) {
        $entryName = $zip->getNameIndex($index);
        if ($entryName === false) {
            return array(
                'success' => false,
                'message' => '更新包条目读取失败，已停止在线更新。',
            );
        }

        $entryName = str_replace('\\', '/', (string) $entryName);
        if ($entryName === '' || strpos($entryName, '../') !== false || preg_match('#^([a-zA-Z]:)?/#', $entryName)) {
            return array(
                'success' => false,
                'message' => '更新包包含非法路径，已停止在线更新。',
            );
        }
    }

    return array(
        'success' => true,
        'message' => '',
    );
}

function butterflyThemeUpdaterLockPath()
{
    return butterflyGetThemeParentPath() . '/.' . butterflyGetThemeSlug() . '.update.lock';
}

function butterflyThemeUpdaterAcquireLock()
{
    $config = butterflyGetThemeUpdaterConfig();
    $lockPath = butterflyThemeUpdaterLockPath();

    if (is_file($lockPath) && (time() - (int) @filemtime($lockPath)) > (int) $config['lock_ttl']) {
        @unlink($lockPath);
    }

    $lockHandle = @fopen($lockPath, 'c+');
    if ($lockHandle === false) {
        return array(
            'success' => false,
            'message' => '无法创建更新锁文件，已停止在线更新。',
            'handle' => null,
        );
    }

    if (!@flock($lockHandle, LOCK_EX | LOCK_NB)) {
        fclose($lockHandle);
        return array(
            'success' => false,
            'message' => '检测到已有主题更新任务正在执行，请稍后重试。',
            'handle' => null,
        );
    }

    ftruncate($lockHandle, 0);
    fwrite($lockHandle, (string) time());
    fflush($lockHandle);

    return array(
        'success' => true,
        'message' => '',
        'handle' => $lockHandle,
    );
}

function butterflyThemeUpdaterReleaseLock($lockHandle)
{
    $lockPath = butterflyThemeUpdaterLockPath();

    if (is_resource($lockHandle)) {
        @flock($lockHandle, LOCK_UN);
        @fclose($lockHandle);
    }

    if (is_file($lockPath)) {
        @unlink($lockPath);
    }
}

function butterflyRunThemeUpdate($currentVersion)
{
    $releaseInfo = butterflyGetLatestReleaseInfo(true);
    $releasePage = !empty($releaseInfo['release_url']) ? $releaseInfo['release_url'] : butterflyGetThemeUpdaterConfig()['release_page'];

    if (empty($releaseInfo['success'])) {
        return array(
            'success' => false,
            'message' => !empty($releaseInfo['message']) ? $releaseInfo['message'] : '暂时无法获取 Release 信息，请前往 Release 页面手动下载。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $currentVersionNormalized = butterflyNormalizeVersion($currentVersion);
    if ($currentVersionNormalized === '' || empty($releaseInfo['version'])) {
        return array(
            'success' => false,
            'message' => '无法识别当前版本或 Release 标签版本，已停止在线更新。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    if (!version_compare($releaseInfo['version'], $currentVersionNormalized, '>')) {
        return array(
            'success' => true,
            'message' => '当前已经是最新 Release 版本，无需在线更新。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $permissionInfo = butterflyCheckThemeUpdatePermissions();
    if (empty($permissionInfo['ok'])) {
        return array(
            'success' => false,
            'message' => $permissionInfo['message'],
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $lockInfo = butterflyThemeUpdaterAcquireLock();
    if (empty($lockInfo['success'])) {
        return array(
            'success' => false,
            'message' => $lockInfo['message'],
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $lockHandle = $lockInfo['handle'];

    $tempDir = $permissionInfo['temp_dir'];
    $themeRoot = $permissionInfo['theme_root'];
    $themeParent = $permissionInfo['theme_parent'];
    $themeSlug = butterflyGetThemeSlug();
    $stagedNewDir = $themeParent . '/' . $themeSlug . '-new-' . date('YmdHis');
    if (file_exists($stagedNewDir)) {
        $stagedNewDir .= '-' . uniqid();
    }

    if (!butterflyThemeUpdaterPrepareTempDirectory($tempDir)) {
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => '无法创建更新所需的 temp 目录，请前往 Release 页面手动下载。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $zipPath = $tempDir . '/theme-release.zip';
    $extractDir = $tempDir . '/package';
    if (!@mkdir($extractDir, 0755, true)) {
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => '无法创建解压目录，请前往 Release 页面手动下载。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $downloadResult = butterflyDownloadThemeReleaseZip($releaseInfo['download_url'], $zipPath);
    if (empty($downloadResult['success'])) {
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => $downloadResult['message'],
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $zip = new ZipArchive();
    if ($zip->open($zipPath) !== true) {
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => '下载的 zip 包无法打开，请前往 Release 页面手动下载。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $zipValidation = butterflyThemeUpdaterValidateZipArchive($zip);
    if (empty($zipValidation['success'])) {
        $zip->close();
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => $zipValidation['message'],
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $extractOk = $zip->extractTo($extractDir);
    $zip->close();

    if (!$extractOk) {
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => 'zip 包解压失败，请前往 Release 页面手动下载。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $packageRoot = butterflyThemeUpdaterLocatePackageRoot($extractDir);
    if ($packageRoot === '') {
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => '未能在更新包中识别主题根目录，已停止在线更新。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    if (!@rename($packageRoot, $stagedNewDir)) {
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => '无法将新版本目录移动到待切换位置，已停止在线更新。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    $backupDir = $themeParent . '/' . $themeSlug . '-old-' . date('YmdHis');
    if (file_exists($backupDir)) {
        $backupDir .= '-' . uniqid();
    }

    if (!@rename($themeRoot, $backupDir)) {
        butterflyThemeUpdaterRecursiveDelete($stagedNewDir);
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => '旧版本目录重命名失败，无法继续在线更新。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    if (!@rename($stagedNewDir, $themeRoot)) {
        $rollbackOk = @rename($backupDir, $themeRoot);
        butterflyThemeUpdaterRecursiveDelete($stagedNewDir);
        butterflyThemeUpdaterRecursiveDelete($tempDir);
        butterflyThemeUpdaterReleaseLock($lockHandle);
        return array(
            'success' => false,
            'message' => $rollbackOk
                ? '新版本目录替换失败，旧版本已恢复。'
                : '新版本目录替换失败，且旧版本恢复也失败，请立即手动将 ' . basename($backupDir) . ' 重命名回 ' . $themeSlug . '。',
            'redirect' => false,
            'delay' => 0,
            'release_url' => $releasePage,
        );
    }

    if (function_exists('clearCache')) {
        clearCache();
    }

    butterflyThemeUpdaterRecursiveDelete($tempDir);
    $deleteOldResult = butterflyThemeUpdaterRecursiveDelete($backupDir);
    butterflyThemeUpdaterReleaseLock($lockHandle);
    if (!$deleteOldResult) {
        return array(
            'success' => true,
            'message' => '主题已更新到 ' . $releaseInfo['tag_name'] . '，但旧版本目录删除失败，请手动清理：' . basename($backupDir),
            'redirect' => true,
            'delay' => 3000,
            'release_url' => $releasePage,
        );
    }

    return array(
        'success' => true,
        'message' => '主题已成功更新到 ' . $releaseInfo['tag_name'] . '，请等待自动刷新！如果等不到请点击',
        'redirect' => true,
        'delay' => 3000,
        'release_url' => $releasePage,
    );
}
