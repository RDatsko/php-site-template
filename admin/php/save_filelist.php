<?php
// save_filelist.php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['filename']) || !isset($data['lines'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
    exit;
}

// $filename = basename($data['filename']); // prevent directory traversal
$lines = $data['lines'];

// Save to the same folder as the original file
// $filepath = __DIR__ . '/' . $filename;
$filepath = $data['filename'];

$result = file_put_contents($filepath, implode("\n", $lines));

if ($result === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to write file']);
} else {
    echo json_encode(['status' => 'success', 'message' => 'File saved', 'path' => $filepath]);
}
?>
