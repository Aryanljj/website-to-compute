<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $amount = floatval($_POST['amount']);
    $wallet = $_POST['wallet'];
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $balance = $stmt->fetchColumn();
    if($amount >= 100 && $amount <= $balance){
        $stmt = $pdo->prepare("INSERT INTO withdraw_requests (user_id, amount, wallet_address) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $amount, $wallet]);
        $stmt = $pdo->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $stmt->execute([$amount, $_SESSION['user_id']]);
        echo "<script>alert('✅ Withdraw request submitted!'); window.location='dashboard.php';</script>";
    } else $error = "❌ Invalid amount or insufficient balance";
}
?>
<!DOCTYPE html>
<html>
<head><title>Withdraw | Rajavip</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:system-ui}
body{background:#0a0e1a;color:white;padding:1rem}
.card{max-width:400px;margin:auto;background:#0f172f;padding:1.5rem;border-radius:1.5rem}
input,button{width:100%;padding:0.7rem;margin:0.5rem 0;border-radius:1rem;border:none}
input{background:#1e2a3a;color:white;border:1px solid #2d3748}
button{background:#f5b042;font-weight:bold;cursor:pointer}
.error{color:#ff8989}
</style>
</head>
<body>
<div class="card">
    <h2>💸 Withdraw Funds</h2>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <input type="number" name="amount" placeholder="Amount (min ₹100)" min="100" required>
        <input type="text" name="wallet" placeholder="UPI ID / Bank Account / USDT" required>
        <button type="submit">Request Withdraw</button>
    </form>
    <a href="dashboard.php" style="color:#f5b042;text-decoration:none">← Back</a>
</div>
</body>
</html>
