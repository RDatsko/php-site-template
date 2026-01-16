<footer>
  Footer Code
</footer>

<?php

if (isset($add_jsb) && is_array($add_jsb)) {
  foreach ($add_jsb as $jsb_file) {
    echo "<script src=\"" . htmlspecialchars($jsb_file, ENT_QUOTES, 'UTF-8') . "\" /></script>\n";
  }
}

?>

</body>
</html>
