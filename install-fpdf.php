<?php

/**
 * FPDF Installer Script
 * Run this once from browser: http://localhost/uniqueStudio/install-fpdf.php
 * DELETE after use!
 */
set_time_limit(120);

echo '<pre>';
echo "Attempting to download FPDF...\n\n";

// Try downloading fpdf.php directly
$fpdf_url  = 'https://raw.githubusercontent.com/Setasign/FPDF/master/fpdf.php';
$save_path = __DIR__ . '/vendor/fpdf/fpdf.php';

// Create directory
if (!is_dir(dirname($save_path))) {
    mkdir(dirname($save_path), 0755, true);
    echo "✅ Created vendor/fpdf/ directory\n";
}

// Check if real FPDF already exists
if (file_exists($save_path)) {
    $content = file_get_contents($save_path);
    if (strpos($content, 'class FPDF') !== false && strpos($content, 'stub') === false) {
        echo "✅ Real FPDF already installed!\n";
        echo "You can delete this file (install-fpdf.php)\n";
        exit;
    }
}

// Try file_get_contents
$ctx = stream_context_create([
    'http' => [
        'timeout'       => 30,
        'user_agent'    => 'Mozilla/5.0',
        'ignore_errors' => true,
    ],
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ],
]);

$fpdf_content = @file_get_contents($fpdf_url, false, $ctx);

if ($fpdf_content && strlen($fpdf_content) > 10000 && strpos($fpdf_content, 'class FPDF') !== false) {
    file_put_contents($save_path, $fpdf_content);
    echo "✅ FPDF downloaded and installed successfully!\n";
    echo "Size: " . number_format(strlen($fpdf_content)) . " bytes\n";
    echo "\nVerifying installation...\n";
    require_once $save_path;
    if (class_exists('FPDF')) {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'FPDF Test OK');
        echo "✅ FPDF class works correctly!\n";
        echo "\n🎉 Installation complete! Delete this file (install-fpdf.php)\n";
    }
} else {
    echo "❌ Download failed (file_get_contents)\n";
    echo "Response length: " . strlen($fpdf_content ?: '') . " bytes\n\n";
    echo "MANUAL INSTALLATION STEPS:\n";
    echo "1. Go to: https://github.com/Setasign/FPDF/archive/refs/heads/master.zip\n";
    echo "2. Download and extract the ZIP\n";
    echo "3. Copy the file 'fpdf.php' from the extracted folder\n";
    echo "4. Paste it into: " . dirname($save_path) . "\\\n";
    echo "5. Rename it to 'fpdf.php' if needed\n\n";
    echo "The system will work WITHOUT FPDF — orders will still process, just without PDF generation.\n";
}

echo "\n<a href='index.php'>← Back to Homepage</a>\n";
echo '</pre>';
