<?php

/**
 * UNIQUE STUDIO - Upload Design File API
 * Handles customer design file uploads
 */

header('Content-Type: application/json');

require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

if (empty($_FILES['design']) || $_FILES['design']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

$file     = $_FILES['design'];
$ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed  = ['jpg', 'jpeg', 'png', 'pdf', 'ai', 'psd'];
$max_size = MAX_UPLOAD_SIZE;

if (!in_array($ext, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'File type not allowed. Use: JPG, PNG, PDF, AI, PSD']);
    exit;
}

if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum 5MB allowed.']);
    exit;
}

$upload_dir = UPLOAD_DIR . 'customer-designs/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$filename = uniqid('design_') . '_' . time() . '.' . $ext;
$target   = $upload_dir . $filename;

if (move_uploaded_file($file['tmp_name'], $target)) {
    echo json_encode([
        'success'   => true,
        'message'   => 'File uploaded successfully',
        'filename'  => $filename,
        'path'      => 'uploads/customer-designs/' . $filename,
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not save file. Please try again.']);
}
exit;
