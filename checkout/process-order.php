<?php

/**
 * UNIQUE STUDIO - Process Order
 * Handles checkout form submission, saves order, generates PDF, redirects to WhatsApp
 */

require_once '../includes/functions.php';
require_once '../config/whatsapp-config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get cart
$cart = getCart();
if (empty($cart)) {
    header('Location: ../cart/view-cart.php');
    exit;
}

// ============================================================
// 1. Validate and sanitize form data
// ============================================================
$errors = [];

$customer_name        = sanitize($_POST['customer_name'] ?? '');
$customer_email       = sanitize($_POST['customer_email'] ?? '');
$customer_phone       = sanitize($_POST['customer_phone'] ?? '');
$company_name         = sanitize($_POST['company_name'] ?? '');
$customer_address     = sanitize($_POST['customer_address'] ?? '');
$customer_city        = sanitize($_POST['customer_city'] ?? '');
$customer_state       = sanitize($_POST['customer_state'] ?? '');
$customer_pincode     = sanitize($_POST['customer_pincode'] ?? '');
$special_instructions = sanitize($_POST['special_instructions'] ?? '');

if (empty($customer_name))    $errors[] = 'Customer name is required.';
if (empty($customer_email) || !isValidEmail($customer_email)) $errors[] = 'Valid email address is required.';
if (empty($customer_phone))   $errors[] = 'Phone number is required.';
if (empty($customer_address)) $errors[] = 'Delivery address is required.';
if (empty($customer_city))    $errors[] = 'City is required.';
if (empty($customer_state))   $errors[] = 'State is required.';
if (empty($customer_pincode)) $errors[] = 'PIN code is required.';

if (!empty($errors)) {
    $_SESSION['checkout_errors'] = $errors;
    header('Location: index.php');
    exit;
}

// ============================================================
// 2. Calculate totals
// ============================================================
$totals   = calculateCartTotals();
$subtotal = $totals['subtotal'];
$gst      = $totals['gst'];
$delivery = $totals['delivery'];
$total    = $totals['total'];

// ============================================================
// 3. Handle file uploads
// ============================================================
$uploaded_files = [];
if (!empty($_FILES['design_files']['name'][0])) {
    $upload_dir = UPLOAD_DIR . 'customer-designs/';
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

    foreach ($_FILES['design_files']['name'] as $i => $fileName) {
        if ($_FILES['design_files']['error'][$i] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (
                in_array($ext, ['jpg', 'jpeg', 'png', 'pdf', 'ai', 'psd']) &&
                $_FILES['design_files']['size'][$i] <= MAX_UPLOAD_SIZE
            ) {
                $newName = uniqid('design_') . '.' . $ext;
                $target  = $upload_dir . $newName;
                if (move_uploaded_file($_FILES['design_files']['tmp_name'][$i], $target)) {
                    $uploaded_files[] = 'uploads/customer-designs/' . $newName;
                }
            }
        }
    }
}

// ============================================================
// 4. Build order items array
// ============================================================
$order_items = [];
foreach ($cart as $item) {
    $order_items[] = [
        'name'        => $item['name'],
        'quantity'    => $item['quantity'],
        'unit_price'  => $item['unit_price'],
        'total_price' => $item['total_price'],
        'options'     => $item['options_str'] ?? '',
    ];
}

// ============================================================
// 5. Save order to database
// ============================================================
$order_number = generateOrderNumber();

$order_data = [
    'order_number'        => $order_number,
    'customer_name'       => $customer_name,
    'customer_email'      => $customer_email,
    'customer_phone'      => $customer_phone,
    'customer_address'    => $customer_address,
    'customer_city'       => $customer_city,
    'customer_state'      => $customer_state,
    'customer_pincode'    => $customer_pincode,
    'order_items'         => json_encode($order_items),
    'subtotal'            => $subtotal,
    'gst_amount'          => $gst,
    'delivery_charge'     => $delivery,
    'total_amount'        => $total,
    'special_instructions' => $special_instructions,
];

$order_id = saveOrder($order_data);

if (!$order_id) {
    $_SESSION['checkout_errors'] = ['Could not save order. Please try again.'];
    header('Location: index.php');
    exit;
}

// ============================================================
// 6. Generate PDF
// ============================================================
$pdf_url  = '';
$pdf_path = '';

// Check if FPDF is available
$fpdf_path = __DIR__ . '/../vendor/fpdf/fpdf.php';
if (file_exists($fpdf_path)) {
    require_once $fpdf_path;

    try {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetMargins(15, 15, 15);

        // Header
        $pdf->SetFont('Arial', 'B', 22);
        $pdf->SetTextColor(0, 78, 124);
        $pdf->Cell(0, 12, 'UNIQUE STUDIO', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 7, 'Order Quotation Request', 0, 1, 'C');
        $pdf->Ln(3);

        // Separator line
        $pdf->SetDrawColor(255, 107, 53);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(8);

        // Order Number & Date
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(90, 8, 'ORDER NUMBER: ' . $order_number, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 8, 'DATE: ' . date('d M Y, h:i A'), 0, 1, 'R');
        $pdf->Ln(5);

        // Customer Details Section
        $pdf->SetFillColor(0, 78, 124);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, '  CUSTOMER DETAILS', 0, 1, 'L', true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $details = [
            ['Name:', $customer_name],
            ['Email:', $customer_email],
            ['Phone:', $customer_phone],
            ['Address:', $customer_address],
            ['City/State:', $customer_city . ', ' . $customer_state . ' - ' . $customer_pincode],
        ];
        foreach ($details as $row) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(35, 6, $row[0], 0, 0);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, $row[1], 0, 1);
        }
        $pdf->Ln(5);

        // Order Items Section
        $pdf->SetFillColor(255, 107, 53);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, '  ORDER ITEMS', 0, 1, 'L', true);
        $pdf->Ln(3);

        // Table header
        $pdf->SetFillColor(245, 247, 250);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(80, 7, 'Product Name', 'B', 0, 'L', false);
        $pdf->Cell(40, 7, 'Options', 'B', 0, 'L', false);
        $pdf->Cell(20, 7, 'Qty', 'B', 0, 'C', false);
        $pdf->Cell(25, 7, 'Unit Price', 'B', 0, 'R', false);
        $pdf->Cell(25, 7, 'Total', 'B', 1, 'R', false);

        $pdf->SetFont('Arial', '', 9);
        $alternateRow = false;
        foreach ($order_items as $i => $item) {
            if ($alternateRow) {
                $pdf->SetFillColor(252, 252, 252);
                $fill = true;
            } else {
                $fill = false;
            }
            $alternateRow = !$alternateRow;

            $pdf->Cell(80, 6, ($i + 1) . '. ' . substr($item['name'], 0, 40), 0, 0, 'L', $fill);
            $pdf->Cell(40, 6, substr($item['options'], 0, 20), 0, 0, 'L', $fill);
            $pdf->Cell(20, 6, $item['quantity'], 0, 0, 'C', $fill);
            $pdf->Cell(25, 6, 'Rs.' . number_format($item['unit_price'], 2), 0, 0, 'R', $fill);
            $pdf->Cell(25, 6, 'Rs.' . number_format($item['total_price'], 2), 0, 1, 'R', $fill);
        }
        $pdf->Ln(5);

        // Pricing Summary
        $pdf->SetFillColor(0, 78, 124);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 8, '  PRICING SUMMARY', 0, 1, 'L', true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);

        $summary_lines = [
            ['Subtotal:', 'Rs.' . number_format($subtotal, 2)],
            ['GST (18%):', 'Rs.' . number_format($gst, 2)],
            ['Delivery Charge:', $delivery == 0 ? 'FREE' : 'Rs.' . number_format($delivery, 2)],
        ];
        foreach ($summary_lines as $line) {
            $pdf->Cell(140, 6, $line[0], 0, 0, 'R');
            $pdf->Cell(40, 6, $line[1], 0, 1, 'R');
        }

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(255, 107, 53);
        $pdf->Cell(140, 8, 'TOTAL AMOUNT:', 0, 0, 'R');
        $pdf->Cell(40, 8, 'Rs.' . number_format($total, 2), 0, 1, 'R');
        $pdf->Ln(5);

        // Special Instructions
        if (!empty($special_instructions)) {
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 6, 'SPECIAL INSTRUCTIONS:', 0, 1);
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(80, 80, 80);
            $pdf->MultiCell(0, 5, $special_instructions);
            $pdf->Ln(5);
        }

        // Next Steps
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, 'NEXT STEPS:', 0, 1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(80, 80, 80);
        $steps = [
            '1. Our team will review your order',
            '2. We will send you payment details via WhatsApp',
            '3. Production begins after payment confirmation',
            '4. Estimated delivery: 5-7 business days',
        ];
        foreach ($steps as $step) {
            $pdf->Cell(0, 5, $step, 0, 1);
        }
        $pdf->Ln(5);

        // Footer
        $pdf->SetDrawColor(255, 107, 53);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 5, 'CONTACT US: WhatsApp: +91 ' . WA_NUMBER . ' | Email: ' . SITE_EMAIL, 0, 1, 'C');
        $pdf->Cell(0, 5, 'Website: www.uniquestudio.com', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->Cell(0, 5, 'Generated: ' . date('d M Y h:i A') . ' | This is a quotation request only, NOT a tax invoice.', 0, 1, 'C');

        // Save PDF
        $pdf_dir = __DIR__ . '/../uploads/order-pdfs/';
        if (!is_dir($pdf_dir)) mkdir($pdf_dir, 0755, true);

        $pdf_filename = $order_number . '.pdf';
        $pdf_save_path = $pdf_dir . $pdf_filename;
        $pdf->Output('F', $pdf_save_path);

        $pdf_path = 'uploads/order-pdfs/' . $pdf_filename;
        $pdf_url  = SITE_URL . '/uploads/order-pdfs/' . $pdf_filename;

        updateOrderPdfPath($order_number, $pdf_path);
    } catch (Exception $e) {
        error_log("PDF generation error: " . $e->getMessage());
        // Continue without PDF - order is saved
    }
}

// ============================================================
// 7. Clear cart and save order number in session
// ============================================================
$_SESSION['last_order_number'] = $order_number;
$_SESSION['last_order_total']  = $total;
clearCart();

// ============================================================
// 8. Generate WhatsApp link
// ============================================================
$order_full = array_merge($order_data, [
    'gst_amount'     => $gst,
    'delivery_charge' => $delivery,
    'total_amount'   => $total,
]);
$whatsapp_link = generateWhatsAppLink($order_full, $pdf_url);

// Store in session for confirmation page
$_SESSION['whatsapp_link'] = $whatsapp_link;
$_SESSION['pdf_url']       = $pdf_url;

// ============================================================
// 9. Redirect to WhatsApp OR confirmation page
// ============================================================
// Option A: Direct redirect to WhatsApp
// header('Location: ' . $whatsapp_link);

// Option B: Go to confirmation page first (recommended)
header('Location: order-confirmation.php?order=' . urlencode($order_number));
exit;
