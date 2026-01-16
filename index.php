<?php
/*
============================================================
LANGUAGE CONFIG
============================================================
*/

$supportsMultiLang = false;
$supportedLangs    = ['en', 'jp'];
$defaultLang       = 'jp';

/*
============================================================
REQUEST PARSING
============================================================
*/

$uri   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$query = $_SERVER['QUERY_STRING'] ?? '';

$segments = array_values(array_filter(explode('/', trim($uri, '/'))));

$lang = $defaultLang;

// Detect language ONLY if multilang is enabled
if ($supportsMultiLang && isset($segments[0]) && in_array($segments[0], $supportedLangs, true)) {
  $lang = $segments[0];
  array_shift($segments);
}

/*
============================================================
CANONICAL NORMALIZATION
Canonical:
- trailing slash
- no ".php"
- language prefix ONLY if enabled
============================================================
*/

// Build canonical path
$canonicalPath = '/';

if ($supportsMultiLang) {
  $canonicalPath .= $lang . '/';
}

if (!empty($segments)) {
  $canonicalPath .= implode('/', $segments) . '/';
}

// Normalize incoming URI for comparison
$normalizedUri = rtrim(preg_replace('~\.php$~i', '', $uri), '/') . '/';

// Redirect if non-canonical
if ($normalizedUri !== $canonicalPath) {
  $location = $canonicalPath;
  if ($query !== '') {
    $location .= '?' . $query;
  }

  header('Location: ' . $location, true, 301);
  exit;
}

/*
============================================================
ROUTING
============================================================
*/

$route = implode('/', $segments);

if ($route === '') {
  $file = __DIR__ . '/pages/index.php';
} else {
  $mapped = rtrim(str_replace('/', '__', $route), '_');
  $file   = __DIR__ . "/pages/{$mapped}.php";
}

/*
============================================================
DISPATCH
============================================================
*/

$_GET['lang'] = $lang;

if (is_file($file)) {
  require $file;
  exit;
}

$attemptedFile = $file;
http_response_code(404);
require __DIR__ . '/404.php';
