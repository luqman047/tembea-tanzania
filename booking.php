<?php
require_once __DIR__ . '/config.php';
$destinations = db()->query('SELECT id,name,region,price_from FROM destinations ORDER BY name')->fetchAll();
$selected = filter_input(INPUT_GET, 'destination', FILTER_VALIDATE_INT) ?: 0;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $destinationId = filter_input(INPUT_POST, 'destination_id', FILTER_VALIDATE_INT) ?: 0;
    $name = trim((string)($_POST['full_name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $date = trim((string)($_POST['travel_date'] ?? ''));
    $guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT) ?: 0;
    $notes = trim((string)($_POST['notes'] ?? ''));
    $destinationCheck = db()->prepare('SELECT id FROM destinations WHERE id = ?');
    $destinationCheck->execute([$destinationId]);
    $validDate = DateTime::createFromFormat('Y-m-d', $date);
    if (!$destinationCheck->fetch()) $error = 'Chagua destination halali.';
    elseif (mb_strlen($name) < 2 || mb_strlen($name) > 140) $error = 'Weka jina sahihi.';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Weka email sahihi.';
    elseif (mb_strlen($phone) < 7 || mb_strlen($phone) > 40) $error = 'Weka namba ya simu sahihi.';
    elseif (!$validDate || $validDate->format('Y-m-d') !== $date || $date < date('Y-m-d')) $error = 'Chagua tarehe ya leo au ya baadaye.';
    elseif ($guests < 1 || $guests > 30) $error = 'Idadi ya wageni iwe kati ya 1 na 30.';
    elseif (mb_strlen($notes) > 2000) $error = 'Maelezo ni marefu mno.';
    else {
        $code = 'TT-' . strtoupper(bin2hex(random_bytes(4)));
        $stmt = db()->prepare('INSERT INTO bookings (booking_code,destination_id,full_name,email,phone,travel_date,guests,notes) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute([$code,$destinationId,$name,$email,$phone,$date,$guests,$notes ?: null]);
        header('Location: index.php?booking=sent#destinations');
        exit;
    }
    $selected = $destinationId;
}
?>
<!doctype html><html lang="sw"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Booking enquiry — Tembea Tanzania</title><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
<style>:root{--forest:#173c30;--gold:#d6b77a;--cream:#f7f4ed;--ink:#17251f;--muted:#6e7972;--line:#e4e0d6}*{box-sizing:border-box}body{margin:0;background:var(--cream);color:var(--ink);font-family:'DM Sans',sans-serif}.wrap{width:min(720px,calc(100% - 30px));margin:35px auto}.brand{font-weight:700;letter-spacing:.5px;color:var(--forest)}a{color:inherit}.panel{margin-top:28px;padding:clamp(22px,5vw,42px);background:white;box-shadow:0 18px 50px #17251f10}.eyebrow{color:#98753c;text-transform:uppercase;letter-spacing:2px;font-size:10px;font-weight:700}h1{font:500 clamp(34px,6vw,48px) 'Playfair Display',serif;margin:12px 0}p{color:var(--muted);font-size:13px;line-height:1.8}.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}.field{display:flex;flex-direction:column;gap:7px;margin:10px 0}.field.full{grid-column:1/-1}label{font-size:12px;font-weight:700}input,select,textarea{width:100%;padding:12px;border:1px solid var(--line);border-radius:3px;background:#fff;color:var(--ink);outline:none}input:focus,select:focus,textarea:focus{border-color:#9d8049}textarea{min-height:100px;resize:vertical}.btn{border:0;background:var(--forest);color:white;padding:14px 18px;font-weight:700;cursor:pointer;width:100%;margin-top:12px}.error{background:#fff0ed;border:1px solid #f2c3ba;padding:12px;color:#8a3024;font-size:13px}.notice{background:#f6f1e6;padding:13px;color:#65512f;font-size:12px;line-height:1.7;margin:15px 0}@media(max-width:540px){.grid{grid-template-columns:1fr}.field.full{grid-column:auto}}</style></head><body><div class="wrap"><a class="brand" href="index.php">✦ TEMBEA TANZANIA</a><div class="panel"><div class="eyebrow">Start your journey</div><h1>Plan your escape.</h1><p>Tupe taarifa zako. Hili ni ombi la booking; halithibitishi upatikanaji, bei ya mwisho au malipo.</p><?php if($error): ?><div class="error"><?=e($error)?></div><?php endif; ?><form method="post" action="booking.php"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><div class="grid"><div class="field full"><label for="destination_id">Destination *</label><select required id="destination_id" name="destination_id"><option value="">Chagua eneo</option><?php foreach($destinations as $d): ?><option value="<?=(int)$d['id']?>" <?=$selected===(int)$d['id']?'selected':''?>><?=e($d['name'])?> — <?=e($d['region'])?></option><?php endforeach; ?></select></div><div class="field"><label for="full_name">Jina kamili *</label><input id="full_name" name="full_name" maxlength="140" required value="<?=e($_POST['full_name']??'')?>"></div><div class="field"><label for="email">Email *</label><input id="email" name="email" type="email" maxlength="190" required value="<?=e($_POST['email']??'')?>"></div><div class="field"><label for="phone">Namba ya simu *</label><input id="phone" name="phone" type="tel" maxlength="40" required value="<?=e($_POST['phone']??'')?>"></div><div class="field"><label for="travel_date">Tarehe ya safari *</label><input id="travel_date" name="travel_date" type="date" min="<?=date('Y-m-d')?>" required value="<?=e($_POST['travel_date']??'')?>"></div><div class="field full"><label for="guests">Idadi ya wageni *</label><select id="guests" name="guests" required><?php for($i=1;$i<=30;$i++): ?><option value="<?=$i?>" <?=((int)($_POST['guests']??2)===$i)?'selected':''?>><?=$i?> <?= $i===1?'mgeni':'wageni' ?></option><?php endfor; ?></select></div><div class="field full"><label for="notes">Maombi maalumu (hiari)</label><textarea id="notes" name="notes" maxlength="2000" placeholder="Aina ya safari, mahitaji ya malazi, maswali..."><?=e($_POST['notes']??'')?></textarea></div></div><div class="notice">🔒 Hakuna malipo yatakayochukuliwa kupitia form hii. Timu inapaswa kuthibitisha booking na kukupa maelekezo rasmi ya malipo. Payment gateway haijaunganishwa.</div><button class="btn" type="submit">TUMA OMBI LA BOOKING ↗</button></form></div></div></body></html>
