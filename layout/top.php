<?php

$hImage = "";
$hText = array("タイトル","Title");
$links = array(
  "リンク1", "Link 1", "#", "", "",
  "リンク2", "Link 2", "#", "", "",
  "リンク3", "Link 3", "#", "", "",
  "リンク4", "Link 4", "#", "", "",
  "リンク5", "Link 5", "#", "", "",
);
$morelink = array("", "");

?>
<!DOCTYPE html>
<?php


if($_GET["lang"] == "en") {
  echo "<html lang=\"en\">\n";
} else {
  echo "<html lang=\"jp\">\n";
}


?>
<head>
<meta charset="UTF-8">
<?php

if (isset($add_meta) && is_array($add_meta)) {
  echo "\n<!-- メタタグ記述 -->\n";

  foreach ($add_meta as $name => $content) {
    if (!empty($content)) { // Skip empty meta tags
      if ($name === 'title') {
        echo "<title>" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "</title>\n";
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no\" />\n";
        // echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover\" />\n";
      } else {
        echo "<meta name=\"" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "\" content=\"" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "\" />\n";
      }
    }
  }
}

if (isset($share_og)) {
  echo "\n<!-- og系記述 -->\n";

  foreach ($share_og as $property => $content) {
    if (is_array($content)) {
      foreach ($content as $subcontent) {
        echo "<meta property=\"og:$property\" content=\"" . htmlspecialchars($subcontent, ENT_QUOTES, 'UTF-8') . "\" />\n";
      }
    } else {
      echo "<meta property=\"og:$property\" content=\"" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "\" />\n";
    }
  }
}

if (isset($share_x) || isset($share_og)) {
  echo "\n<!-- Twitter/𝕏系記述 -->\n";

  // Merge Twitter-specific properties with OpenGraph properties
  $share_twitter = array_merge(
    array_intersect_key($share_og, array_flip(['title', 'description', 'image', 'url'])),
    $share_x
  );

  foreach ($share_twitter as $property => $content) {
    if (is_array($content)) {
      foreach ($content as $subcontent) {
        echo "<meta property=\"twitter:$property\" content=\"" . htmlspecialchars($subcontent, ENT_QUOTES, 'UTF-8') . "\" />\n";
      }
    } else {
      echo "<meta property=\"twitter:$property\" content=\"" . htmlspecialchars($content, ENT_QUOTES, 'UTF-8') . "\" />\n";
    }
  }
}

echo "\n";

?>

<link rel="stylesheet" type="text/css" href="/layout/css/cosmuic.css">

<?php

if (isset($add_css) && is_array($add_css)) {
  echo "\n<!-- CSSファイル記述 -->\n";

  foreach ($add_css as $css_file) {
    echo "<link rel=\"stylesheet\" href=\"" . htmlspecialchars($css_file, ENT_QUOTES, 'UTF-8') . "\" />\n";
  }
}

if (isset($add_jst) && is_array($add_jst)) {
  foreach ($add_jst as $jst_file) {
    echo "<script src=\"" . htmlspecialchars($jst_file, ENT_QUOTES, 'UTF-8') . "\" /></script>\n";
  }
}

?>

</head>
<body>

<header data-nav="right">
  <span>
    <input type="checkbox" />
      <div>
        <!-- Show site icon / company logo -->
        <a href="/" class="clear"><img src="/layout/image/site_icon.png"></a>

<?php

if (!empty($hImage)) { echo "<a href=\"/" . $_GET["sub"] . "/\"><img src=\"$hImage\"></a>"; }
else if (isset($_GET["lang"]) && $_GET["lang"] == "jp") {
  // If language is Japanese, output the first element (Japanese text)
  echo "<a href=\"/" . $_GET["sub"] . "/\">$hText[0]<a>";  // Outputs: 会社
} else {
  // Check if the second element (English) exists
  if (isset($hText[1])) {
    // If the second element exists, output the English text
    echo "<a href=\"/" . $_GET["sub"] . "/\">$hText[1]<a>";  // Outputs: Company
  } else {
    // If the second element doesn't exist, provide an error message
    echo "English text is not available.";
  }
}

?>
    </div>
  <nav>
<?php

if (isset($_GET["lang"]) && $_GET["lang"] == "jp") {
  if($hImage != "") {
    echo "<a href=\"/" . $_GET["sub"] . "/\"><img src=\"$hImage\"></a>";
  } else {
    echo "<h6><a href=\"/" . $_GET["sub"] . "/\">$hText[0]<a></h6>";
  }
  // echo "<p>リンク</p>";
  echo "<ol>";
  // echo '<li class="more ' . $morelink[0] . '"><a>もっと見る</a></li>';
  for ($i = 0; $i < count($links); $i += 5) {
    if($links[$i + 3] == "") {
      echo '<li><a href="' . $links[$i + 2] . '">' . $links[$i] . '</a></li>';
    } else {
      echo '<li class="' . $links[$i + 3] . '"><a href="' . $links[$i + 2] . '">' . $links[$i] . '</a></li>';
    }
  }
  echo "</ol>";
} else {
  if($hImage != "") {
    echo "<img src=\"$hImage\">";
  } else {
    echo "<h6>$hText[1]</h6>";
  }
  // echo "<p>リンク</p>";
  echo "<ol>";
  // echo '<li class="more' . $morelink[1] . '"><a>MORE</a></li>';
  for ($i = 0; $i < count($links); $i += 5) {
    if($links[$i + 4] == "") {
      echo '<li><a href="' . $links[$i + 2] . '">' . $links[$i + 1] . '</a></li>';
    } else {
      echo '<li class="' . $links[$i + 4] . '"><a href="' . $links[$i + 2] . '">' . $links[$i + 1] . '</a></li>';
    }
  }
  echo "</ol>";
}

?>
    </nav>
  </span>
</header>
