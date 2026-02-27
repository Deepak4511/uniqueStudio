<?php

/**
 * UNIQUE STUDIO - WhatsApp Configuration
 * Settings for WhatsApp order integration
 */

// WhatsApp Business Number (with country code, no '+')
// NOTE: Also defined as WHATSAPP_NUMBER in config/database.php — kept in sync here
if (!defined('WA_NUMBER')) {
    define('WA_NUMBER', defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : '919876543210');
}

// WhatsApp API Method: 'link' (wa.me) or 'api' (WhatsApp Business API)
if (!defined('WA_METHOD')) {
    define('WA_METHOD', 'link');
}


/**
 * Generate WhatsApp message link for order notification
 * 
 * @param array $order Order data array
 * @param string $pdf_url PDF download URL
 * @return string WhatsApp redirect URL
 */
function generateWhatsAppLink($order, $pdf_url = '')
{
    $number = WA_NUMBER;

    $message  = "🛍️ *New Order - " . SITE_NAME . "*\n";
    $message .= "━━━━━━━━━━━━━━━━━━━\n\n";
    $message .= "📋 *Order Number:* " . $order['order_number'] . "\n";
    $message .= "📅 *Date:* " . date('d M Y, h:i A') . "\n\n";

    $message .= "👤 *Customer Details:*\n";
    $message .= "• Name: " . $order['customer_name'] . "\n";
    $message .= "• Phone: " . $order['customer_phone'] . "\n";
    $message .= "• Email: " . $order['customer_email'] . "\n\n";

    $message .= "📍 *Delivery Address:*\n";
    $message .= $order['customer_address'];
    if (!empty($order['customer_city'])) {
        $message .= "\n" . $order['customer_city'];
    }
    if (!empty($order['customer_state'])) {
        $message .= ", " . $order['customer_state'];
    }
    if (!empty($order['customer_pincode'])) {
        $message .= " - " . $order['customer_pincode'];
    }
    $message .= "\n\n";

    // Order Items
    $message .= "🛒 *Order Items:*\n";
    $items = json_decode($order['order_items'], true);
    if (is_array($items)) {
        $i = 1;
        foreach ($items as $item) {
            $message .= $i . ". " . $item['name'] . "\n";
            $message .= "   Qty: " . $item['quantity'];
            if (!empty($item['options'])) {
                $message .= " | Options: " . $item['options'];
            }
            $message .= " | Price: " . CURRENCY_SYMBOL . number_format($item['total_price'], 2) . "\n";
            $i++;
        }
    }
    $message .= "\n";

    $message .= "💰 *Price Summary:*\n";
    $message .= "• Subtotal: " . CURRENCY_SYMBOL . number_format($order['subtotal'], 2) . "\n";
    if ($order['gst_amount'] > 0) {
        $message .= "• GST (" . GST_RATE . "%): " . CURRENCY_SYMBOL . number_format($order['gst_amount'], 2) . "\n";
    }
    $message .= "• Delivery: " . CURRENCY_SYMBOL . number_format($order['delivery_charge'], 2) . "\n";
    $message .= "• *TOTAL: " . CURRENCY_SYMBOL . number_format($order['total_amount'], 2) . "*\n\n";

    if (!empty($order['special_instructions'])) {
        $message .= "📝 *Special Instructions:*\n" . $order['special_instructions'] . "\n\n";
    }

    if (!empty($pdf_url)) {
        $message .= "📄 *Order PDF:* " . $pdf_url . "\n\n";
    }

    $message .= "━━━━━━━━━━━━━━━━━━━\n";
    $message .= "_Please confirm this order and send payment details._";

    return "https://wa.me/" . $number . "?text=" . urlencode($message);
}

/**
 * Generate simple WhatsApp contact link
 * 
 * @param string $pre_filled_message Pre-filled message text
 * @return string WhatsApp URL
 */
function getWhatsAppContactLink($pre_filled_message = '')
{
    $number = WA_NUMBER;
    if (empty($pre_filled_message)) {
        $pre_filled_message = "Hi! I'm interested in your printing services.";
    }
    return "https://wa.me/" . $number . "?text=" . urlencode($pre_filled_message);
}
