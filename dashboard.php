<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['game_result'])){
    $bet = floatval($_POST['bet']);
    $win = floatval($_POST['win']);
    $new_balance = $user['balance'] - $bet + $win;
    if($new_balance >= 0){
        $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $stmt->execute([$new_balance, $user['id']]);
        $type = ($win > 0) ? 'game_win' : 'game_loss';
        $amount = ($win > 0) ? $win : $bet;
        $stmt = $pdo->prepare("INSERT INTO transactions (user_id, type, amount, status) VALUES (?, ?, ?, 'approved')");
        $stmt->execute([$user['id'], $type, $amount]);
        $user['balance'] = $new_balance;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard | Rajavip</title><meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:system-ui}
body{background:#0a0e1a;color:white;padding:1rem}
.dashboard{max-width:1200px;margin:auto}
.balance-card{background:linear-gradient(135deg,#1e2a3a,#0f172f);border-radius:1.5rem;padding:1.5rem;text-align:center;margin-bottom:1.5rem}
.balance{font-size:2.5rem;color:#f5b042}
.btn{display:inline-block;padding:0.7rem 1.5rem;border-radius:2rem;text-decoration:none;margin:0.3rem}
.deposit-btn{background:#2d3748;color:white}
.withdraw-btn{background:#f5b042;color:#0a0e1a;font-weight:bold}
.games-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem}
.game-card{background:#0f172f;border-radius:1rem;padding:1rem;text-align:center;border:1px solid #2d3748;cursor:pointer;transition:0.2s}
.game-card:hover{transform:scale(0.98)}
.game-icon{font-size:3rem}
input{background:#0f172f;border:1px solid #2d3748;padding:0.5rem;border-radius:0.5rem;color:white;width:80%;margin-top:0.5rem}
</style>
</head>
<body>
<div class="dashboard">
    <div class="balance-card">
        <h3>Welcome, <?php echo htmlspecialchars($user['phone']); ?></h3>
        <div class="balance">₹ <?php echo number_format($user['balance'], 2); ?></div>
        <p>Your invite code: <strong><?php echo $user['invite_code']; ?></strong></p>
        <a href="deposit.php" class="btn deposit-btn">💰 Deposit</a>
        <a href="withdraw.php" class="btn withdraw-btn">💸 Withdraw</a>
        <a href="logout.php" style="color:#94a3b8;margin-left:1rem">Logout</a>
    </div>
    <div class="games-grid">
        <div class="game-card" onclick="playSlot()"><div class="game-icon">🎰</div><h3>Slot Machine</h3><p>Win up to 10x!</p><input type="number" id="slotBet" placeholder="Bet amount" value="10"></div>
        <div class="game-card" onclick="playDice()"><div class="game-icon">🎲</div><h3>Dice Roll</h3><p>Roll >3 = 2x win</p><input type="number" id="diceBet" placeholder="Bet amount" value="10"></div>
        <div class="game-card" onclick="playCoinFlip()"><div class="game-icon">🪙</div><h3>Coin Flip</h3><p>50/50 chance, 2x win</p><input type="number" id="coinBet" placeholder="Bet amount" value="10"></div>
    </div>
</div>
<script>
let balance = <?php echo $user['balance']; ?>;
async function submitGame(bet, winAmount){
    if(bet<=0 || bet>balance){ alert("Invalid bet or insufficient balance"); return false; }
    let formData = new FormData();
    formData.append('game_result','1');
    formData.append('bet',bet);
    formData.append('win',winAmount);
    await fetch(window.location.href, {method:'POST', body:formData});
    location.reload();
}
function playSlot(){ let bet=parseFloat(document.getElementById('slotBet').value)||10; if(bet>balance){ alert("No balance"); return; } let multipliers=[0,0,0,1,2,5,10]; let m=multipliers[Math.floor(Math.random()*7)]; let win=bet*m; alert(m?`🎉 You won ₹${win}! (${m}x)`:`😢 You lost ₹${bet}`); submitGame(bet,win); }
function playDice(){ let bet=parseFloat(document.getElementById('diceBet').value)||10; if(bet>balance) return; let roll=Math.floor(Math.random()*6)+1; let win=(roll>3)?bet*2:0; alert(`🎲 Dice: ${roll}. ${win?`You won ₹${win}!`:`You lost ₹${bet}`}`); submitGame(bet,win); }
function playCoinFlip(){ let bet=parseFloat(document.getElementById('coinBet').value)||10; if(bet>balance) return; let heads=Math.random()<0.5; let win=heads?bet*2:0; alert(`${heads?'🪙 Heads':'🪙 Tails'}! ${win?`You won ₹${win}!`:`You lost ₹${bet}`}`); submitGame(bet,win); }
</script>
</body>
</html>
