<?php
require_once dirname(__DIR__) . '/config.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $stmt = db()->prepare('SELECT id,username,password_hash FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]); $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Username au password si sahihi.';
}
?><!doctype html><html lang="sw"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login — Tembea Tanzania</title><style>body{margin:0;background:#10241d;color:#17251f;font-family:Arial,sans-serif;min-height:100vh;display:grid;place-items:center;padding:18px;box-sizing:border-box}.box{width:min(390px,100%);background:#f7f4ed;padding:30px;box-sizing:border-box}h1{font-family:Georgia,serif;font-weight:500}label{display:block;font-size:13px;margin:15px 0 7px}input{width:100%;box-sizing:border-box;padding:12px;border:1px solid #ddd;background:white}button{margin-top:20px;width:100%;padding:13px;background:#173c30;color:white;border:0;font-weight:bold;cursor:pointer}.error{background:#ffe9e5;padding:10px;color:#8a3024;font-size:13px}.muted{color:#69766e;font-size:12px;line-height:1.6}a{color:#173c30}</style></head><body><div class="box"><p>✦ TEMBEA TANZANIA / ADMIN</p><h1>Karibu tena.</h1><p class="muted">Ingia kusimamia maombi ya safari.</p><?php if($error):?><div class="error"><?=e($error)?></div><?php endif;?><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Username</label><input name="username" required autocomplete="username" maxlength="80"><label>Password</label><input type="password" name="password" required autocomplete="current-password"><button type="submit">INGIA</button></form><p class="muted"><a href="../index.php">← Rudi kwenye website</a></p></div></body></html>
