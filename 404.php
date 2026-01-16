<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 - Page not found</title>
<style>
body {
  font-family: monospace;
  padding: 20px;
}

.debug {
  margin-top: 10px;
  color: #a00;
}
</style>
</head>
<body>

<h1>404 - Page not found</h1>

<p>The requested URL could not be resolved.</p>

<div class="debug">
<?php
http_response_code(404);

echo "<strong>Requested URI:</strong><br>";
echo htmlspecialchars($_SERVER['REQUEST_URI']) . "<br><br>";
?>
</div>

</body>
</html>
