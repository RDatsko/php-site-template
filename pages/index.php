<?php

if($_GET["lang"] == "jp") {
  $page_title = "ホメパゲ";
  $header_logo = "";
  $header_text = "株式会社";
} else {
  $page_title = "Homepage";
  $header_logo = "";
  $header_text = "Company";
}

$add_meta = array(
  "title"       => $page_title,
  "description" => "Page description"
);

$add_css  = array(
  "/layout/css/styles.css"
);

$add_jst  = array(
);

$add_jsb  = array(
);

// OpenGraph (og) : https://ogp.me/
$share_og = array(
  // required
  "title"            => "Company Home Page",
  "image"            => "http://127.0.0.1/image/share/og_homepage.jpg",
  "url"              => "http://12.0.0.1/",
  "type"             => "website",

  // optional
  "description"      => "Description of the page",
  "locale"           => "ja_JP",
  "locale:alternate" => array("en_US")
);

// Twitter / 𝕏 specific
$share_x = array(
  "creator"     => "@example",
  "site"        => "@example",
  "card"        => "summary_large_image"
);

include('../layout/top.php');
?>

<h1>Hello World</h1>

<?php include('../layout/bottom.php'); ?>