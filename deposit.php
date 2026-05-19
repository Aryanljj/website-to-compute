<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $amount = floatval($_POST['amount']);
    if($amount >= 100){
        $stmt = $pdo->prepare("INSERT INTO deposit_requests (user_id, amount, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$user['id'], $amount]);
        $success = "✅ Deposit request submitted! Admin will approve.";
    } else $error = "❌ Minimum deposit ₹100";
}
?>
<!DOCTYPE html>
<html>
<head><title>Deposit | Rajavip</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:system-ui}
body{background:#0a0e1a;color:white;padding:1rem}
.card{max-width:500px;margin:auto;background:#0f172f;border-radius:1.5rem;padding:1.5rem;text-align:center;border:1px solid #f5b042}
img{width:220px;margin:1rem;border-radius:1rem}
input,button{width:100%;padding:0.7rem;margin:0.5rem 0;border-radius:1rem;border:none}
input{background:#1e2a3a;color:white;border:1px solid #2d3748}
button{background:#f5b042;font-weight:bold;cursor:pointer}
.error{color:#ff8989}
.success{color:#4ade80}
</style>
</head>
<body>
<div class="card">
    <h2>💰 Deposit Money</h2>
    <p>Your Balance: <strong>₹<?php echo number_format($user['balance'],2); ?></strong></p>
    <img src="https://i.ibb.co/PGPbgDsw/IMG-20260518-141051.png" alt="Payment QR Code">
    <p style="font-size:0.8rem;color:#94a3b8">Scan this QR code & pay</p>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <?php if(isset($success)) echo "<div class='success'>$success</div>"; ?>
    <form method="POST">
        <input type="number" name="amount" placeholder="Amount (min ₹100)" min="100" required>
        <button type="submit">Submit Deposit Request</button>
    </form>
    <a href="dashboard.php" style="color:#f5b042;text-decoration:none">← Back to Dashboard</a>
</div>
</body>
</html>
