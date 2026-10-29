<?php
require_once __DIR__ . '/config.php';
$count = (int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $count === 0) {
    verify_csrf();
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_.-]{4,80}$/', $username)) $message = 'Username iwe na herufi/namba 4–80.';
    elseif (strlen($password) < 12) $message = 'Password iwe na angalau herufi 12.';
    else {
        $stmt = db()->prepare('INSERT INTO admins (username,password_hash) VALUES (?,?)');
        $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT)]);
        $message = 'Admin ameundwa. Sasa futa faili setup_admin.php kutoka server, kisha ingia admin.';
        $count = 1;
    }
}
?><!doctype html><html lang="sw"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Setup Admin</title><style>body{font-family:Arial;background:#f3f4ef;margin:0;padding:20px}.box{max-width:450px;margin:6vh auto;background:white;padding:25px}label{display:block;margin:15px 0 6px}input{box-sizing:border-box;width:100%;padding:12px;border:1px solid #ddd}button{margin-top:16px;background:#173c30;color:white;padding:12px;border:0;width:100%}.note{font-size:13px;line-height:1.7;color:#5d6b61}.msg{padding:12px;background:#f7f0df;margin:10px 0}</style></head><body><div class="box"><h1>Tembea Tanzania</h1><h2>Admin setup</h2><p class="note">Tumia ukurasa huu kuunda admin wa kwanza. Ukishamaliza, futa setup_admin.php kwenye server.</p><?php if($message):?><div class="msg"><?=e($message)?></div><?php endif;?><?php if($count===0):?><form method="post"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><label>Username</label><input name="username" required minlength="4" maxlength="80"><label>Password (angalau herufi 12)</label><input type="password" name="password" required minlength="12"><button type="submit">Unda admin</button></form><?php else:?><p>Admin tayari yupo au ameundwa. Kwa usalama, futa setup_admin.php kisha <a href="admin/login.php">ingia hapa</a>.</p><?php endif;?></div></body></html>
