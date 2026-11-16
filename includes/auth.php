<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login() {
    if (empty($_SESSION['user_id'])) {
        header('Location: ' . base_url('login.php'));
        exit;
    }
}
function require_role($roles) {
    require_login();
    if (!is_array($roles)) $roles = [$roles];
    if (!in_array($_SESSION['role'] ?? '', $roles)) {
        http_response_code(403);
        die('Akses ditolak: hak akses Anda tidak mencukupi.');
    }
}
function base_url($path = '') {
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    // jika sedang di subfolder (admin/apoteker/pelanggan), naik 1 level
    $script = $_SERVER['SCRIPT_NAME'];
    if (preg_match('#/(admin|apoteker|pelanggan)/#', $script)) {
        $base = preg_replace('#/(admin|apoteker|pelanggan)$#', '', $base);
    }
    return $base . '/' . ltrim($path, '/');
}
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function flash($k, $v=null){
    if ($v!==null){ $_SESSION['flash'][$k]=$v; return; }
    $val = $_SESSION['flash'][$k] ?? null;
    unset($_SESSION['flash'][$k]);
    return $val;
}
