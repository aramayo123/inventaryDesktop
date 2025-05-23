<?php
session_start();
header('Content-Type: application/json');

define('GITHUB_API', 'https://api.github.com/repos/aramayo123/inventaryDesktop/releases/latest');
define('USER_AGENT', 'InventaryDesktop-Updater');
define('TMP_DIR', __DIR__ . '/tmp_update');

function set_progress($step, $msg = '') {
    $_SESSION['update_progress'] = ['step' => $step, 'msg' => $msg];
}

if (isset($_GET['progress'])) {
    echo json_encode($_SESSION['update_progress'] ?? ['step' => 0, 'msg' => 'Esperando...']);
    exit;
}

try {
    set_progress(1, 'Consultando GitHub...');
    $ch = curl_init(GITHUB_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
    $json = curl_exec($ch);
    curl_close($ch);
    $release = json_decode($json, true);
    if (!$release || !isset($release['assets'][0]['browser_download_url'])) {
        throw new Exception('No se pudo obtener el release de GitHub');
    }
    $download_url = $release['assets'][0]['browser_download_url'];
    $filename = basename(parse_url($download_url, PHP_URL_PATH));
    $local_zip = TMP_DIR . '/' . $filename;

    set_progress(2, 'Descargando actualización...');
    if (!is_dir(TMP_DIR)) mkdir(TMP_DIR, 0777, true);
    $fp = fopen($local_zip, 'w+');
    $ch = curl_init($download_url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, USER_AGENT);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    set_progress(3, 'Descarga completada. Archivo guardado en: ' . $local_zip);
    echo json_encode(['ok' => true, 'msg' => 'Descarga completada. Archivo guardado en: ' . $local_zip]);
} catch (Exception $e) {
    set_progress(-1, 'Error: ' . $e->getMessage());
    echo json_encode(['ok' => false, 'msg' => $e->getMessage()]);
} 