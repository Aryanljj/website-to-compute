<?php require_once 'config.php'; if(isset($_SESSION['user_id'])) header("Location: dashboard.php"); ?>
<!DOCTYPE html>
<html>
<head><title>Login | Rajavip</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:system-ui}
body{background:linear-gradient(145deg,#0a0e1a 0%,#0c1120 100%);min-height:100vh;display:flex;justify-content:center;align-items:center}
.card{background:rgba(18,25,40,0.85);backdrop-filter:blur(8px);padding:2rem;border-radius:1.5rem;max-width:400px;width:100%;border:1px solid rgba(255,200,50,0.3)}
h2{color:white;margin-bottom:1rem}
input{background:#0f172f;border:1px solid #2d3748;border-radius:1rem;padding:0.75rem;color:white;width:100%;margin:0.5rem 0}
button{background:linear-gradient(95deg,#f5b042,#e08e2a);width:100%;padding:0.8rem;border:none;border-radius:1rem;font-weight:700;cursor:pointer}
.error{color:#ff8989}
a{color:#f5b042;text-decoration:none}
</style>
</head>
<body>
<div class="card">
<h2>Login</h2>
<?php if(isset($_GET['error'])) echo "<div class='error'>".htmlspecialchars($_GET['error'])."</div>"; ?>
<form method="POST" action="login_process.php">
<input type="tel" name="phone" placeholder="Phone number" required>
<input type="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>
<div style="text-align:center;margin-top:1rem"><a href="register.php">Register</a></div>
</div>
</body>
</html>
