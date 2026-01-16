<?php
$hash = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['password'])) {
    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Hash Generator</title>
</head>
<body>

<form method="post">
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <button type="submit">Generate Hash</button>
</form>

<?php if ($hash): ?>
    <p><strong>Generated hash:</strong></p>
    <code><?= htmlspecialchars($hash) ?></code>
<?php endif; ?>

</body>
</html>
