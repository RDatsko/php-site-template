<?php
session_start();

// If already logged in, go to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /admin/');
    exit;
}

// Auto-logout after 30 minutes of inactivity
$timeout = 1800; // 30 minutes in seconds

if (isset($_SESSION['LAST_ACTIVE']) && (time() - $_SESSION['LAST_ACTIVE'] > $timeout)) {
    // Session timed out
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

// Handle form submission
$errors = [];

if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $errors[] = 'Your session has timed out. Please log in again.';
}

// Update last active time
$_SESSION['LAST_ACTIVE'] = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUser = $_POST['username'] ?? '';
    $inputPass = $_POST['password'] ?? '';

    $usersFile = __DIR__ . '/data/users.txt';

    if (!file_exists($usersFile)) {
        $errors[] = 'User file not found.';
    } else {
        $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            list($storedUser, $storedHash) = explode(':', $line, 2);

            if ($inputUser === $storedUser && password_verify($inputPass, $storedHash)) {
                // Successful login
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_user'] = $storedUser;
                header('Location: /admin/');
                exit;
            }
        }
        $errors[] = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Admin Login</title></head>
<style>
* {
	box-sizing: border-box;
	margin: 0;
	padding: 0;	
	font-family: Raleway, sans-serif;
}

body {
	background: linear-gradient(90deg, #C7C5F4, #776BCC);	
    overflow: hidden	
}

.container {
	display: flex;
	align-items: center;
	justify-content: flex-end;
	min-height: 100vh;
}

.screen {		
	background: linear-gradient(90deg, #5D54A4, #7C78B8);		
	position: relative;	
	height: 100vh;
	width: 500px;
	box-shadow: 0px 0px 24px #5C5696;
}

.screen__content {
	z-index: 1;
	position: relative;	
	height: 100%;
}

.screen__background {		
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 0;
	-webkit-clip-path: inset(0 0 0 0);
	clip-path: inset(0 0 0 0);	
}

.screen__background__shape {
	transform: rotate(45deg);
	position: absolute;
}

.screen__background__shape1 {
	height: 820px;
	width: 820px;
	background: #FFF;	
	top: -180px;
	right: 170px;	
	border-radius: 0 72px 0 0;
}

.screen__background__shape2 {
	height: 220px;
	width: 220px;
	background: #6C63AC;	
	top: -172px;
	right: 0;	
	border-radius: 32px;
}

.screen__background__shape3 {
	height: 540px;
	width: 190px;
	background: linear-gradient(270deg, #5D54A4, #6A679E);
	top: -24px;
	right: 0;	
	border-radius: 32px;
}

.screen__background__shape4 {
	height: 400px;
	width: 200px;
	background: #7E7BB9;	
	top: 420px;
	right: 50px;	
	border-radius: 60px;
}

.login {
	width: 320px;
	padding: 30px;
	padding-top: 156px;
}

.login__field {
	padding: 20px 0px;	
	position: relative;	
}

.login__icon {
	position: absolute;
	top: 30px;
	color: #7875B5;
}

.login__input {
	border: none;
	border-bottom: 2px solid #D1D1D4;
	background: none;
	padding: 10px;
	padding-left: 24px;
	font-weight: 700;
	width: 100%;
	transition: .2s;
}

.login__input:active,
.login__input:focus,
.login__input:hover {
	outline: none;
	border-bottom-color: #6A679E;
}

.login__submit {
	background: #fff;
	font-size: 14px;
	margin-top: 30px;
	padding: 16px 20px;
	border-radius: 26px;
	border: 1px solid #D4D3E8;
	text-transform: uppercase;
	font-weight: 700;
	display: flex;
	align-items: center;
	width: 100%;
	color: #4C489D;
	box-shadow: 0px 2px 2px #5C5696;
	cursor: pointer;
	transition: .2s;
    justify-content: center;
}

.login__submit:active,
.login__submit:focus,
.login__submit:hover {
	border-color: #6A679E;
	outline: none;
}

.button__icon {
	font-size: 24px;
	margin-left: auto;
	color: #7875B5;
}

</style>
<body>



<div class="container">
	<div class="screen">
		<div class="screen__content">
			<form class="login" method="post">
				<div class="login__field">
					<i class="login__icon fas fa-user"></i>
					<input type="text" name="username" class="login__input" placeholder="User Name" required>
				</div>
				<div class="login__field">
					<i class="login__icon fas fa-lock"></i>
					<input type="password" name="password" class="login__input" placeholder="Password" required>
				</div>
                <div style="width: 175px; height: 50px;">
<?php foreach ($errors as $e): ?>
    <p style="color:red; width:"><?= htmlspecialchars($e) ?></p>
<?php endforeach; ?>
</div>
				<button class="button login__submit" type="submit">
					<span class="button__text">Log In</span>
					<!-- <i class="button__icon fas fa-chevron-right"></i> -->
				</button>				
			</form>
		</div>
		<div class="screen__background">
			<span class="screen__background__shape screen__background__shape4"></span>
			<span class="screen__background__shape screen__background__shape3"></span>		
			<span class="screen__background__shape screen__background__shape2"></span>
			<span class="screen__background__shape screen__background__shape1"></span>
		</div>		
	</div>
</div>

<!-- <form method="post">
    <label>Username: <input type="text" name="username" required></label><br><br>
    <label>Password: <input type="password" name="password" required></label><br><br>
    <button type="submit">Login</button>
</form> -->
</body>
</html>
