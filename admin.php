<?php
require_once 'config.php';
$adminCheck = $pdo->query("SELECT id FROM users ORDER BY id ASC LIMIT 1")->fetch();
if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $adminCheck['id']) die("⛔ Access Denied");

if(isset($_GET['approve_deposit'])){
    $id = $_GET['approve_deposit'];
    $dep = $pdo->prepare("SELECT * FROM deposit_requests WHERE id=?");
    $dep->execute([$id]); $d=$dep->fetch();
    if($d && $d['status']=='pending'){
        $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id=?")->execute([$d['amount'],$d['user_id']]);
        $pdo->prepare("UPDATE deposit_requests SET status='approved' WHERE id=?")->execute([$id]);
        $pdo->prepare("INSERT INTO transactions (user_id,type,amount,status) VALUES (?, 'deposit', ?, 'approved')")->execute([$d['user_id'],$d['amount']]);
    } header("Location: admin.php");
}
if(isset($_GET['approve_withdraw'])){
    $pdo->prepare("UPDATE withdraw_requests SET status='approved' WHERE id=?")->execute([$_GET['approve_withdraw']]);
    header("Location: admin.php");
}
$deposits = $pdo->query("SELECT d.*, u.phone FROM deposit_requests d JOIN users u ON d.user_id = u.id WHERE d.status='pending' ORDER BY d.id DESC")->fetchAll();
$withdraws = $pdo->query("SELECT w.*, u.phone FROM withdraw_requests w JOIN users u ON w.user_id = u.id WHERE w.status='pending' ORDER BY w.id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin | Rajavip</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:system-ui}
body{background:#0a0e1a;color:white;padding:1rem}
table{width:100%;border-collapse:collapse;margin:1rem 0}
td,th{border:1px solid #2d3748;padding:0.5rem;text-align:left}
th{background:#f5b042;color:#0a0e1a}
.approve{color:#4ade80;text-decoration:none;font-weight:bold}
</style>
</head>
<body>
<h2>👑 Admin Panel</h2>
<h3>📥 Pending Deposits (<?php echo count($deposits); ?>)</h3>
<table>
<tr><th>User</th><th>Amount</th><th>Action</th></tr>
<?php foreach($deposits as $d): ?>
<tr><td><?php echo $d['phone']; ?></td><td>₹<?php echo $d['amount']; ?></td><td><a href="?approve_deposit=<?php echo $d['id']; ?>" class="approve">✅ Approve</a></td></tr>
<?php endforeach; ?>
</table>
<h3>📤 Pending Withdrawals (<?php echo count($withdraws); ?>)</h3>
<table>
<tr><th>User</th><th>Amount</th><th>Action</th></tr>
<?php foreach($withdraws as $w): ?>
<tr><td><?php echo $w['phone']; ?></td><td>₹<?php echo $w['amount']; ?></td><td><a href="?approve_withdraw=<?php echo $w['id']; ?>" class="approve">✅ Approve</a></td></tr>
<?php endforeach; ?>
</table>
<br>
<a href="dashboard.php" style="color:#f5b042">← Back to Dashboard</a>
</body>
</html>
