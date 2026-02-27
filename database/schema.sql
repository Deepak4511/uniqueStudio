-- ================================================
-- UNIQUE STUDIO - DATABASE SCHEMA (v2)
-- E-Commerce with WhatsApp Order System
-- Run this in phpMyAdmin or MySQL CLI
-- ================================================

CREATE DATABASE IF NOT EXISTS unique_studio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE unique_studio;

-- ------------------------------------------------
-- Categories Table
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS categories (
    id           INT PRIMARY KEY AUTO_INCREMENT,
    name         VARCHAR(100) NOT NULL,
    slug         VARCHAR(100) UNIQUE NOT NULL,
    description  TEXT,
    image        VARCHAR(255),
    parent_id    INT DEFAULT NULL,
    display_order INT DEFAULT 0,
    status       ENUM('active','inactive') DEFAULT 'active',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ------------------------------------------------
-- Products Table
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id                INT PRIMARY KEY AUTO_INCREMENT,
    category_id       INT NOT NULL,
    name              VARCHAR(200) NOT NULL,
    slug              VARCHAR(200) UNIQUE NOT NULL,
    short_description TEXT,
    long_description  TEXT,
    base_price        DECIMAL(10,2) NOT NULL,
    image_main        VARCHAR(255),
    images_gallery    TEXT,
    customizable      ENUM('yes','no') DEFAULT 'no',
    min_quantity      INT DEFAULT 1,
    featured          ENUM('yes','no') DEFAULT 'no',
    status            ENUM('active','inactive') DEFAULT 'active',
    created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- ------------------------------------------------
-- Product Options Table (Size, Paper Type, etc.)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS product_options (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    product_id     INT NOT NULL,
    option_type    VARCHAR(50) NOT NULL,
    option_name    VARCHAR(100) NOT NULL,
    option_values  TEXT NOT NULL,
    price_modifier DECIMAL(10,2) DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ------------------------------------------------
-- Quantity-based Pricing Table
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS product_pricing (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    product_id     INT NOT NULL,
    quantity_from  INT NOT NULL,
    quantity_to    INT NOT NULL,
    price_per_unit DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ------------------------------------------------
-- Orders Table
-- NOTE: 'status' column (not order_status) for consistency
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id                   INT PRIMARY KEY AUTO_INCREMENT,
    order_number         VARCHAR(50) UNIQUE NOT NULL,
    customer_name        VARCHAR(100) NOT NULL,
    customer_email       VARCHAR(100) NOT NULL,
    customer_phone       VARCHAR(20) NOT NULL,
    customer_address     TEXT NOT NULL,
    customer_city        VARCHAR(100),
    customer_state       VARCHAR(100),
    customer_pincode     VARCHAR(10),
    order_items          TEXT NOT NULL,
    subtotal             DECIMAL(10,2) NOT NULL,
    gst_amount           DECIMAL(10,2) DEFAULT 0,
    delivery_charge      DECIMAL(10,2) DEFAULT 0,
    total_amount         DECIMAL(10,2) NOT NULL,
    status               ENUM('pending','confirmed','in_production','shipped','delivered','cancelled') DEFAULT 'pending',
    payment_status       ENUM('pending','paid','cod') DEFAULT 'pending',
    pdf_path             VARCHAR(255),
    design_file          VARCHAR(255),
    whatsapp_sent        ENUM('yes','no') DEFAULT 'no',
    special_instructions TEXT,
    admin_notes          TEXT,
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ------------------------------------------------
-- Contact Inquiries Table
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_inquiries (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    phone      VARCHAR(20),
    subject    VARCHAR(200),
    message    TEXT NOT NULL,
    status     ENUM('new','replied','closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------
-- Site Settings Table
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS site_settings (
    id            INT PRIMARY KEY AUTO_INCREMENT,
    setting_key   VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT
);

-- ================================================
-- SEED DATA - Categories
-- ================================================
INSERT IGNORE INTO categories (name, slug, description, display_order, status) VALUES
('Business Cards',    'business-cards',    'Premium quality business cards for professionals', 1, 'active'),
('Photo Gifts',       'photo-gifts',       'Custom photo gifts for all occasions',              2, 'active'),
('Custom Apparel',    'custom-apparel',    'Personalized clothing and accessories',             3, 'active'),
('Marketing Materials','marketing-materials','Brochures, flyers, banners and more',            4, 'active'),
('Stationery',        'stationery',        'Office stationery and supplies',                   5, 'active'),
('Photo Frames',      'photo-frames',      'Beautiful frames for your memories',               6, 'active'),
('Cup Printing',      'cup-printing',      'Custom printed mugs and cups',                     7, 'active'),
('Wall Display',      'wall-display',      'Canvas prints and wall art',                       8, 'active');

SET @stationery_id := (SELECT id FROM categories WHERE slug = 'stationery' LIMIT 1);
INSERT IGNORE INTO categories (name, slug, description, parent_id, display_order, status)
SELECT 'ID Cards', 'id-cards', 'Employee/student ID cards and accessories', @stationery_id, 1, 'active'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'id-cards');

SET @idcards_id := (SELECT id FROM categories WHERE slug = 'id-cards' LIMIT 1);
INSERT IGNORE INTO categories (name, slug, description, parent_id, display_order, status)
SELECT 'PVC ID Cards', 'pvc-id-cards', 'Durable PVC ID cards', @idcards_id, 1, 'active'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'pvc-id-cards');

INSERT IGNORE INTO categories (name, slug, description, display_order, status)
SELECT 'Mobile Cases', 'mobile-cases', 'Protective and custom mobile cases', 9, 'active'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'mobile-cases');

SET @mobilecases_id := (SELECT id FROM categories WHERE slug = 'mobile-cases' LIMIT 1);
INSERT IGNORE INTO categories (name, slug, description, parent_id, display_order, status)
SELECT 'Apple', 'apple', 'Apple brand cases', @mobilecases_id, 1, 'active'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'apple');

SET @apple_id := (SELECT id FROM categories WHERE slug = 'apple' LIMIT 1);
INSERT IGNORE INTO categories (name, slug, description, parent_id, display_order, status)
SELECT 'iPhone 14', 'iphone-14', 'Cases for iPhone 14', @apple_id, 1, 'active'
WHERE NOT EXISTS (SELECT 1 FROM categories WHERE slug = 'iphone-14');

-- ================================================
-- SEED DATA - Products
-- ================================================
INSERT IGNORE INTO products (category_id, name, slug, short_description, long_description, base_price, customizable, min_quantity, featured, status) VALUES
-- Business Cards
(1, 'Standard Business Cards', 'standard-business-cards',
 'Professional matte business cards printed on 300 GSM paper',
 '<p>High-quality standard business cards printed on 300 GSM matte paper. Perfect for professionals.</p><ul><li>300 GSM premium paper</li><li>Matte or glossy finish</li><li>Standard 3.5x2 inch size</li><li>Both sides printing available</li></ul>',
 299.00, 'yes', 100, 'yes', 'active'),

(1, 'Premium UV Business Cards', 'premium-business-cards',
 'Luxury spot-UV business cards on 400 GSM paper',
 '<p>Premium business cards with spot UV coating on 400 GSM paper. Make a lasting impression.</p><ul><li>400 GSM premium board</li><li>Spot UV / Soft touch lamination</li><li>Ultra-sharp printing</li></ul>',
 599.00, 'yes', 100, 'yes', 'active'),

(1, 'Rounded Corner Cards', 'rounded-corner-cards',
 'Elegant business cards with smooth rounded corners',
 '<p>Stand out with beautifully rounded corner business cards on premium paper stock.</p>',
 399.00, 'yes', 100, 'no', 'active'),

(1, 'Transparent PVC Cards', 'transparent-cards',
 'Crystal clear PVC transparent business cards',
 '<p>Ultra-modern transparent PVC business cards that leave a lasting impression.</p>',
 899.00, 'yes', 50, 'yes', 'active'),

-- Photo Gifts
(2, 'Custom Photo Mug', 'custom-photo-mug',
 'Personalized ceramic mug with your photo — perfect gift',
 '<p>Print your favourite photos on high-quality ceramic mugs. Perfect gift for loved ones.</p><ul><li>11oz ceramic mug</li><li>Dishwasher safe coating</li><li>Vibrant full-colour print</li></ul>',
 349.00, 'yes', 1, 'yes', 'active'),

(2, 'Canvas Print', 'canvas-print',
 'Gallery-quality canvas prints from your photos',
 '<p>Transform your photos into stunning gallery-quality canvas prints.</p>',
 799.00, 'yes', 1, 'yes', 'active'),

(2, 'Custom Cushion', 'custom-cushion',
 'Soft cushions with your personalized design or photo',
 '<p>Snuggle up with custom printed cushions featuring your favourite photos.</p>',
 549.00, 'yes', 1, 'no', 'active'),

(2, 'Custom Mouse Pad', 'custom-mouse-pad',
 'Custom printed mouse pads for work or gifting',
 '<p>Personalize your workspace with custom printed mouse pads.</p>',
 199.00, 'yes', 1, 'no', 'active'),

-- Apparel
(3, 'Custom T-Shirt', 'custom-t-shirt',
 'High-quality 180 GSM cotton t-shirts with custom print',
 '<p>Premium 180 GSM cotton t-shirts with vibrant custom printing. Available in all sizes.</p><ul><li>180 GSM 100% cotton</li><li>Vibrant dye-sublimation print</li><li>Sizes: S, M, L, XL, XXL</li><li>Available in multiple colors</li></ul>',
 299.00, 'yes', 5, 'yes', 'active'),

(3, 'Custom Polo Shirt', 'custom-polo-shirt',
 'Professional polo shirts with custom logo',
 '<p>Corporate polo shirts with custom logo or design. Ideal for uniforms and events.</p>',
 499.00, 'yes', 10, 'no', 'active'),

(3, 'Custom Hoodie', 'custom-hoodie',
 'Cozy fleece hoodies with your custom design',
 '<p>Soft fleece hoodies with your favourite design or logo. Perfect for winter.</p>',
 699.00, 'yes', 5, 'no', 'active'),

(3, 'Custom Cap', 'custom-cap',
 'Stylish caps with embroidered or printed logo',
 '<p>Baseball caps with custom embroidery or printing. Great for teams and events.</p>',
 249.00, 'yes', 10, 'no', 'active'),

-- Marketing Materials
(4, 'Custom Flyers', 'custom-flyers',
 'Eye-catching flyers for your business on 130 GSM paper',
 '<p>High-impact flyers printed on 130 GSM paper. Single or double sided available.</p>',
 199.00, 'yes', 500, 'no', 'active'),

(4, 'Brochures', 'brochures',
 'Professional tri-fold or bi-fold brochures on 170 GSM',
 '<p>Premium quality brochures on 170 GSM glossy paper. Various folding options.</p>',
 349.00, 'yes', 200, 'no', 'active'),

(4, 'Large Format Banners', 'large-format-banners',
 'Durable outdoor and indoor banners — any size',
 '<p>High-resolution banners for events, shops and outdoor advertising.</p>',
 599.00, 'yes', 1, 'yes', 'active'),

(4, 'Custom Stickers', 'custom-stickers',
 'Die-cut waterproof stickers in any shape and size',
 '<p>Vibrant waterproof stickers for branding, packaging and promotion.</p>',
 149.00, 'yes', 100, 'no', 'active'),

-- Stationery
(5, 'Custom Letterheads', 'custom-letterheads',
 'Professional company letterheads on 90 GSM paper',
 '<p>Premium letterheads on 90 GSM paper with your company branding.</p>',
 299.00, 'yes', 200, 'no', 'active'),

(5, 'Custom Notepads', 'custom-notepads',
 'Branded notepads for meetings and daily office use',
 '<p>Custom notepads with your logo for meetings and daily use.</p>',
 249.00, 'yes', 50, 'no', 'active'),

(5, 'Custom Calendars', 'custom-calendars',
 'Personalized wall and desk calendars for gifting',
 '<p>Personalized wall and desk calendars for gifting and corporate use.</p>',
 399.00, 'yes', 25, 'no', 'active'),

-- Photo Frames
(6, 'College Photo Frame', 'college-photo-frame',
 'Elegant frames for college photos and certificates',
 '<p>Elegant frames perfect for college photos, degrees and certificates.</p>',
 549.00, 'yes', 1, 'no', 'active'),

(6, 'Family Photo Collage Frame', 'family-collage-frame',
 'Multi-photo collage frames for family memories',
 '<p>Showcase multiple family photos in a beautiful collage frame.</p>',
 699.00, 'yes', 1, 'no', 'active'),

-- Cup Printing
(7, 'Magic Color Changing Mug', 'magic-color-mug',
 'Heat-sensitive mugs that reveal photos when filled',
 '<p>Amazing heat-sensitive mugs that reveal your photo as you drink hot beverages.</p>',
 449.00, 'yes', 1, 'yes', 'active'),

(7, 'Tall Coffee Mug', 'tall-coffee-mug',
 'Large 15oz custom printed premium coffee mug',
 '<p>Tall premium coffee mugs with vibrant custom printing.</p>',
 399.00, 'yes', 1, 'no', 'active'),

-- Wall Display
(8, 'Canvas Wall Art', 'canvas-wall-art',
 'Large format canvas wall art — transform your photos',
 '<p>Transform your photos into stunning large format canvas wall art.</p>',
 999.00, 'yes', 1, 'yes', 'active'),

(8, 'Acrylic Wall Print', 'acrylic-wall-print',
 'Ultra-vivid prints behind premium acrylic glass',
 '<p>Ultra-vivid prints behind acrylic glass for a modern, high-end look.</p>',
 1299.00, 'yes', 1, 'no', 'active');

-- ================================================
-- SEED DATA - Product Options
-- ================================================
INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'size', 'Card Size', '3.5x2 inch,3.5x2.5 inch,Square 2.5x2.5 inch', 0
FROM products p WHERE p.slug = 'standard-business-cards' LIMIT 1;

INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'finish', 'Finish Type', 'Matte,Glossy,Silk Lamination', 50
FROM products p WHERE p.slug = 'standard-business-cards' LIMIT 1;

INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'size', 'Mug Size', '11oz Standard,15oz Large', 50
FROM products p WHERE p.slug = 'custom-photo-mug' LIMIT 1;

INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'size', 'T-Shirt Size', 'S,M,L,XL,XXL', 0
FROM products p WHERE p.slug = 'custom-t-shirt' LIMIT 1;

INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'color', 'T-Shirt Color', 'White,Black,Navy Blue,Red,Grey', 0
FROM products p WHERE p.slug = 'custom-t-shirt' LIMIT 1;

INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'print', 'Print Side', 'Front Only,Back Only,Front & Back', 100
FROM products p WHERE p.slug = 'custom-t-shirt' LIMIT 1;

INSERT IGNORE INTO product_options (product_id, option_type, option_name, option_values, price_modifier)
SELECT p.id, 'size', 'Banner Size', '2x4 ft,3x6 ft,4x8 ft,6x10 ft', 200
FROM products p WHERE p.slug = 'large-format-banners' LIMIT 1;

-- ================================================
-- SEED DATA - Quantity Based Pricing
-- ================================================
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 100,  249,  2.99 FROM products WHERE slug = 'standard-business-cards' LIMIT 1;
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 250,  499,  2.49 FROM products WHERE slug = 'standard-business-cards' LIMIT 1;
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 500,  999,  1.99 FROM products WHERE slug = 'standard-business-cards' LIMIT 1;
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 1000, 99999, 1.49 FROM products WHERE slug = 'standard-business-cards' LIMIT 1;

INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 5,  9,     299 FROM products WHERE slug = 'custom-t-shirt' LIMIT 1;
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 10, 24,    249 FROM products WHERE slug = 'custom-t-shirt' LIMIT 1;
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 25, 49,    219 FROM products WHERE slug = 'custom-t-shirt' LIMIT 1;
INSERT IGNORE INTO product_pricing (product_id, quantity_from, quantity_to, price_per_unit)
SELECT id, 50, 99999, 199 FROM products WHERE slug = 'custom-t-shirt' LIMIT 1;

-- ================================================
-- SEED DATA - Site Settings
-- ================================================
INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES
('site_name',          'Unique Studio'),
('site_email',         'orders@uniquestudio.com'),
('site_phone',         '+91 98765-43210'),
('whatsapp_number',    '919876543210'),
('site_address',       'Indore, Madhya Pradesh, India'),
('gst_rate',           '18'),
('delivery_charge',    '100'),
('free_delivery_above','2000'),
('currency_symbol',    '₹'),
('order_prefix',       'UST');
