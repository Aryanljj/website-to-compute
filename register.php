<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | Rajavip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:system-ui}
        body{background:linear-gradient(145deg,#0a0e1a 0%,#0c1120 100%);min-height:100vh;display:flex;justify-content:center;align-items:center;padding:1.5rem}
        .card{max-width:500px;width:100%;background:rgba(18,25,40,0.85);backdrop-filter:blur(8px);border-radius:2rem;padding:2rem 1.5rem;border:1px solid rgba(255,200,50,0.3)}
        h2{color:white;font-size:1.8rem}
        .sub{color:#94a3b8;margin-bottom:1.5rem;border-left:3px solid #f5b042;padding-left:0.7rem}
        .input-group{margin-bottom:1rem}
        label{color:#cbd5e6;display:block;margin-bottom:0.4rem;font-size:0.8rem}
        input,select{background:#0f172f;border:1px solid #2d3748;border-radius:1rem;padding:0.75rem 1rem;color:white;width:100%;outline:none}
        .phone-flex{display:flex;gap:0.7rem}
        .country-code{width:100px}
        button{background:linear-gradient(95deg,#f5b042,#e08e2a);width:100%;padding:0.9rem;border:none;border-radius:2rem;font-weight:700;font-size:1.1rem;cursor:pointer;margin-top:0.5rem}
        .login-link{text-align:center;margin-top:1rem;color:#94a3b8}
        .login-link a{color:#f5b042;text-decoration:none}
        .error{color:#ff8989;font-size:0.8rem;margin-top:0.3rem}
        .success{color:#4ade80}
        .footer{text-align:center;margin-top:1.5rem;font-size:0.7rem;color:#e2e8f0}
    </style>
</head>
<body>
<div class="card">
    <h2>Register</h2>
    <div class="sub">Please register by phone number or email</div>
    <?php if(isset($_GET['error'])) echo "<div class='error'>".htmlspecialchars($_GET['error'])."</div>"; ?>
    <?php if(isset($_GET['success'])) echo "<div class='success'>Registered! <a href='login.php'>Login</a></div>"; ?>
    <form method="POST" action="register_process.php">
        <div class="input-group">
            <label>Phone number</label>
            <div class="phone-flex">
                <select name="country_code" class="country-code"><option value="+91">+91 India</option><option value="+1">+1 USA</option></select>
                <input type="tel" name="phone" placeholder="Phone number" required>
            </div>
        </div>
        <div class="input-group"><label>Email (optional)</label><input type="email" name="email" placeholder="your@email.com"></div>
        <div class="input-group"><label>Password (8+ chars, letters+numbers)</label><input type="password" name="password" id="password" required></div>
        <div class="input-group"><label>Confirm password</label><input type="password" name="confirm_password" id="confirm" required></div>
        <div class="input-group"><label>Invite code</label><input type="text" name="invite_code" value="5376197784723" placeholder="Invitation code"></div>
        <button type="submit">Register</button>
        <div class="login-link">Have account? <a href="login.php">Login</a></div>
        <div class="footer">⚡ Withdraw fast, safe and stable</div>
    </form>
</div>
</body>
</html>
